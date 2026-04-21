<?php
namespace Admin\Controllers;

use Core\Database;

final class AnalyticsController extends BaseController
{
    public function index(): void
    {
        $site = $this->requireSite();
        $db = Database::instance();

        $days = max(7, min(365, (int)$this->input('days', 30)));
        $prevDays = $days * 2; // para calcular el periodo anterior

        // Clicks del rango actual
        $totalClicks = (int)$db->fetchColumn(
            "SELECT COUNT(*) FROM affiliate_clicks c
             JOIN affiliate_links l ON l.id = c.affiliate_link_id
             WHERE l.site_id = :s AND c.clicked_at >= (NOW() - INTERVAL $days DAY)",
            ['s' => $site['id']]
        );

        // Clicks del periodo anterior (mismo tamaño de ventana, inmediatamente previo)
        $prevClicks = (int)$db->fetchColumn(
            "SELECT COUNT(*) FROM affiliate_clicks c
             JOIN affiliate_links l ON l.id = c.affiliate_link_id
             WHERE l.site_id = :s
               AND c.clicked_at >= (NOW() - INTERVAL $prevDays DAY)
               AND c.clicked_at <  (NOW() - INTERVAL $days DAY)",
            ['s' => $site['id']]
        );

        // Delta %: (actual - previo) / previo. Si previo = 0, mostramos texto especial.
        $delta = null;
        if ($prevClicks > 0) {
            $delta = round((($totalClicks - $prevClicks) / $prevClicks) * 100);
        }

        // Pageviews totales del sitio (suma de views_count) + pageviews del rango via click logs
        $totalViewsAllTime = (int)$db->fetchColumn(
            "SELECT COALESCE(SUM(views_count), 0) FROM articles WHERE site_id = :s AND status = 'published'",
            ['s' => $site['id']]
        );

        // Afiliados activos
        $activeLinks = (int)$db->fetchColumn(
            'SELECT COUNT(*) FROM affiliate_links WHERE site_id = :s AND active = 1',
            ['s' => $site['id']]
        );

        $byLink = $db->fetchAll(
            "SELECT l.name, l.tracking_slug, l.network_name,
                    COUNT(c.id) AS clicks
             FROM affiliate_links l
             LEFT JOIN affiliate_clicks c ON c.affiliate_link_id = l.id
               AND c.clicked_at >= (NOW() - INTERVAL $days DAY)
             WHERE l.site_id = :s
             GROUP BY l.id
             ORDER BY clicks DESC
             LIMIT 25",
            ['s' => $site['id']]
        );

        // Top articulos: incluir CTR (clicks / views) y clicks del periodo
        $byArticle = $db->fetchAll(
            "SELECT a.id, a.title, a.slug, a.article_type, a.views_count,
                    COUNT(c.id) AS clicks
             FROM articles a
             LEFT JOIN affiliate_clicks c ON c.article_id = a.id
               AND c.clicked_at >= (NOW() - INTERVAL $days DAY)
             WHERE a.site_id = :s AND a.status = 'published'
             GROUP BY a.id
             ORDER BY clicks DESC, a.views_count DESC
             LIMIT 25",
            ['s' => $site['id']]
        );

        $byProduct = $db->fetchAll(
            "SELECT p.id, p.name, p.slug, COUNT(c.id) AS clicks
             FROM products p
             LEFT JOIN affiliate_clicks c ON c.product_id = p.id
               AND c.clicked_at >= (NOW() - INTERVAL $days DAY)
             WHERE p.site_id = :s
             GROUP BY p.id
             ORDER BY clicks DESC
             LIMIT 25",
            ['s' => $site['id']]
        );

        $byDay = $db->fetchAll(
            "SELECT DATE(c.clicked_at) AS d, COUNT(*) AS clicks
             FROM affiliate_clicks c
             JOIN affiliate_links l ON l.id = c.affiliate_link_id
             WHERE l.site_id = :s AND c.clicked_at >= (NOW() - INTERVAL $days DAY)
             GROUP BY DATE(c.clicked_at)
             ORDER BY d ASC",
            ['s' => $site['id']]
        );

        // Top paises de clicks (cuando Cloudflare CF-IPCOUNTRY esta presente)
        $byCountry = $db->fetchAll(
            "SELECT c.country, COUNT(*) AS clicks
             FROM affiliate_clicks c
             JOIN affiliate_links l ON l.id = c.affiliate_link_id
             WHERE l.site_id = :s AND c.clicked_at >= (NOW() - INTERVAL $days DAY)
               AND c.country IS NOT NULL AND c.country <> ''
             GROUP BY c.country
             ORDER BY clicks DESC
             LIMIT 10",
            ['s' => $site['id']]
        );

        $this->render('analytics', [
            'days'                => $days,
            'total_clicks'        => $totalClicks,
            'prev_clicks'         => $prevClicks,
            'delta'               => $delta,
            'total_views_alltime' => $totalViewsAllTime,
            'active_links'        => $activeLinks,
            'by_link'             => $byLink,
            'by_article'          => $byArticle,
            'by_product'          => $byProduct,
            'by_day'              => $byDay,
            'by_country'          => $byCountry,
            'page_title'          => 'Analytics',
        ]);
    }
}
