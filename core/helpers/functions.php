<?php
/**
 * Helpers globales. Incluido por bootstrap.
 */

if (!function_exists('e')) {
    /**
     * Escape HTML para output seguro. Llamar SIEMPRE sobre cualquier valor
     * dinamico que se vuelque en un template.
     */
    function e($value): string
    {
        if ($value === null || $value === false) {
            return '';
        }
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('site_url')) {
    /**
     * Genera una URL absoluta bajo el dominio del tenant actual.
     */
    function site_url(string $path = '/'): string
    {
        $site = \Core\Site::current();
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https')
            ? 'https' : 'http';
        return $scheme . '://' . $site->domain . '/' . ltrim($path, '/');
    }
}

if (!function_exists('theme_asset')) {
    /**
     * URL publica a un asset del tema activo.
     *
     * Los assets (CSS, JS, imagenes) viven en public/theme-assets/{theme}/...
     * para que Apache los sirva directo (no dependen del docroot) mientras que
     * los templates PHP (layouts/partials/views) siguen fuera del docroot.
     */
    function theme_asset(string $path): string
    {
        $site = \Core\Site::current();
        return '/theme-assets/' . $site->themeName . '/' . ltrim($path, '/');
    }
}

if (!function_exists('product_url')) {
    function product_url(array $product): string
    {
        return '/producto/' . $product['slug'];
    }
}

if (!function_exists('article_url')) {
    function article_url(array $article): string
    {
        $type = $article['article_type'] ?? 'guide';
        $prefix = match ($type) {
            'review'     => '/resena/',
            'comparison' => '/comparativa/',
            'news'       => '/noticia/',
            default      => '/guia/',
        };
        return $prefix . $article['slug'];
    }
}

if (!function_exists('category_url')) {
    function category_url(array $cat): string
    {
        return '/productos/' . $cat['slug'];
    }
}

if (!function_exists('affiliate_url')) {
    /**
     * URL de tracking /go/{slug} con contexto opcional.
     */
    function affiliate_url(string $trackingSlug, ?int $articleId = null, ?int $productId = null): string
    {
        $url = '/go/' . rawurlencode($trackingSlug);
        $qs = [];
        if ($articleId) { $qs['article_id'] = $articleId; }
        if ($productId) { $qs['product_id'] = $productId; }
        return $qs ? $url . '?' . http_build_query($qs) : $url;
    }
}

if (!function_exists('format_price')) {
    function format_price(?float $price, ?string $currency, string $pricingModel = 'custom'): string
    {
        if ($pricingModel === 'free') {
            return 'Gratis';
        }
        if ($pricingModel === 'custom' || $price === null) {
            return 'Consultar';
        }
        $suffix = match ($pricingModel) {
            'monthly' => ' / mes',
            'yearly'  => ' / año',
            default   => '',
        };
        $num = number_format($price, 2, ',', '.');
        return trim(($currency ? $currency . ' ' : '') . $num . $suffix);
    }
}

if (!function_exists('excerpt')) {
    function excerpt(?string $text, int $maxChars = 160): string
    {
        if ($text === null) { return ''; }
        $text = trim(preg_replace('/\s+/', ' ', strip_tags($text)));
        if (mb_strlen($text) <= $maxChars) { return $text; }
        return rtrim(mb_substr($text, 0, $maxChars - 1)) . '…';
    }
}
