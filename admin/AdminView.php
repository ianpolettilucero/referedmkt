<?php
namespace Admin;

/**
 * View engine minimal para el admin. Similar a Core\View pero usa las carpetas
 * de admin/views/ en lugar del tema del tenant.
 */
final class AdminView
{
    private string $base;
    private ?string $layout = null;

    public function __construct()
    {
        $this->base = APP_ROOT . '/admin/views';
    }

    public function layout(string $name): void
    {
        $this->layout = $name;
    }

    public function render(string $view, array $data = []): string
    {
        $body = $this->renderFile($this->base . '/' . $view . '.php', $data);
        if ($this->layout === null) {
            return $body;
        }
        $file = $this->base . '/layouts/' . $this->layout . '.php';
        $this->layout = null;
        return $this->renderFile($file, array_merge($data, ['content' => $body]));
    }

    public function partial(string $name, array $data = []): string
    {
        return $this->renderFile($this->base . '/partials/' . $name . '.php', $data);
    }

    private function renderFile(string $file, array $data): string
    {
        if (!is_file($file)) {
            throw new \RuntimeException("Admin view no encontrada: $file");
        }
        $view = $this;
        extract($data, EXTR_SKIP);
        ob_start();
        try {
            include $file;
            return (string)ob_get_clean();
        } catch (\Throwable $e) {
            ob_end_clean();
            throw $e;
        }
    }
}
