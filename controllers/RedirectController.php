<?php
namespace Controllers;

use Core\Database;
use Core\Site;

/**
 * Tracking de clicks + redirect a afiliado.
 * Endpoint: GET /go/{tracking_slug}?article_id=...&product_id=...
 *
 * Requisito: responder en <100ms. Todo el trabajo es 1 SELECT + 1 INSERT + 1 header.
 * El hash de IP usa APP_SALT del env para no guardar IP plana (GDPR-friendly).
 */
final class RedirectController
{
    public function affiliate(array $params): void
    {
        $site = Site::current();
        $slug = $params['slug'] ?? '';

        $link = Database::instance()->fetch(
            'SELECT id, destination_url FROM affiliate_links
             WHERE site_id = :site AND tracking_slug = :slug AND active = 1 LIMIT 1',
            ['site' => $site->id, 'slug' => $slug]
        );

        if (!$link) {
            http_response_code(404);
            echo "404";
            return;
        }

        $articleId = isset($_GET['article_id']) ? (int)$_GET['article_id'] : null;
        $productId = isset($_GET['product_id']) ? (int)$_GET['product_id'] : null;

        $ip      = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
        $ip      = trim(explode(',', $ip)[0]);
        $salt    = getenv('APP_SALT') ?: 'change-me-in-env';
        $ipHash  = $ip !== '' ? hash('sha256', $ip . '|' . $salt) : null;
        $ua      = mb_substr((string)($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 500);
        $referer = mb_substr((string)($_SERVER['HTTP_REFERER']    ?? ''), 0, 1000);
        $country = isset($_SERVER['HTTP_CF_IPCOUNTRY']) ? substr($_SERVER['HTTP_CF_IPCOUNTRY'], 0, 2) : null;

        try {
            Database::instance()->insert('affiliate_clicks', [
                'affiliate_link_id' => $link['id'],
                'article_id'        => $articleId ?: null,
                'product_id'        => $productId ?: null,
                'user_ip_hash'      => $ipHash,
                'user_agent'        => $ua ?: null,
                'referer'           => $referer ?: null,
                'country'           => $country,
            ]);
        } catch (\Throwable $e) {
            // No bloquear el redirect por un error de log.
            error_log('[referedmkt] click log failed: ' . $e->getMessage());
        }

        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Location: ' . $link['destination_url'], true, 302);
    }
}
