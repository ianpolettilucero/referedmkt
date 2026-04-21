<?php
namespace Controllers;

use Core\Database;
use Core\Security;
use Core\Site;

/**
 * Tracking de clicks + redirect a afiliado.
 * Endpoint: GET /go/{tracking_slug}?article_id=...&product_id=...
 *
 * Features:
 *  - Redirect <100ms con 1 SELECT + 1 INSERT + 1 header.
 *  - UTM params automaticos: utm_source (nuestro dominio), utm_medium=affiliate,
 *    utm_campaign (slug del articulo si aplica), utm_content (slug del producto).
 *    Si el vendor ya los tenía en la URL destino, respetamos los existentes.
 *  - Rate limit anti-abuse: >20 clicks/min desde misma IP al mismo link -> skip log.
 *  - Preview mode: /go/{slug}?preview=1 muestra info del link sin trackear.
 *  - IP hasheada con APP_SALT (GDPR-friendly).
 */
final class RedirectController
{
    private const CLICK_MAX_PER_MIN = 20;

    public function affiliate(array $params): void
    {
        $site = Site::current();
        $slug = $params['slug'] ?? '';

        $link = Database::instance()->fetch(
            'SELECT id, name, destination_url, network_name, commission_structure
             FROM affiliate_links
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

        // Resolver slugs para UTM params
        $articleSlug = null;
        $productSlug = null;
        if ($articleId) {
            $a = Database::instance()->fetch(
                'SELECT slug FROM articles WHERE id = :id AND site_id = :s LIMIT 1',
                ['id' => $articleId, 's' => $site->id]
            );
            $articleSlug = $a['slug'] ?? null;
        }
        if ($productId) {
            $p = Database::instance()->fetch(
                'SELECT slug FROM products WHERE id = :id AND site_id = :s LIMIT 1',
                ['id' => $productId, 's' => $site->id]
            );
            $productSlug = $p['slug'] ?? null;
        }

        // Armar la URL final con UTMs auto
        $finalUrl = self::addUtmParams(
            (string)$link['destination_url'],
            $site->domain,
            $articleSlug,
            $productSlug
        );

        // Preview mode: muestra info sin redirigir ni loguear. Solo para admin testing.
        if (($_GET['preview'] ?? '') === '1') {
            self::renderPreview($link, $finalUrl, $articleSlug, $productSlug);
            return;
        }

        // Tracking normal
        $ip      = Security::getClientIp();
        $salt    = getenv('APP_SALT') ?: 'change-me-in-env';
        $ipHash  = $ip !== '' && $ip !== '0.0.0.0' ? hash('sha256', $ip . '|' . $salt) : null;
        $ua      = mb_substr((string)($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 500);
        $referer = mb_substr((string)($_SERVER['HTTP_REFERER']    ?? ''), 0, 1000);
        $country = isset($_SERVER['HTTP_CF_IPCOUNTRY']) ? substr($_SERVER['HTTP_CF_IPCOUNTRY'], 0, 2) : null;

        // Rate limit anti-abuse
        $shouldLog = true;
        if ($ipHash !== null) {
            try {
                $recent = (int)Database::instance()->fetchColumn(
                    "SELECT COUNT(*) FROM affiliate_clicks
                     WHERE affiliate_link_id = :lid AND user_ip_hash = :h
                       AND clicked_at >= (NOW() - INTERVAL 1 MINUTE)",
                    ['lid' => $link['id'], 'h' => $ipHash]
                );
                if ($recent >= self::CLICK_MAX_PER_MIN) {
                    $shouldLog = false;
                    error_log(sprintf(
                        '[referedmkt][click-rate-limit] link_id=%d recent_clicks=%d',
                        $link['id'], $recent
                    ));
                }
            } catch (\Throwable $e) {
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

        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Location: ' . $finalUrl, true, 302);
    }

    /**
     * Arma la URL final agregando UTMs si no existen ya.
     * Respeta params custom que el vendor haya dado (utm_medium, etc.).
     */
    private static function addUtmParams(
        string $destUrl,
        string $siteDomain,
        ?string $articleSlug,
        ?string $productSlug
    ): string {
        $parsed = parse_url($destUrl);
        if (!$parsed || empty($parsed['host'])) {
            return $destUrl; // URL invalida, no tocar
        }
        $query = [];
        if (!empty($parsed['query'])) {
            parse_str($parsed['query'], $query);
        }

        // Solo agregamos si NO estaba ya (respeta configuracion custom del user/vendor).
        if (empty($query['utm_source']))   { $query['utm_source']   = $siteDomain; }
        if (empty($query['utm_medium']))   { $query['utm_medium']   = 'affiliate'; }
        if (empty($query['utm_campaign'])) {
            $query['utm_campaign'] = $articleSlug ?: $siteDomain;
        }
        if (empty($query['utm_content']) && $productSlug) {
            $query['utm_content'] = $productSlug;
        }

        $parsed['query'] = http_build_query($query);
        return self::unparseUrl($parsed);
    }

    /**
     * Rebuilder de URL desde parse_url output.
     */
    private static function unparseUrl(array $parts): string
    {
        $scheme   = isset($parts['scheme'])   ? $parts['scheme'] . '://' : '';
        $host     = $parts['host']            ?? '';
        $port     = isset($parts['port'])     ? ':' . $parts['port']     : '';
        $user     = $parts['user']            ?? '';
        $pass     = isset($parts['pass'])     ? ':' . $parts['pass']     : '';
        $pass     = ($user || $pass)          ? $pass . '@'              : '';
        $path     = $parts['path']            ?? '';
        $query    = isset($parts['query'])    ? '?' . $parts['query']    : '';
        $fragment = isset($parts['fragment']) ? '#' . $parts['fragment'] : '';
        return $scheme . $user . $pass . $host . $port . $path . $query . $fragment;
    }

    /**
     * Preview del redirect: muestra info sin trackear. Solo para testing manual.
     */
    private static function renderPreview(array $link, string $finalUrl, ?string $articleSlug, ?string $productSlug): void
    {
        header('Content-Type: text/html; charset=utf-8');
        header('X-Robots-Tag: noindex, nofollow');
        $e = fn($s) => htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
        echo <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<title>Preview: {$e($link['name'])}</title>
<meta name="robots" content="noindex, nofollow">
<style>
body { font-family: system-ui, sans-serif; max-width: 760px; margin: 2rem auto; padding: 0 1.5rem; color: #1a1d22; background: #fafbfc; line-height: 1.5; }
h1 { font-size: 1.4rem; }
.card { background: #fff; border: 1px solid #e2e5ea; border-radius: 8px; padding: 1.25rem; margin-bottom: 1rem; }
.row { display: grid; grid-template-columns: 180px 1fr; gap: 0.4rem 1rem; margin-bottom: 0.4rem; font-size: 0.92rem; }
.row > div:first-child { color: #6b7280; font-weight: 600; }
code { background: #f4f6fb; padding: 0.15rem 0.4rem; border-radius: 4px; font-size: 0.85em; word-break: break-all; }
.badge { display: inline-block; padding: 0.2rem 0.6rem; background: #fef3c7; color: #78350f; border-radius: 99px; font-size: 0.8rem; font-weight: 600; }
.btn { display: inline-block; padding: 0.5rem 1rem; background: #2b6cb0; color: #fff; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 0.9rem; }
.btn:hover { background: #1e4e8c; }
</style>
</head>
<body>
<h1>Preview del link <span class="badge">no trackea</span></h1>
<div class="card">
    <div class="row"><div>Nombre</div><div><strong>{$e($link['name'])}</strong></div></div>
    <div class="row"><div>Red</div><div>{$e($link['network_name'] ?? '—')}</div></div>
    <div class="row"><div>Comisión</div><div>{$e($link['commission_structure'] ?? '—')}</div></div>
    <div class="row"><div>URL original</div><div><code>{$e($link['destination_url'])}</code></div></div>
    <div class="row"><div>URL con UTMs</div><div><code>{$e($finalUrl)}</code></div></div>
    <div class="row"><div>article_slug</div><div>{$e($articleSlug ?? '—')}</div></div>
    <div class="row"><div>product_slug</div><div>{$e($productSlug ?? '—')}</div></div>
</div>
<p><a class="btn" href="{$e($finalUrl)}" target="_blank" rel="nofollow noopener">Abrir URL final →</a></p>
<p style="color:#6b7280;font-size:0.85rem">Este modo <strong>no</strong> registra el click en tus analytics. Útil para verificar que la URL con UTMs sea correcta antes de publicarla.</p>
</body>
</html>
HTML;
    }
}
