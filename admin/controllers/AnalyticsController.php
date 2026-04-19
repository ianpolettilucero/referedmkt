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

        $totalClicks = (int)$db->fetchColumn(
            "SELECT COUNT(*) FROM affiliate_clicks c
             JOIN affiliate_links l ON l.id = c.affiliate_link_id
             WHERE l.site_id = :s AND c.clicked_at >= (NOW() - INTERVAL $days DAY)",
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

        $byArticle = $db->fetchAll(
            "SELECT a.id, a.title, a.slug, a.article_type, COUNT(c.id) AS clicks, a.views_count
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

        $this->render('analytics', [
            'days'         => $days,
            'total_clicks' => $totalClicks,
            'by_link'      => $byLink,
            'by_article'   => $byArticle,
            'by_product'   => $byProduct,
            'by_day'       => $byDay,
            'page_title'   => 'Analytics',
        ]);
    }
}
