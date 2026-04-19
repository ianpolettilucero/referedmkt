<?php
namespace Admin\Controllers;

use Admin\AdminView;
use Admin\Context;
use Core\Auth;
use Core\Csrf;
use Core\Flash;
use Core\Migrator;

/**
 * Base de admin controllers.
 *
 * Regla: TODAS las rutas que heredan esta clase requieren usuario autenticado.
 * La unica ruta publica del admin es /admin/login (AuthController).
 * El routing aplica la guard antes de instanciar estos controllers.
 */
abstract class BaseController
{
    protected AdminView $view;

    /** @var array<string, mixed> */
    protected array $user;

    public function __construct()
    {
        $this->user = Auth::user() ?: [];
        $this->view = new AdminView();
    }

    protected function render(string $view, array $data = []): void
    {
        $data = array_merge([
            'user'               => $this->user,
            'active_site'        => Context::activeSite(),
            'sites'              => Context::visibleSites(),
            'csrf_token'         => Csrf::token(),
            'flashes'            => Flash::consume(),
            'pending_migrations' => $this->pendingMigrationsCount(),
        ], $data);
        echo $this->view->render($view, $data);
    }

    /**
     * Cuenta cheap de migraciones pendientes para el banner. Cacheada 60s via
     * APCu si esta disponible para no golpear la DB en cada request del admin.
     */
    private function pendingMigrationsCount(): int
    {
        $cacheKey = 'refmkt_pending_migrations';
        if (function_exists('apcu_fetch')) {
            $hit = apcu_fetch($cacheKey, $ok);
            if ($ok) { return (int)$hit; }
        }
        try {
            $n = count(Migrator::pending());
        } catch (\Throwable $e) {
            $n = 0;
        }
        if (function_exists('apcu_store')) {
            apcu_store($cacheKey, $n, 60);
        }
        return $n;
    }

    protected function redirect(string $path, int $code = 302): void
    {
        header('Location: ' . $path, true, $code);
        exit;
    }

    protected function requireCsrf(): void
    {
        Csrf::requireValid();
    }

    protected function requireSite(): array
    {
        return Context::requireActiveSite();
    }

    protected function input(string $key, $default = null)
    {
        return $_POST[$key] ?? $_GET[$key] ?? $default;
    }

    protected function intInput(string $key, ?int $default = null): ?int
    {
        $v = $this->input($key);
        return ($v === null || $v === '') ? $default : (int)$v;
    }

    protected function boolInput(string $key): bool
    {
        return filter_var($this->input($key, false), FILTER_VALIDATE_BOOLEAN);
    }

    protected function jsonInput(string $key): ?array
    {
        $raw = $this->input($key, '');
        if (!is_string($raw) || trim($raw) === '') {
            return null;
        }
        // Permitir "un item por linea" como shortcut para arrays simples.
        if (strncmp(ltrim($raw), '[', 1) !== 0 && strncmp(ltrim($raw), '{', 1) !== 0) {
            $lines = array_values(array_filter(array_map('trim', explode("\n", $raw)), fn($l) => $l !== ''));
            return $lines;
        }
        $decoded = json_decode($raw, true);
        return is_array($decoded) ? $decoded : null;
    }
}
