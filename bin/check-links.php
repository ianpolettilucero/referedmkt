<?php
/**
 * CLI: chequea health de links externos en articulos de uno o todos los sitios.
 *
 * Uso:
 *   php bin/check-links.php              # chequea todos los sitios
 *   php bin/check-links.php --site 1     # chequea site_id=1 solamente
 *   php bin/check-links.php --list       # muestra count de rotos por sitio, sin chequear
 *
 * Pensado para correr via cron (ej. diario a las 4am):
 *   0 4 * * * cd /home/USER/domains/capacero.online/public_html && php bin/check-links.php > /tmp/link-check.log 2>&1
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("CLI solamente.\n");
}

require dirname(__DIR__) . '/core/bootstrap.php';

use Core\Database;
use Core\LinkChecker;

$argv = $_SERVER['argv'] ?? [];

$mode = 'all';
$siteId = null;

for ($i = 1; $i < count($argv); $i++) {
    $a = $argv[$i];
    if ($a === '--help' || $a === '-h') {
        echo "Uso:\n";
        echo "  php bin/check-links.php              chequea todos los sitios\n";
        echo "  php bin/check-links.php --site N     chequea un sitio puntual\n";
        echo "  php bin/check-links.php --list       muestra count de rotos sin chequear\n";
        exit(0);
    }
    if ($a === '--list') { $mode = 'list'; }
    elseif ($a === '--site' && isset($argv[$i+1])) { $mode = 'site'; $siteId = (int)$argv[$i+1]; $i++; }
}

$sites = Database::instance()->fetchAll('SELECT id, domain FROM sites ORDER BY id');
if (!$sites) {
    fwrite(STDERR, "No hay sitios cargados.\n");
    exit(1);
}

if ($mode === 'list') {
    foreach ($sites as $s) {
        $broken = LinkChecker::brokenCountForSite((int)$s['id']);
        printf("  site %d (%s): %d link(s) roto(s)\n", $s['id'], $s['domain'], $broken);
    }
    exit(0);
}

$targets = $mode === 'site'
    ? array_filter($sites, fn($s) => (int)$s['id'] === $siteId)
    : $sites;

if (!$targets) {
    fwrite(STDERR, "Sitio $siteId no encontrado.\n");
    exit(1);
}

foreach ($targets as $s) {
    printf("[%s] Chequeando site %d (%s)...\n", date('Y-m-d H:i:s'), $s['id'], $s['domain']);
    $r = LinkChecker::checkAllForSite((int)$s['id']);
    printf("[%s]   -> %d articulos procesados, %d URLs chequeadas, %d rotos totales.\n",
        date('Y-m-d H:i:s'), $r['articles_processed'], $r['urls_checked'], $r['broken']);
}
echo "OK.\n";
