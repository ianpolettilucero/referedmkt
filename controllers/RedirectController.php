<?php
namespace Controllers;

use Core\Database;
use Core\Security;
use Core\Site;

/**
 * Tracking de clicks + redirect a afiliado.
 * Endpoint: GET /go/{tracking_slug}?article_id=...&product_id=...
 *
 * Requisito: responder en <100ms. Todo el trabajo es 1 SELECT + 1 INSERT + 1 header.
 * El hash de IP usa APP_SALT del env para no guardar IP plana (GDPR-friendly).
 *
 * Rate limit anti-abuse: si la misma IP hasheada genera >CLICK_MAX_PER_MIN
 * clicks al mismo affiliate_link_id en 1 minuto, seguimos redirigiendo pero
 * NO registramos el click (para no falsear stats / no gastar budget del
 * vendor con clicks fraudulentos). Asi un scraper o un user con mal mouse
 * no rompe nuestras metricas.
 */
final class RedirectController
{
    private const CLICK_MAX_PER_MIN = 20;

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

        $ip      = Security::getClientIp();
        $salt    = getenv('APP_SALT') ?: 'change-me-in-env';
        $ipHash  = $ip !== '' && $ip !== '0.0.0.0' ? hash('sha256', $ip . '|' . $salt) : null;
        $ua      = mb_substr((string)($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 500);
        $referer = mb_substr((string)($_SERVER['HTTP_REFERER']    ?? ''), 0, 1000);
        $country = isset($_SERVER['HTTP_CF_IPCOUNTRY']) ? substr($_SERVER['HTTP_CF_IPCOUNTRY'], 0, 2) : null;

        // Rate limit anti-abuse: contar clicks del mismo ip_hash a este link
        // en el ultimo minuto. Si >= CLICK_MAX_PER_MIN, skip logging.
        $shouldLog = true;
        if ($ipHash !== null) {
            try {
                $recent = (int)Database::instance()->fetchColumn(
                    "SELECT COUNT(*) FROM affiliate_clicks
                     WHERE affiliate_link_id = :lid
                       AND user_ip_hash = :h
                       AND clicked_at >= (NOW() - INTERVAL 1 MINUTE)",
                    ['lid' => $link['id'], 'h' => $ipHash]
                );
                if ($recent >= self::CLICK_MAX_PER_MIN) {
                    $shouldLog = false;
                    // Log suave al error log (sin PII) para detectar abuso.
                    error_log(sprintf(
                        '[referedmkt][click-rate-limit] link_id=%d recent_clicks=%d (same hashed IP)',
                        $link['id'], $recent
                    ));
                }
            } catch (\Throwable $e) {
                // Si el count falla, seguimos y loggeamos normal.
                error_log('[referedmkt] click rate-check failed: ' . $e->getMessage());
            }
        }

        if ($shouldLog) {
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
                error_log('[referedmkt] click log failed: ' . $e->getMessage());
            }
        }

        // El redirect se hace SIEMPRE, con o sin logging (no romper UX).
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Location: ' . $link['destination_url'], true, 302);
    }
}
