<?php
/**
 * Front controller publico. Todas las requests pasan por aca via .htaccess rewrite.
 */

require dirname(__DIR__) . '/core/bootstrap.php';

use Core\Router;
use Core\Site;

$site = Site::resolve();
if ($site === null) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=utf-8');
    echo "Sitio no configurado para este dominio.";
    exit;
}

$router = new Router();

// Placeholders que se iran completando en siguientes iteraciones.
// Por ahora: home stub, tracking stub, sitemap stub, 404 default.

$router->get('/', function () use ($site) {
    header('Content-Type: text/plain; charset=utf-8');
    echo "OK - Sitio activo: {$site->name} ({$site->domain}) - tema: {$site->themeName}";
});

$router->get('/go/{slug}', [\Controllers\RedirectController::class, 'affiliate']);
$router->get('/sitemap.xml', [\Controllers\SitemapController::class, 'index']);
$router->get('/robots.txt',  [\Controllers\RobotsController::class,  'index']);

$router->setNotFound(function (string $path) use ($site) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=utf-8');
    echo "404 - {$path} no encontrado en {$site->domain}";
});

try {
    $router->dispatch();
} catch (\Throwable $e) {
    http_response_code(500);
    if (filter_var(getenv('APP_DEBUG') ?: 'false', FILTER_VALIDATE_BOOLEAN)) {
        header('Content-Type: text/plain; charset=utf-8');
        echo "500 - " . $e->getMessage() . "\n" . $e->getTraceAsString();
    } else {
        error_log('[referedmkt] ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine());
        echo "500 - Error interno";
    }
}
