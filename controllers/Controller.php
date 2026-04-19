<?php
namespace Controllers;

use Core\SEO;
use Core\Site;
use Core\View;

/**
 * Controller base.
 * Provee helpers para render de views con layout y respuesta 404 semantica.
 */
abstract class Controller
{
    protected Site $site;
    protected View $view;
    protected SEO  $seo;

    public function __construct()
    {
        $this->site = Site::current();
        $this->view = new View();
        $this->seo  = new SEO($this->site);
    }

    /**
     * @param array<string, mixed> $data
     */
    protected function render(string $view, array $data = []): void
    {
        // Inyecta site y seo para que layouts/partials tengan acceso sin recibirlos manualmente.
        $data = array_merge([
            'site' => $this->site,
            'seo'  => $this->seo,
        ], $data);

        echo $this->view->render($view, $data);
    }

    protected function notFound(string $message = 'No encontrado'): void
    {
        http_response_code(404);
        $this->seo->rawTitle('404 - ' . $this->site->name)->noindex();
        $this->render('404', ['message' => $message]);
    }
}
