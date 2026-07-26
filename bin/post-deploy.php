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
$bin = dirname(__DIR__);

// 1) Migraciones pendientes.
$out = shell_exec('php ' . escapeshellarg($bin . '/migrate.php') . ' 2>&1');

// Solo loggeamos si hubo trabajo real.
if ($out !== null && strpos($out, 'Nothing to migrate') === false) {
    echo "[$started] post-deploy migraciones:\n" . $out . "\n";
}

// 2) Sincronizar contenido de content/*.md hacia la tabla articles.
//    Idempotente: si ningun .md cambio, no toca nada (ver content_files).
$imp = shell_exec('php ' . escapeshellarg($bin . '/bin/import-content.php') . ' 2>&1');
if ($imp !== null && !preg_match('/^Import: 0 nuevo\(s\), 0 actualizado\(s\)/m', $imp)) {
    echo "[$started] post-deploy contenido:\n" . $imp . "\n";
}

exit($rc);
