<?php
namespace Admin\Controllers;

use Admin\Context;
use Core\Database;
use Core\Flash;

final class DashboardController extends BaseController
{
    public function index(): void
    {
        $site = Context::activeSite();
        $stats = ['products' => 0, 'articles' => 0, 'affiliate_links' => 0, 'clicks_30d' => 0, 'broken_links' => 0];

        if ($site) {
            $db = Database::instance();
            $stats['products']        = (int)$db->fetchColumn('SELECT COUNT(*) FROM products WHERE site_id = :s', ['s' => $site['id']]);
            $stats['articles']        = (int)$db->fetchColumn("SELECT COUNT(*) FROM articles WHERE site_id = :s AND status = 'published'", ['s' => $site['id']]);
            $stats['affiliate_links'] = (int)$db->fetchColumn('SELECT COUNT(*) FROM affiliate_links WHERE site_id = :s AND active = 1', ['s' => $site['id']]);
            $stats['clicks_30d']      = (int)$db->fetchColumn(
                "SELECT COUNT(*) FROM affiliate_clicks c
                 JOIN affiliate_links l ON l.id = c.affiliate_link_id
                 WHERE l.site_id = :s AND c.clicked_at >= (NOW() - INTERVAL 30 DAY)",
                ['s' => $site['id']]
            );
            $stats['broken_links']    = \Core\LinkChecker::brokenCountForSite((int)$site['id']);
        }

        $this->render('dashboard', ['stats' => $stats, 'page_title' => 'Dashboard']);
    }

    public function switchSite(): void
    {
        $this->requireCsrf();
        $id = (int)($_POST['site_id'] ?? 0);
        if (Context::setActiveSiteId($id)) {
            Flash::success('Sitio cambiado.');
        } else {
            Flash::error('No tenés acceso a ese sitio.');
        }
        $back = $_SERVER['HTTP_REFERER'] ?? '/admin/dashboard';
        $this->redirect($back);
    }
}
