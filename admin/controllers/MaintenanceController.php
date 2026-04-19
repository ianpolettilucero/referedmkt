<?php
namespace Admin\Controllers;

use Core\Auth;
use Core\Database;
use Core\Flash;
use Core\Migrator;

final class MaintenanceController extends BaseController
{
    /**
     * Aplica migraciones pendientes (desde el banner del admin).
     */
    public function migrate(): void
    {
        $this->requireCsrf();
        if (!Auth::isSuperadmin()) {
            Flash::error('Solo superadmin puede aplicar migraciones.');
            $this->redirect('/admin/dashboard');
        }
        try {
            $r = Migrator::runPending();
            $n = count($r['applied']);
            Flash::success($n === 0 ? 'Sin migraciones pendientes.' : "Aplicadas $n migracion(es).");
        } catch (\Throwable $e) {
            Flash::error('Error: ' . $e->getMessage());
        }
        $this->redirect('/admin/dashboard');
    }

    /**
     * Descarga backup de la DB como .sql.gz. Usa mysqldump si esta disponible;
     * fallback a dump PHP puro via PDO.
     */
    public function backup(): void
    {
        if (!Auth::isSuperadmin()) {
            http_response_code(403);
            exit('403');
        }

        $config = require APP_ROOT . '/config/config.php';
        $db = $config['db'];
        $filename = $db['name'] . '_' . date('Y-m-d_Hi') . '.sql.gz';

        header('Content-Type: application/gzip');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: no-store');

        $dumpCmd = trim((string)@shell_exec('command -v mysqldump 2>/dev/null'));
        if ($dumpCmd !== '' && $dumpCmd !== false && function_exists('proc_open')) {
            $this->streamMysqldump($dumpCmd, $db);
        } else {
            $this->streamPhpDump($db);
        }
    }

    private function streamMysqldump(string $cmd, array $db): void
    {
        // Escribimos password a un archivo temporal de credenciales (mas seguro
        // que pasar --password por CLI, que queda visible en `ps`).
        $cnf = tempnam(sys_get_temp_dir(), 'refmkt-my-');
        file_put_contents($cnf, "[client]\nuser=\"{$db['user']}\"\npassword=\"{$db['pass']}\"\n");
        @chmod($cnf, 0600);

        $shell = escapeshellcmd($cmd)
            . ' --defaults-extra-file=' . escapeshellarg($cnf)
            . ' --host='     . escapeshellarg($db['host'])
            . ' --port='     . (int)$db['port']
            . ' --single-transaction --quick --routines --default-character-set=utf8mb4'
            . ' ' . escapeshellarg($db['name'])
            . ' | gzip -9';

        $proc = popen($shell, 'r');
        if ($proc) {
            while (!feof($proc)) {
                echo fread($proc, 65536);
                @ob_flush(); @flush();
            }
            pclose($proc);
        }
        @unlink($cnf);
    }

    private function streamPhpDump(array $db): void
    {
        $pdo = new \PDO(
            sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
                $db['host'], $db['port'], $db['name']),
            $db['user'], $db['pass'],
            [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
        );

        $out = fopen('php://output', 'wb');
        $gz = null;
        if (function_exists('deflate_init')) {
            $gz = deflate_init(ZLIB_ENCODING_GZIP, ['level' => 6]);
        }
        $write = function (string $chunk) use ($out, $gz): void {
            if ($gz) {
                echo deflate_add($gz, $chunk, ZLIB_NO_FLUSH);
            } else {
                fwrite($out, $chunk);
            }
            @ob_flush(); @flush();
        };
        $flushGz = function () use ($gz): void {
            if ($gz) {
                echo deflate_add($gz, '', ZLIB_FINISH);
                @ob_flush(); @flush();
            }
        };

        $write("-- referedmkt PHP dump " . date('c') . "\n");
        $write("SET NAMES utf8mb4;\nSET FOREIGN_KEY_CHECKS=0;\n\n");

        foreach ($pdo->query("SHOW TABLES")->fetchAll(\PDO::FETCH_COLUMN) as $t) {
            $write("\n-- $t\nDROP TABLE IF EXISTS `$t`;\n");
            $create = $pdo->query("SHOW CREATE TABLE `$t`")->fetch(\PDO::FETCH_ASSOC);
            $write($create['Create Table'] . ";\n\n");

            $cols = null;
            $batch = [];
            foreach ($pdo->query("SELECT * FROM `$t`") as $row) {
                if ($cols === null) {
                    $cols = array_keys($row);
                    $write("INSERT INTO `$t` (`" . implode('`, `', $cols) . "`) VALUES\n");
                }
                $vals = array_map(function ($v) use ($pdo) {
                    if ($v === null) return 'NULL';
                    if (is_int($v) || is_float($v)) return (string)$v;
                    return $pdo->quote((string)$v);
                }, array_values($row));
                $batch[] = '(' . implode(',', $vals) . ')';
                if (count($batch) >= 200) {
                    $write(implode(",\n", $batch) . ";\n");
                    $batch = [];
                    $write("INSERT INTO `$t` (`" . implode('`, `', $cols) . "`) VALUES\n");
                }
            }
            if ($batch) $write(implode(",\n", $batch) . ";\n");
        }
        $write("\nSET FOREIGN_KEY_CHECKS=1;\n");
        $flushGz();
    }
}
