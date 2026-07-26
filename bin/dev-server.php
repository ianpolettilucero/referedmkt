<?php
/**
 * Router para el servidor embebido de PHP (`php -S`). No se usa en produccion:
 * ahi Apache hace el rewrite via public/.htaccess.
 *
 * Por que hace falta: hasta PHP 8.3, el servidor embebido trata cualquier ruta
 * con extension de contenido estatico (.xml, .txt, .css…) como un pedido de
 * archivo. Si el archivo no existe devuelve 404 y NO pasa por index.php, asi
 * que /sitemap.xml, /robots.txt, /feed.xml y /llms.txt daban 404 aunque el
 * router de la app las tenga declaradas. PHP 8.4 cambio ese comportamiento, lo
 * que hacia que el smoke test pasara local y fallara en CI.
 *
 * Con este router el servidor embebido se comporta igual que Apache: sirve el
 * archivo si existe, y todo lo demas va al front controller.
 *
 * Uso:
 *   php -S 127.0.0.1:8080 -t public bin/dev-server.php
 */

$docRoot = dirname(__DIR__) . '/public';
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';

// Archivo real (assets, imagenes): que lo sirva el servidor tal cual.
$file = $docRoot . $path;
if ($path !== '/' && is_file($file)) {
    return false;
}

require $docRoot . '/index.php';
