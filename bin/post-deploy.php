<?php
/**
 * Post-deploy hook. Aplica migraciones pendientes silenciosamente.
 *
 * Pensado para correr despues de un git pull (manual o via cron).
 * Idempotente: si no hay migraciones pendientes, termina en 0.
 *
 * Uso directo:
 *   php bin/post-deploy.php
 *
 * Via cron (cada 10 min, chequea si hay migraciones nuevas despues de un pull):
 *   [CRON]  /10 * * * * /usr/bin/php /home/USER/public_html/bin/post-deploy.php
 *   (reemplaza [CRON] por un asterisco)
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("CLI solamente.\n");
}

$started = date('c');
$rc = 0;
$out = shell_exec('php ' . escapeshellarg(dirname(__DIR__) . '/migrate.php') . ' 2>&1');

// Solo loggeamos si hubo trabajo real.
if ($out !== null && strpos($out, 'Nothing to migrate') === false) {
    echo "[$started] post-deploy:\n" . $out . "\n";
}
exit($rc);
