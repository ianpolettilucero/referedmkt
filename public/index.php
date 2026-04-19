<?php
/**
 * Front controller publico. Todas las requests pasan por aca via .htaccess rewrite.
 */

require dirname(__DIR__) . '/core/bootstrap.php';

use Core\Router;
use Core\Site;
use Controllers\ArticleController;
use Controllers\CategoryController;
use Controllers\HomeController;
use Controllers\ProductController;
use Controllers\RedirectController;
use Controllers\RobotsController;
use Controllers\SitemapController;

// El admin es global (no requiere tenant). Se delega antes de resolver Site.
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
if (strncmp($currentPath, '/admin', 6) === 0
    && ($currentPath === '/admin' || $currentPath[6] === '/')) {
    require APP_ROOT . '/admin/entry.php';
    exit;
}

$site = Site::resolve();
if ($site === null) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=utf-8');
    echo "Sitio no configurado para este dominio.";
    exit;
}

$router = new Router();

// Home
$router->get('/', [HomeController::class, 'index']);

// Catalogo / categorias
$router->get('/productos',                 [CategoryController::class, 'index']);
$router->get('/productos/{slug}',          [CategoryController::class, 'show']);
$router->get('/producto/{slug}',           [ProductController::class,  'show']);

// Articulos por tipo. Cada tipo tiene un indice y una pagina de detalle.
// El __type se inyecta como parametro para que el controller sepa el contexto.
$router->get('/guias',            fn($p) => (new ArticleController())->indexByType(['__type' => 'guide']));
$router->get('/guia/{slug}',      fn($p) => (new ArticleController())->show(array_merge($p, ['__type' => 'guide'])));

$router->get('/comparativas',     fn($p) => (new ArticleController())->indexByType(['__type' => 'comparison']));
$router->get('/comparativa/{slug}', fn($p) => (new ArticleController())->show(array_merge($p, ['__type' => 'comparison'])));

$router->get('/resenas',          fn($p) => (new ArticleController())->indexByType(['__type' => 'review']));
$router->get('/resena/{slug}',    fn($p) => (new ArticleController())->show(array_merge($p, ['__type' => 'review'])));

$router->get('/noticias',         fn($p) => (new ArticleController())->indexByType(['__type' => 'news']));
$router->get('/noticia/{slug}',   fn($p) => (new ArticleController())->show(array_merge($p, ['__type' => 'news'])));

// Afiliados + SEO infra
$router->get('/go/{slug}',    [RedirectController::class, 'affiliate']);
$router->get('/sitemap.xml',  [SitemapController::class,  'index']);
$router->get('/robots.txt',   [RobotsController::class,   'index']);

$router->setNotFound(function (string $path) {
    http_response_code(404);
    $seo = new \Core\SEO(\Core\Site::current());
    $seo->rawTitle('404 - ' . \Core\Site::current()->name)->noindex();
    $view = new \Core\View();
    echo $view->render('404', [
        'site'    => \Core\Site::current(),
        'seo'     => $seo,
        'message' => "La ruta $path no existe.",
    ]);
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
        header('Content-Type: text/plain; charset=utf-8');
        echo "500 - Error interno";
    }
}
