<?php
namespace Core;

/**
 * View engine minimal basado en PHP puro.
 *
 * - Las views viven en themes/{theme}/views/*.php
 * - Los layouts en themes/{theme}/layouts/*.php
 * - Los partials en themes/{theme}/partials/*.php
 * - Cada view recibe $data extraido a variables locales.
 * - Una view declara su layout con $this->layout('default') y delega el cuerpo
 *   renderizado via $content dentro del layout.
 * - Las views llaman $this->partial('header', ['x' => ...]) para composables.
 *
 * IMPORTANTE: el escape NO es automatico. Los templates deben usar e($var) para
 * valores dinamicos. Esto es una decision consciente para permitir HTML confiable
 * (ej. output de Markdown ya sanitizado) sin un doble-escape manual en cada caso.
 */
final class View
{
    private string $themePath;
    private ?string $currentLayout = null;

    /** @var array<string, mixed> */
    private array $sections = [];

    public function __construct(?string $themePath = null)
    {
        $this->themePath = $themePath ?? Site::current()->themePath();
    }

    /**
     * Renderiza una view y (si declara layout) la envuelve. Devuelve el HTML.
     *
     * @param array<string, mixed> $data
     */
    public function render(string $view, array $data = []): string
    {
        $body = $this->renderFile($this->themePath . '/views/' . $view . '.php', $data);

        if ($this->currentLayout === null) {
            return $body;
        }

        $layoutFile = $this->themePath . '/layouts/' . $this->currentLayout . '.php';
        $this->currentLayout = null; // reset para siguientes render()
        return $this->renderFile($layoutFile, array_merge($data, ['content' => $body]));
    }

    /**
     * Invocado desde una view para declarar su layout.
     */
    public function layout(string $name): void
    {
        $this->currentLayout = $name;
    }

    /**
     * Render inline de un partial (devuelve HTML, no imprime).
     *
     * @param array<string, mixed> $data
     */
    public function partial(string $name, array $data = []): string
    {
        return $this->renderFile($this->themePath . '/partials/' . $name . '.php', $data);
    }

    /**
     * @param array<string, mixed> $data
     */
    private function renderFile(string $file, array $data): string
    {
        if (!is_file($file)) {
            throw new \RuntimeException("View file no encontrado: $file");
        }
        $view = $this; // accesible como $this dentro del template
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
