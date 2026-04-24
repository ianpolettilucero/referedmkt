<?php
namespace Admin\Controllers;

use Core\Database;
use Core\Flash;
use Core\GscInspector;
use Core\IndexNow;
use Models\IndexStatus;

/**
 * /admin/index-health — panel de estado de indexacion (GSC URL Inspection).
 * Similar a /admin/link-health pero para indexacion en Google.
 */
final class IndexHealthController extends BaseController
{
    public function index(): void
    {
        $site = $this->requireSite();
        $grouped = IndexStatus::groupedForSite((int)$site['id']);
        $configured = GscInspector::isConfigured((int)$site['id']);

        $this->render('index_health/index', [
            'grouped'    => $grouped,
            'configured' => $configured,
            'site'       => $site,
            'page_title' => 'Health de indexación',
        ]);
    }

    /**
     * Chequea todas las URLs publicas del sitio contra GSC.
     * Tarda segun cuantas URLs tengas: ~300ms por URL (+ rate limit).
     */
    public function checkAll(): void
    {
        $this->requireCsrf();
        $site = $this->requireSite();

        if (!GscInspector::isConfigured((int)$site['id'])) {
            Flash::error('GSC no está configurado. Cargá el JSON del service account en Settings → Indexación.');
            $this->redirect('/admin/index-health');
            return;
        }

        $force = $this->boolInput('force');
        @set_time_limit(600);
        try {
            $r = IndexStatus::checkAllForSite((int)$site['id'], $force);
            Flash::success(sprintf(
                'Chequeo terminado: %d consultadas, %d saltadas (cache <24h), %d errores.',
                $r['checked'], $r['skipped'], $r['errors']
            ));
        } catch (\Throwable $e) {
            Flash::error('Error: ' . $e->getMessage());
        }
        $this->redirect('/admin/index-health');
    }

    public function checkOne(): void
    {
        $this->requireCsrf();
        $site = $this->requireSite();
        $url = trim((string)$this->input('url', ''));
        if ($url === '') { $this->redirect('/admin/index-health'); return; }
        if (!GscInspector::isConfigured((int)$site['id'])) {
            Flash::error('GSC no está configurado.');
            $this->redirect('/admin/index-health');
            return;
        }
        try {
            $r = IndexStatus::checkUrl((int)$site['id'], $url, true);
            if (!empty($r['ok'])) {
                Flash::success('Chequeado. Verdict: ' . ($r['verdict'] ?? '—') . ' · ' . ($r['coverage_state'] ?? '—'));
            } else {
                Flash::error('Error al chequear: ' . ($r['error'] ?? 'desconocido'));
            }
        } catch (\Throwable $e) {
            Flash::error('Error: ' . $e->getMessage());
        }
        $this->redirect('/admin/index-health');
    }

    /**
     * Pinguea IndexNow (Bing/Yandex) con todas las URLs publicas.
     * Util para forzar re-crawl despues de un cambio grande.
     */
    public function pingIndexNow(): void
    {
        $this->requireCsrf();
        $site = $this->requireSite();
        $urls = IndexStatus::publicUrls((int)$site['id']);
        if (!$urls) { Flash::error('Sin URLs publicas para pingear.'); $this->redirect('/admin/index-health'); return; }

        // IndexNow acepta 10k URLs max por request. Para un sitio normal estamos
        // muy por debajo de eso.
        $ok = IndexNow::ping((int)$site['id'], $urls);
        if ($ok) {
            Flash::success('IndexNow pingeado con ' . count($urls) . ' URLs. Bing/Yandex las crawleran en breve.');
        } else {
            Flash::error('IndexNow fallo. Revisa el error log del servidor.');
        }
        $this->redirect('/admin/index-health');
    }
}
