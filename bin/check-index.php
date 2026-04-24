<?php
/**
 * CLI: chequea estado de indexacion en Google Search Console para todas las
 * URLs publicas, por sitio. Requiere Settings → Indexación configurado.
 *
 * Uso:
 *   php bin/check-index.php                chequea todos los sitios
 *   php bin/check-index.php --site 1       solo site_id=1
 *   php bin/check-index.php --force        re-chequea aunque la cache sea <24h
 *   php bin/check-index.php --list         muestra count por sitio
 *
 * Pensado para cron diario (no mas — la cache es 24h):
 *   0 5 * * * cd /home/USER/domains/.../public_html && php bin/check-index.php > /tmp/idx.log 2>&1
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("CLI solamente.\n");
}

require dirname(__DIR__) . '/core/bootstrap.php';

use Core\Database;
use Core\GscInspector;
use Models\IndexStatus;

$argv = $_SERVER['argv'] ?? [];
$mode = 'all';
$siteId = null;
$force = false;

for ($i = 1; $i < count($argv); $i++) {
    $a = $argv[$i];
    if ($a === '--help' || $a === '-h') {
        echo "Uso:\n";
        echo "  php bin/check-index.php              chequea todos los sitios\n";
        echo "  php bin/check-index.php --site N     solo site_id=N\n";
        echo "  php bin/check-index.php --force      ignora cache de 24h\n";
        echo "  php bin/check-index.php --list       count por sitio (no chequea)\n";
        exit(0);
    }
    if ($a === '--list')  { $mode = 'list'; }
    elseif ($a === '--force') { $force = true; }
    elseif ($a === '--site' && isset($argv[$i+1])) { $mode = 'site'; $siteId = (int)$argv[$i+1]; $i++; }
}

$sites = Database::instance()->fetchAll('SELECT id, domain FROM sites ORDER BY id');
if (!$sites) {
    fwrite(STDERR, "No hay sitios cargados.\n");
    exit(1);
}

if ($mode === 'list') {
    foreach ($sites as $s) {
        $n = IndexStatus::notIndexedCount((int)$s['id']);
        printf("  site %d (%s): %d no indexada(s)\n", $s['id'], $s['domain'], $n);
    }
    exit(0);
}

$targets = $mode === 'site'
    ? array_filter($sites, fn($s) => (int)$s['id'] === $siteId)
    : $sites;
if (!$targets) { fwrite(STDERR, "Sitio $siteId no encontrado.\n"); exit(1); }

foreach ($targets as $s) {
    $sid = (int)$s['id'];
    if (!GscInspector::isConfigured($sid)) {
        printf("[%s] site %d (%s): GSC no configurado, skip.\n", date('Y-m-d H:i:s'), $sid, $s['domain']);
        continue;
    }
    printf("[%s] Chequeando site %d (%s)...\n", date('Y-m-d H:i:s'), $sid, $s['domain']);
    $r = IndexStatus::checkAllForSite($sid, $force);
    printf("[%s]   -> %d chequeadas, %d saltadas (cache), %d errores.\n",
        date('Y-m-d H:i:s'), $r['checked'], $r['skipped'], $r['errors']);
}
echo "OK.\n";
