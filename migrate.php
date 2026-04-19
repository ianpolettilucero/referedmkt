<?php
/**
 * Migration runner CLI.
 *
 * Uso:
 *   php migrate.php            # aplica pendientes en orden
 *   php migrate.php --status   # muestra estado
 *
 * Ademas: desde el navegador podés correrlas en /install (primera vez) o
 * desde /admin (banner "Aplicar migraciones pendientes").
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo corre desde CLI. Usá /install o /admin para correr migraciones via web.\n");
}

require __DIR__ . '/core/bootstrap.php';

use Core\Migrator;

if (in_array('--status', $argv, true)) {
    echo "Applied:\n";
    foreach (Migrator::applied() as $f) { echo "  [x] $f\n"; }
    echo "Pending:\n";
    foreach (Migrator::pending() as $f) { echo "  [ ] $f\n"; }
    exit(0);
}

try {
    $r = Migrator::runPending();
    echo $r['log'];
    echo count($r['applied']) === 0
        ? "Nothing to migrate.\n"
        : "Applied " . count($r['applied']) . " migration(s).\n";
} catch (\Throwable $e) {
    fwrite(STDERR, "FAIL: " . $e->getMessage() . "\n");
    exit(1);
}
