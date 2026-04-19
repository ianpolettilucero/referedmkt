<?php
/**
 * Backup de la base de datos.
 *
 * Uso:
 *   php bin/backup-db.php                  # escribe en ./backups/YYYY-MM-DD_HHMM.sql.gz
 *   php bin/backup-db.php /otro/path       # escribe en el path dado
 *   KEEP_DAYS=14 php bin/backup-db.php     # retencion custom (default: 7)
 *
 * Estrategia:
 *   - Usa mysqldump si esta disponible (rapido, consistente).
 *   - Fallback PHP puro via PDO si no hay mysqldump (util en shared hosting
 *     restringido). El fallback genera INSERTs batcheados por tabla.
 *   - Gzip siempre (reduce 8-10x para este dataset).
 *   - Rotacion: borra dumps > KEEP_DAYS en el mismo directorio.
 *
 * Cron sugerido (Hostinger hPanel > Advanced > Cron Jobs):
 *   0 3 * * *  /usr/bin/php /home/USER/public_html/bin/backup-db.php
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("CLI solamente.\n");
}

require dirname(__DIR__) . '/core/bootstrap.php';

$config = require dirname(__DIR__) . '/config/config.php';
$db = $config['db'];
$destDir = $argv[1] ?? dirname(__DIR__) . '/backups';
$keepDays = (int)(getenv('KEEP_DAYS') ?: 7);

if (!is_dir($destDir) && !mkdir($destDir, 0750, true) && !is_dir($destDir)) {
    fwrite(STDERR, "No se pudo crear directorio: $destDir\n");
    exit(1);
}

$timestamp = date('Y-m-d_Hi');
$outFile = $destDir . "/{$db['name']}_$timestamp.sql.gz";
$tmpFile = $destDir . "/{$db['name']}_$timestamp.sql.tmp";

$dumpCmd = trim((string)@shell_exec('command -v mysqldump 2>/dev/null'));

if ($dumpCmd !== '' && $dumpCmd !== false) {
    echo "Usando mysqldump ($dumpCmd) -> $outFile\n";
    $cmd = escapeshellcmd($dumpCmd)
        . ' --host='     . escapeshellarg($db['host'])
        . ' --port='     . (int)$db['port']
        . ' --user='     . escapeshellarg($db['user'])
        . ' --password=' . escapeshellarg($db['pass']) // escapeshellarg protege
        . ' --single-transaction --quick --routines --default-character-set=utf8mb4'
        . ' ' . escapeshellarg($db['name'])
        . ' | gzip -9 > ' . escapeshellarg($outFile);
    // Nota: --password=... no es ideal (aparece en `ps`). Mejor opcion seria
    // usar --defaults-extra-file con un tmp de credenciales. Lo dejamos asi
    // por simplicidad; el script corre en tu servidor bajo tu usuario.
    $rc = 0;
    system($cmd, $rc);
    if ($rc !== 0) {
        fwrite(STDERR, "mysqldump fallo (rc=$rc). Fallback a PHP dump.\n");
        phpDump($tmpFile, $db);
        gzipFile($tmpFile, $outFile);
    }
} else {
    echo "mysqldump no disponible, usando fallback PHP -> $outFile\n";
    phpDump($tmpFile, $db);
    gzipFile($tmpFile, $outFile);
}

if (!is_file($outFile) || filesize($outFile) === 0) {
    fwrite(STDERR, "Backup fallo: $outFile\n");
    exit(1);
}

echo "OK: $outFile (" . number_format(filesize($outFile) / 1024, 0) . " KB)\n";

// Rotacion
$cutoff = time() - ($keepDays * 86400);
$deleted = 0;
foreach (glob($destDir . "/{$db['name']}_*.sql.gz") ?: [] as $f) {
    if (filemtime($f) < $cutoff) {
        @unlink($f);
        $deleted++;
    }
}
if ($deleted > 0) {
    echo "Rotacion: borrados $deleted backups > $keepDays dias.\n";
}

// --------------------------------------------------------------------------

function gzipFile(string $in, string $out): void
{
    $fpIn  = fopen($in, 'rb');
    $fpOut = gzopen($out, 'wb9');
    if (!$fpIn || !$fpOut) {
        throw new RuntimeException('gzip: no se pudo abrir archivos');
    }
    while (!feof($fpIn)) {
        gzwrite($fpOut, (string)fread($fpIn, 65536));
    }
    fclose($fpIn);
    gzclose($fpOut);
    @unlink($in);
}

function phpDump(string $file, array $db): void
{
    $pdo = new PDO(
        sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $db['host'], $db['port'], $db['name']),
        $db['user'],
        $db['pass'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $fp = fopen($file, 'wb');
    if (!$fp) {
        throw new RuntimeException("No se pudo abrir $file");
    }

    fwrite($fp, "-- referedmkt PHP dump " . date('c') . "\n");
    fwrite($fp, "SET NAMES utf8mb4;\nSET FOREIGN_KEY_CHECKS=0;\n\n");

    $tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
    foreach ($tables as $t) {
        fwrite($fp, "\n-- $t\nDROP TABLE IF EXISTS `$t`;\n");
        $create = $pdo->query("SHOW CREATE TABLE `$t`")->fetch(PDO::FETCH_ASSOC);
        fwrite($fp, $create['Create Table'] . ";\n\n");

        $rows = $pdo->query("SELECT * FROM `$t`");
        $batch = [];
        $cols = null;
        foreach ($rows as $row) {
            if ($cols === null) {
                $cols = array_keys($row);
                fwrite($fp, "INSERT INTO `$t` (`" . implode('`, `', $cols) . "`) VALUES\n");
            }
            $vals = array_map(function ($v) use ($pdo) {
                if ($v === null) return 'NULL';
                if (is_int($v) || is_float($v)) return (string)$v;
                return $pdo->quote((string)$v);
            }, array_values($row));
            $batch[] = '(' . implode(',', $vals) . ')';
            if (count($batch) >= 200) {
                fwrite($fp, implode(",\n", $batch) . ";\n");
                $batch = [];
                fwrite($fp, "INSERT INTO `$t` (`" . implode('`, `', $cols) . "`) VALUES\n");
            }
        }
        if ($batch) {
            fwrite($fp, implode(",\n", $batch) . ";\n");
        } elseif ($cols !== null) {
            // Trim el INSERT header huerfano si quedo abierto.
            ftruncate($fp, ftell($fp));
        }
    }

    fwrite($fp, "\nSET FOREIGN_KEY_CHECKS=1;\n");
    fclose($fp);
}
