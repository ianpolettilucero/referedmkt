<?php
/**
 * Front controller publico. Todas las requests pasan por aca via .htaccess rewrite.
 */

require dirname(__DIR__) . '/core/bootstrap.php';

use Core\Router;
use Core\Site;
use Controllers\ArticleController;
use Controllers\AuthorController;
use Controllers\CategoryController;
use Controllers\CompareController;
use Controllers\FeedController;
use Controllers\HomeController;
use Controllers\ProductController;
use Controllers\RedirectController;
use Controllers\RobotsController;
use Controllers\SearchController;
use Controllers\SitemapController;

// El admin es global (no requiere tenant). Se delega antes de resolver Site.
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
if (strncmp($currentPath, '/admin', 6) === 0
    && ($currentPath === '/admin' || $currentPath[6] === '/')) {
    require APP_ROOT . '/admin/entry.php';
    exit;
}

// Health check: global, sin dependencia de tenant. Para UptimeRobot / BetterStack.
if ($currentPath === '/healthz') {
    (new Controllers\HealthController())->check();
    exit;
}

$site = Site::resolve();
if ($site === null) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=utf-8');
    echo "Sitio no configurado para este dominio.";
    exit;
}

// Si el operador definio un redirect en el admin para este path, ejecutarlo
// antes de cualquier routing (preserva SEO ante cambios de URL).
\Core\Redirects::maybeHandle($site->id, $currentPath);

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

// Autores
$router->get('/autor/{slug}', [AuthorController::class, 'show']);

// Busqueda + comparador
$router->get('/buscar',   [SearchController::class,  'index']);
$router->get('/comparar', [CompareController::class, 'index']);

// Afiliados + SEO infra
$router->get('/go/{slug}',    [RedirectController::class, 'affiliate']);
$router->get('/sitemap.xml',  [SitemapController::class,  'index']);
$router->get('/robots.txt',   [RobotsController::class,   'index']);
$router->get('/feed.xml',     [FeedController::class,     'index']);

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

    // Ref corto para correlacionar error log <-> mensaje al usuario.
    $ref = substr(bin2hex(random_bytes(4)), 0, 8);
    error_log(sprintf(
        '[referedmkt][%s] %s @ %s:%d',
        $ref, $e->getMessage(), $e->getFile(), $e->getLine()
    ));
    error_log('[referedmkt][' . $ref . '] trace: ' . $e->getTraceAsString());

    header('Content-Type: text/plain; charset=utf-8');
    if (filter_var(getenv('APP_DEBUG') ?: 'false', FILTER_VALIDATE_BOOLEAN)) {
        echo "500 [$ref] " . $e->getMessage() . "\n";
        echo "en " . $e->getFile() . ":" . $e->getLine() . "\n\n";
        echo $e->getTraceAsString();
    } else {
        echo "500 - Error interno (ref: $ref)";
    }
}
