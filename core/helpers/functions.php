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
     *
     * El contexto viaja como slug (a=articulo, p=producto) y solo alimenta los
     * UTM del destino, para saber desde donde salio el click en el reporte de
     * la red de afiliados.
     */
    function affiliate_url(string $trackingSlug, ?string $articleSlug = null, ?string $productSlug = null): string
    {
        $url = '/go/' . rawurlencode($trackingSlug);
        $qs = [];
        if ($articleSlug) { $qs['a'] = $articleSlug; }
        if ($productSlug) { $qs['p'] = $productSlug; }
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

if (!function_exists('reading_time')) {
    /**
     * Calcula el tiempo de lectura estimado en minutos.
     * Asume ~225 palabras/minuto (promedio adulto en español).
     * Min 1 min para textos cortos.
     */
    function reading_time(?string $text, int $wordsPerMin = 225): int
    {
        if ($text === null || $text === '') { return 1; }
        $clean = trim(preg_replace('/\s+/', ' ', strip_tags($text)));
        $words = $clean === '' ? 0 : str_word_count($clean, 0, 'áéíóúüñÁÉÍÓÚÜÑ');
        return max(1, (int)round($words / $wordsPerMin));
    }
}

if (!function_exists('active_sections')) {
    /**
     * Secciones que tienen al menos un articulo publicado.
     *
     * El menu y el sitemap la usan para no exponer secciones vacias: una
     * pagina de listado sin items es contenido delgado y, si ademas nadie la
     * enlaza, queda huerfana. Cuando se publica el primer articulo de un tipo,
     * la seccion aparece sola.
     *
     * @return array<string, array{path:string, label:string}> indexado por tipo
     */
    function active_sections(): array
    {
        static $cache = null;
        if ($cache !== null) { return $cache; }

        $defs = [
            'guide'      => ['path' => '/guias',         'label' => 'Guías'],
            'comparison' => ['path' => '/comparativas',  'label' => 'Comparativas'],
            'review'     => ['path' => '/resenas',       'label' => 'Reseñas'],
            'news'       => ['path' => '/noticias',      'label' => 'Noticias'],
        ];

        $conContenido = [];
        foreach (\Core\Content::publishedArticles() as $a) {
            $conContenido[$a['article_type']] = true;
        }

        $cache = array_intersect_key($defs, $conContenido);
        return $cache;
    }
}
