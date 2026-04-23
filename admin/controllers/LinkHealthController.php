<?php
namespace Admin\Controllers;

use Core\Database;
use Core\Flash;
use Core\LinkChecker;

/**
 * /admin/link-health — health monitor de links externos dentro del contenido
 * de articulos (no afiliados /go/).
 */
final class LinkHealthController extends BaseController
{
    public function index(): void
    {
        $site = $this->requireSite();

        // Traer TODOS los links (rotos/sospechosos/redirects) del sitio activo,
        // joined con articulo para mostrar contexto.
        $rows = Database::instance()->fetchAll(
            "SELECT al.*,
                    a.title AS article_title,
                    a.slug AS article_slug,
                    a.article_type
             FROM article_links al
             JOIN articles a ON a.id = al.article_id
             WHERE a.site_id = :s
             ORDER BY
                (al.ignored_at IS NOT NULL) ASC,
                (al.status_code IS NULL) DESC,
                (al.status_code = 404 OR al.status_code = 410 OR al.status_code >= 500) DESC,
                al.first_seen_broken_at ASC,
                al.last_checked_at DESC",
            ['s' => $site['id']]
        );

        // Agrupar por estado para render
        $broken = $suspicious = $redirected = $ignored = [];
        foreach ($rows as $r) {
            if ($r['ignored_at'] !== null)             { $ignored[]    = $r; continue; }
            $s = $r['status_code'];
            if ($s === null)                           { $broken[]     = $r; continue; }
            $s = (int)$s;
            if ($s === 404 || $s === 410 || $s >= 500) { $broken[]     = $r; continue; }
            if ($s === 403 || $s === 429)              { $suspicious[] = $r; continue; }
            if (!empty($r['final_url']))               { $redirected[] = $r; continue; }
            // 2xx sin redirect → OK, no lo mostramos
        }

        $this->render('link_health/index', [
            'broken'     => $broken,
            'suspicious' => $suspicious,
            'redirected' => $redirected,
            'ignored'    => $ignored,
            'page_title' => 'Health check de links',
        ]);
    }

    /**
     * Chequea todos los articulos del sitio activo (respeta cache de 6h por URL).
     */
    public function checkAll(): void
    {
        $this->requireCsrf();
        $site = $this->requireSite();

        // Timeout generoso: chequeos en paralelo + muchos articulos pueden tardar.
        @set_time_limit(300);
        try {
            $r = LinkChecker::checkAllForSite((int)$site['id']);
            Flash::success(sprintf(
                'Chequeados %d articulos, %d URLs nuevas, %d rotas detectadas.',
                $r['articles_processed'], $r['urls_checked'], $r['broken']
            ));
        } catch (\Throwable $e) {
            Flash::error('Error al chequear links: ' . $e->getMessage());
        }
        $this->redirect('/admin/link-health');
    }

    /**
     * Re-chequea un articulo especifico (force = true: ignora el cache de 6h).
     */
    public function checkArticle(array $params): void
    {
        $this->requireCsrf();
        $this->requireSite();
        $articleId = (int)($params['id'] ?? 0);
        if ($articleId <= 0) { $this->redirect('/admin/link-health'); return; }

        @set_time_limit(120);
        try {
            $r = LinkChecker::checkArticle($articleId, true);
            Flash::success(sprintf(
                'Articulo re-chequeado: %d OK, %d rotos, %d sospechosos.',
                $r['ok'], $r['broken'], $r['suspicious']
            ));
        } catch (\Throwable $e) {
            Flash::error('Error: ' . $e->getMessage());
        }

        // Volver al form del articulo si el header Referer apunta ahi, sino al listado
        $ref = $_SERVER['HTTP_REFERER'] ?? '';
        if (strpos($ref, '/admin/articles/') !== false) {
            $this->redirect('/admin/articles/' . $articleId . '/edit');
            return;
        }
        $this->redirect('/admin/link-health');
    }

    /**
     * Aplicar redirect: reescribe el markdown del articulo reemplazando la URL
     * vieja por final_url. Solo funciona si final_url respondio OK.
     */
    public function applyFix(array $params): void
    {
        $this->requireCsrf();
        $this->requireSite();
        $linkId = (int)($params['id'] ?? 0);

        // Verificar que el link pertenece al sitio activo (seguridad multi-tenant)
        if (!$this->linkBelongsToActiveSite($linkId)) {
            Flash::error('Link no encontrado.');
            $this->redirect('/admin/link-health');
            return;
        }

        $result = LinkChecker::applyRedirect($linkId);
        if ($result === false) {
            Flash::error('No se pudo aplicar el fix: el link no tiene una URL nueva valida.');
        } elseif ($result === 0) {
            Flash::info('La URL ya no estaba en el articulo. Registro limpiado.');
        } else {
            Flash::success("Redirect aplicado. $result ocurrencia(s) reemplazada(s) en el articulo.");
        }
        $this->redirect('/admin/link-health');
    }

    public function ignore(array $params): void
    {
        $this->requireCsrf();
        $this->requireSite();
        $linkId = (int)($params['id'] ?? 0);
        if (!$this->linkBelongsToActiveSite($linkId)) {
            $this->redirect('/admin/link-health'); return;
        }
        LinkChecker::ignore($linkId);
        Flash::success('Link marcado como OK (falso positivo).');
        $this->redirect('/admin/link-health');
    }

    public function unignore(array $params): void
    {
        $this->requireCsrf();
        $this->requireSite();
        $linkId = (int)($params['id'] ?? 0);
        if (!$this->linkBelongsToActiveSite($linkId)) {
            $this->redirect('/admin/link-health'); return;
        }
        LinkChecker::unignore($linkId);
        Flash::success('Link ya no esta silenciado. Se vuelve a chequear normalmente.');
        $this->redirect('/admin/link-health');
    }

    private function linkBelongsToActiveSite(int $linkId): bool
    {
        $site = $this->requireSite();
        $row = Database::instance()->fetch(
            'SELECT a.site_id
             FROM article_links al
             JOIN articles a ON a.id = al.article_id
             WHERE al.id = :id LIMIT 1',
            ['id' => $linkId]
        );
        return $row && (int)$row['site_id'] === (int)$site['id'];
    }
}
