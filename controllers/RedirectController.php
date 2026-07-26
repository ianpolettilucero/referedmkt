<?php
namespace Controllers;

use Core\Content;
use Core\Site;
use Models\AffiliateLink;

/**
 * Redirect a afiliado. Endpoint: GET /go/{tracking_slug}?a={articulo}&p={producto}
 *
 * Features:
 *  - Redirect con una lectura en memoria y un header: sin I/O.
 *  - UTM params automaticos: utm_source (nuestro dominio), utm_medium=affiliate,
 *    utm_campaign (slug del articulo si aplica), utm_content (slug del producto).
 *    Si el vendor ya los traia en la URL destino, se respetan los existentes.
 *  - Preview mode: /go/{slug}?preview=1 muestra la URL final sin redirigir.
 *
 * Los clicks NO se registran aca. Los cuenta la red de afiliados (Impact,
 * PartnerStack), que ademas ve las conversiones y la comision — no solo el
 * click — y GA4 por el evento de salida. Contar redirects por nuestra cuenta
 * era duplicar peor un dato que ya teniamos mejor en otro lado.
 */
final class RedirectController
{
    public function affiliate(array $params): void
    {
        $site = Site::current();
        $slug = $params['slug'] ?? '';

        $link = AffiliateLink::findActiveBySlug($site->id, $slug);

        if (!$link) {
            http_response_code(404);
            echo "404";
            return;
        }

        // Contexto para los UTM. Aceptamos los nombres cortos (a/p) y los
        // largos de la version anterior, para no romper links ya publicados.
        $articleSlug = self::resolveSlug(Content::articles(), $_GET['a'] ?? ($_GET['article_id'] ?? null));
        $productSlug = self::resolveSlug(Content::products(), $_GET['p'] ?? ($_GET['product_id'] ?? null));

        // Armar la URL final con UTMs auto
        $finalUrl = self::addUtmParams(
            (string)$link['destination_url'],
            $site->domain,
            $articleSlug,
            $productSlug
        );

        // Preview mode: muestra la URL final sin redirigir. Para verificar UTMs.
        if (($_GET['preview'] ?? '') === '1') {
            self::renderPreview($link, $finalUrl, $articleSlug, $productSlug);
            return;
        }

        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Location: ' . $finalUrl, true, 302);
    }

    /**
     * Resuelve una referencia a slug. Acepta el slug directo o un id entero
     * de la epoca en que el contexto viajaba como article_id/product_id.
     *
     * @param array<string, array<string, mixed>> $collection
     */
    private static function resolveSlug(array $collection, $ref): ?string
    {
        if ($ref === null || $ref === '') {
            return null;
        }
        $ref = (string)$ref;
        if (isset($collection[$ref])) {
            return $ref;
        }
        if (ctype_digit($ref)) {
            $row = Content::byId($collection, (int)$ref);
            return $row['slug'] ?? null;
        }
        return null;
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
