<?php
/**
 * Entry point del admin. Invocado desde public/index.php cuando la ruta
 * empieza por /admin.
 *
 * El admin es GLOBAL (no tenant-scoped): el operador elige que sitio
 * gestionar via Context::activeSiteId(), que se guarda en session.
 */

use Core\Auth;
use Core\Csrf;
use Core\Router;
use Core\Session;
use Admin\Controllers as A;

// Autoloader de admin\ ya registrado por bootstrap (Core\Autoloader addNamespace Admin).
// Registramos ademas el sub-namespace Admin\Controllers\ -> admin/controllers/
\Core\Autoloader::addNamespace('Admin\\Controllers', APP_ROOT . '/admin/controllers');

Session::start();

$router = new Router();

$router->group('/admin', function (Router $r) {
    // Rutas publicas (login/logout)
    $r->get('/login',  [A\AuthController::class, 'showLogin']);
    $r->post('/login', [A\AuthController::class, 'login']);
    $r->post('/logout', [A\AuthController::class, 'logout']);
});

// Guard: todo lo que no sea /admin/login requiere auth.
$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$currentPath = rtrim($currentPath, '/') ?: '/';
$isPublicAdminRoute = in_array($currentPath, ['/admin/login'], true);
$isLogoutPost = $currentPath === '/admin/logout' && ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';

if (!Auth::check() && !$isPublicAdminRoute && !$isLogoutPost) {
    header('Location: /admin/login', true, 302);
    exit;
}

// Rutas autenticadas.
$router->group('/admin', function (Router $r) {
    $r->get('/',          [A\DashboardController::class, 'index']);
    $r->get('/dashboard', [A\DashboardController::class, 'index']);

    // Selector de sitio activo
    $r->post('/switch-site', [A\DashboardController::class, 'switchSite']);

    // Sites (no tenant-scoped, solo superadmin puede crear/editar)
    $r->get('/sites',             [A\SitesController::class, 'index']);
    $r->get('/sites/new',         [A\SitesController::class, 'create']);
    $r->post('/sites',            [A\SitesController::class, 'store']);
    $r->get('/sites/{id}/edit',   [A\SitesController::class, 'edit']);
    $r->post('/sites/{id}',       [A\SitesController::class, 'update']);
    $r->post('/sites/{id}/delete',[A\SitesController::class, 'destroy']);

    // Categories
    $r->get('/categories',              [A\CategoriesController::class, 'index']);
    $r->get('/categories/new',          [A\CategoriesController::class, 'create']);
    $r->post('/categories',             [A\CategoriesController::class, 'store']);
    $r->get('/categories/{id}/edit',    [A\CategoriesController::class, 'edit']);
    $r->post('/categories/{id}',        [A\CategoriesController::class, 'update']);
    $r->post('/categories/{id}/delete', [A\CategoriesController::class, 'destroy']);

    // Authors
    $r->get('/authors',              [A\AuthorsController::class, 'index']);
    $r->get('/authors/new',          [A\AuthorsController::class, 'create']);
    $r->post('/authors',             [A\AuthorsController::class, 'store']);
    $r->get('/authors/{id}/edit',    [A\AuthorsController::class, 'edit']);
    $r->post('/authors/{id}',        [A\AuthorsController::class, 'update']);
    $r->post('/authors/{id}/delete', [A\AuthorsController::class, 'destroy']);

    // Affiliate links
    $r->get('/affiliate-links',              [A\AffiliateLinksController::class, 'index']);
    $r->get('/affiliate-links/new',          [A\AffiliateLinksController::class, 'create']);
    $r->post('/affiliate-links',             [A\AffiliateLinksController::class, 'store']);
    $r->get('/affiliate-links/{id}/edit',    [A\AffiliateLinksController::class, 'edit']);
    $r->post('/affiliate-links/{id}',        [A\AffiliateLinksController::class, 'update']);
    $r->post('/affiliate-links/{id}/delete', [A\AffiliateLinksController::class, 'destroy']);

    // Products
    $r->get('/products',              [A\ProductsController::class, 'index']);
    $r->get('/products/new',          [A\ProductsController::class, 'create']);
    $r->post('/products',             [A\ProductsController::class, 'store']);
    $r->get('/products/{id}/edit',    [A\ProductsController::class, 'edit']);
    $r->post('/products/{id}',        [A\ProductsController::class, 'update']);
    $r->post('/products/{id}/delete', [A\ProductsController::class, 'destroy']);

    // Articles
    $r->get('/articles',              [A\ArticlesController::class, 'index']);
    $r->get('/articles/new',          [A\ArticlesController::class, 'create']);
    $r->post('/articles',             [A\ArticlesController::class, 'store']);
    $r->get('/articles/{id}/edit',    [A\ArticlesController::class, 'edit']);
    $r->post('/articles/{id}',        [A\ArticlesController::class, 'update']);
    $r->post('/articles/{id}/delete', [A\ArticlesController::class, 'destroy']);
    $r->post('/articles/preview',     [A\ArticlesController::class, 'preview']);

    // Redirects
    $r->get('/redirects',              [A\RedirectsController::class, 'index']);
    $r->get('/redirects/new',          [A\RedirectsController::class, 'create']);
    $r->post('/redirects',             [A\RedirectsController::class, 'store']);
    $r->get('/redirects/{id}/edit',    [A\RedirectsController::class, 'edit']);
    $r->post('/redirects/{id}',        [A\RedirectsController::class, 'update']);
    $r->post('/redirects/{id}/delete', [A\RedirectsController::class, 'destroy']);

    // Uploads
    $r->get('/uploads',              [A\UploadsController::class, 'index']);
    $r->post('/uploads',             [A\UploadsController::class, 'store']);
    $r->get('/uploads.json',         [A\UploadsController::class, 'listJson']);
    $r->post('/uploads/{id}/delete', [A\UploadsController::class, 'destroy']);

    // Maintenance (migraciones + backup)
    $r->post('/maintenance/migrate', [A\MaintenanceController::class, 'migrate']);
    $r->get('/maintenance/backup',   [A\MaintenanceController::class, 'backup']);

    // Settings
    $r->get('/settings',  [A\SettingsController::class, 'index']);
    $r->post('/settings', [A\SettingsController::class, 'update']);

    // Analytics
    $r->get('/analytics', [A\AnalyticsController::class, 'index']);
});

$router->setNotFound(function ($path) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=utf-8');
    echo "404 - Admin: $path";
});

$router->dispatch();
