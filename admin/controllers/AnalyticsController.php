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

        // "Usuarios únicos" (proxy): IPs hasheadas distintas con al menos un click.
        // NO es pageviews (no trackeamos IP en pageviews, solo en clicks de afiliado).
        // Util como señal de conversion, no de trafico total.
        $uniqueClickers = (int)$db->fetchColumn(
            "SELECT COUNT(DISTINCT c.user_ip_hash) FROM affiliate_clicks c
             JOIN affiliate_links l ON l.id = c.affiliate_link_id
             WHERE l.site_id = :s AND c.clicked_at >= (NOW() - INTERVAL $days DAY)
               AND c.user_ip_hash IS NOT NULL",
            ['s' => $site['id']]
        );

        // "Usuarios que volvieron": IPs que clickearon en este período Y en el anterior.
        $returningClickers = (int)$db->fetchColumn(
            "SELECT COUNT(DISTINCT c.user_ip_hash) FROM affiliate_clicks c
             JOIN affiliate_links l ON l.id = c.affiliate_link_id
             WHERE l.site_id = :s
               AND c.clicked_at >= (NOW() - INTERVAL $days DAY)
               AND c.user_ip_hash IS NOT NULL
               AND c.user_ip_hash IN (
                   SELECT DISTINCT c2.user_ip_hash FROM affiliate_clicks c2
                   JOIN affiliate_links l2 ON l2.id = c2.affiliate_link_id
                   WHERE l2.site_id = :s2
                     AND c2.clicked_at >= (NOW() - INTERVAL $prevDays DAY)
                     AND c2.clicked_at <  (NOW() - INTERVAL $days DAY)
                     AND c2.user_ip_hash IS NOT NULL
               )",
            ['s' => $site['id'], 's2' => $site['id']]
        );

        // Top referrers de los clicks (de donde venian los usuarios cuando clickearon afiliado)
        $byReferer = $db->fetchAll(
            "SELECT
                CASE
                    WHEN c.referer IS NULL OR c.referer = '' THEN '(directo)'
                    ELSE SUBSTRING_INDEX(SUBSTRING_INDEX(c.referer, '/', 3), '://', -1)
                END AS source,
                COUNT(*) AS clicks
             FROM affiliate_clicks c
             JOIN affiliate_links l ON l.id = c.affiliate_link_id
             WHERE l.site_id = :s AND c.clicked_at >= (NOW() - INTERVAL $days DAY)
             GROUP BY source
             ORDER BY clicks DESC
             LIMIT 15",
            ['s' => $site['id']]
        );

        // Pageviews por día (sumados desde article_views_daily si existe la tabla)
        $pageviewsByDay = [];
        $totalPageviewsPeriod = 0;
        try {
            $pageviewsByDay = $db->fetchAll(
                "SELECT avd.day AS d, SUM(avd.views) AS views
                 FROM article_views_daily avd
                 JOIN articles a ON a.id = avd.article_id
                 WHERE a.site_id = :s AND avd.day >= (CURDATE() - INTERVAL $days DAY)
                 GROUP BY avd.day
                 ORDER BY avd.day ASC",
                ['s' => $site['id']]
            );
            foreach ($pageviewsByDay as $pv) {
                $totalPageviewsPeriod += (int)$pv['views'];
            }
        } catch (\Throwable $e) {
            // Tabla no existe todavia (migracion 006 no aplicada). Silencioso.
        }

        $this->render('analytics', [
            'days'                   => $days,
            'total_clicks'           => $totalClicks,
            'prev_clicks'            => $prevClicks,
            'delta'                  => $delta,
            'total_views_alltime'    => $totalViewsAllTime,
            'total_pageviews_period' => $totalPageviewsPeriod,
            'active_links'           => $activeLinks,
            'unique_clickers'        => $uniqueClickers,
            'returning_clickers'     => $returningClickers,
            'by_link'                => $byLink,
            'by_article'             => $byArticle,
            'by_product'             => $byProduct,
            'by_day'                 => $byDay,
            'by_country'             => $byCountry,
            'by_referer'             => $byReferer,
            'pageviews_by_day'       => $pageviewsByDay,
            'site_name'              => $site['name'],
            'site_domain'            => $site['domain'],
            'page_title'             => 'Analytics',
        ]);
    }
}
