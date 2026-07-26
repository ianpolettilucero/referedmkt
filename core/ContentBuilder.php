<?php
namespace Core;

/**
 * Compila content/ al array que consume Content.
 *
 * Responsabilidades:
 *   1. Leer y parsear cada archivo (front-matter + cuerpo Markdown).
 *   2. Validar: campos obligatorios, enums, slugs unicos, referencias que
 *      apuntan a algo que existe.
 *   3. Asignar ids enteros deterministas (orden alfabetico de slug, 1..N).
 *      Nada externo depende de ellos — las URLs usan slug — pero el codigo
 *      y las vistas los usan para comparar y agrupar.
 *   4. Denormalizar los joins (author_name, category_slug, affiliate_slug…)
 *      y renderizar el Markdown a HTML una sola vez.
 *
 * Cualquier problema aborta el build con InvalidArgumentException: es
 * preferible que falle el CI antes que publicar contenido incoherente.
 */
final class ContentBuilder
{
    private const ARTICLE_TYPES  = ['guide', 'review', 'comparison', 'news'];
    private const ARTICLE_STATES = ['draft', 'published', 'archived'];
    private const PRICING_MODELS = ['one_time', 'monthly', 'yearly', 'free', 'custom'];

    /** Un unico tenant. site_id se mantiene en el modelo por compatibilidad. */
    private const SITE_ID = 1;

    /**
     * @return array<string, mixed>
     */
    public static function build(): array
    {
        $root = APP_ROOT . Content::CONTENT_DIR;
        if (!is_dir($root)) {
            throw new \InvalidArgumentException("No existe el directorio content/ en $root");
        }

        $site           = self::loadSite($root);
        $categories     = self::loadCategories($root);
        $authors        = self::loadAuthors($root);
        $affiliateLinks = self::loadAffiliateLinks($root);
        $products       = self::loadProducts($root, $categories, $affiliateLinks);
        $articles       = self::loadArticles($root, $categories, $authors, $products);
        $redirects      = self::loadRedirects($root);

        return [
            'site'            => $site,
            'categories'      => $categories,
            'authors'         => $authors,
            'affiliate_links' => $affiliateLinks,
            'products'        => $products,
            'articles'        => $articles,
            'redirects'       => $redirects,
        ];
    }

    // -------------------------------------------------------------------------
    // Site
    // -------------------------------------------------------------------------

    /**
     * @return array<string, mixed>
     */
    private static function loadSite(string $root): array
    {
        $path = $root . '/site.php';
        if (!is_readable($path)) {
            throw new \InvalidArgumentException('Falta content/site.php');
        }
        $cfg = require $path;
        if (!is_array($cfg)) {
            throw new \InvalidArgumentException('content/site.php debe devolver un array');
        }

        foreach (['domain', 'name', 'slug'] as $req) {
            if (empty($cfg[$req])) {
                throw new \InvalidArgumentException("content/site.php: falta '$req'");
            }
        }

        return array_merge([
            'id'                                => self::SITE_ID,
            'theme_name'                        => 'default',
            'primary_color'                     => null,
            'logo_url'                          => null,
            'favicon_url'                       => null,
            'affiliate_disclosure_text'         => null,
            'google_analytics_id'               => null,
            'google_search_console_verification' => null,
            'google_tag_manager_id'             => null,
            'google_ads_id'                     => null,
            'microsoft_clarity_id'              => null,
            'meta_pixel_id'                     => null,
            'default_language'                  => 'es',
            'default_country'                   => 'AR',
            'tagline'                           => null,
            'meta_title_template'               => null,
            'meta_description_template'         => null,
            'active'                            => 1,
            'settings'                          => [],
        ], $cfg);
    }

    // -------------------------------------------------------------------------
    // Colecciones
    // -------------------------------------------------------------------------

    /**
     * @return array<string, array<string, mixed>>
     */
    private static function loadCategories(string $root): array
    {
        $docs = self::readDir($root . '/categories');
        $out = [];
        $i = 0;
        foreach ($docs as $slug => $doc) {
            $d = $doc['data'];
            self::require($d, ['name'], "categories/$slug.md");
            $out[$slug] = [
                'id'               => ++$i,
                'site_id'          => self::SITE_ID,
                'parent_id'        => null, // resuelto abajo
                'parent'           => $d['parent'] ?? null,
                'slug'             => $slug,
                'name'             => (string)$d['name'],
                'description'      => $doc['body'] !== '' ? $doc['body'] : ($d['description'] ?? null),
                'sort_order'       => (int)($d['sort_order'] ?? 0),
                'meta_title'       => $d['meta_title'] ?? null,
                'meta_description' => $d['meta_description'] ?? null,
                'featured_image'   => $d['featured_image'] ?? null,
                'updated_at'       => self::stamp($d['updated'] ?? null, $doc['mtime']),
            ];
        }

        // parent por slug -> id
        foreach ($out as $slug => &$row) {
            if ($row['parent'] === null) { continue; }
            if (!isset($out[$row['parent']])) {
                throw new \InvalidArgumentException(
                    "categories/$slug.md: parent '{$row['parent']}' no existe"
                );
            }
            $row['parent_id'] = $out[$row['parent']]['id'];
        }
        unset($row);

        return $out;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private static function loadAuthors(string $root): array
    {
        $docs = self::readDir($root . '/authors');
        $out = [];
        $i = 0;
        foreach ($docs as $slug => $doc) {
            $d = $doc['data'];
            self::require($d, ['name'], "authors/$slug.md");
            $out[$slug] = [
                'id'           => ++$i,
                'site_id'      => self::SITE_ID,
                'slug'         => $slug,
                'name'         => (string)$d['name'],
                'bio'          => $doc['body'] !== '' ? $doc['body'] : ($d['bio'] ?? null),
                'avatar_url'   => $d['avatar_url'] ?? null,
                'social_links' => self::listOf($d['social_links'] ?? null),
                'expertise'    => $d['expertise'] ?? null,
                'updated_at'   => self::stamp($d['updated'] ?? null, $doc['mtime']),
            ];
        }
        return $out;
    }

    /**
     * @return array<string, array<string, mixed>> indexado por tracking_slug
     */
    private static function loadAffiliateLinks(string $root): array
    {
        $path = $root . '/affiliate-links.php';
        if (!is_readable($path)) {
            return [];
        }
        $rows = require $path;
        if (!is_array($rows)) {
            throw new \InvalidArgumentException('content/affiliate-links.php debe devolver un array');
        }

        $out = [];
        $i = 0;
        foreach ($rows as $slug => $r) {
            $slug = (string)$slug;
            if (empty($r['destination_url'])) {
                throw new \InvalidArgumentException("affiliate-links.php[$slug]: falta destination_url");
            }
            if (!filter_var($r['destination_url'], FILTER_VALIDATE_URL)) {
                throw new \InvalidArgumentException(
                    "affiliate-links.php[$slug]: destination_url invalida: {$r['destination_url']}"
                );
            }
            $out[$slug] = [
                'id'              => ++$i,
                'site_id'         => self::SITE_ID,
                'tracking_slug'   => $slug,
                'name'            => $r['name'] ?? $slug,
                'destination_url' => (string)$r['destination_url'],
                'network_name'    => $r['network'] ?? null,
                'active'          => array_key_exists('active', $r) ? (int)(bool)$r['active'] : 1,
            ];
        }
        return $out;
    }

    /**
     * @param array<string, array<string, mixed>> $categories
     * @param array<string, array<string, mixed>> $affiliateLinks
     * @return array<string, array<string, mixed>>
     */
    private static function loadProducts(string $root, array $categories, array $affiliateLinks): array
    {
        $docs = self::readDir($root . '/products');
        $out = [];
        $i = 0;
        foreach ($docs as $slug => $doc) {
            $d = $doc['data'];
            $where = "products/$slug.md";
            self::require($d, ['name'], $where);

            $cat = self::resolveRef($d['category'] ?? null, $categories, $where, 'category');
            $aff = self::resolveRef($d['affiliate'] ?? null, $affiliateLinks, $where, 'affiliate');

            $descriptionHtml = $doc['body'] !== '' ? Markdown::toHtml($doc['body']) : '';
            self::assertNoH1($descriptionHtml, $where);

            $pricing = (string)($d['pricing_model'] ?? 'custom');
            if (!in_array($pricing, self::PRICING_MODELS, true)) {
                throw new \InvalidArgumentException(
                    "$where: pricing_model '$pricing' invalido (validos: " . implode(', ', self::PRICING_MODELS) . ')'
                );
            }

            $out[$slug] = [
                'id'                => ++$i,
                'site_id'           => self::SITE_ID,
                'slug'              => $slug,
                'name'              => (string)$d['name'],
                'brand'             => $d['brand'] ?? null,
                'description_short' => $d['description_short'] ?? null,
                'description_long'  => $doc['body'],
                'description_html'  => $descriptionHtml,
                'logo_url'          => $d['logo_url'] ?? null,
                'rating'            => self::rating($d['rating'] ?? null, $where),
                'price_from'        => isset($d['price_from']) ? (float)$d['price_from'] : null,
                'price_currency'    => $d['price_currency'] ?? null,
                'pricing_model'     => $pricing,
                'features'          => self::listOf($d['features'] ?? null),
                'pros'              => self::listOf($d['pros'] ?? null),
                'cons'              => self::listOf($d['cons'] ?? null),
                'specs'             => is_array($d['specs'] ?? null) ? $d['specs'] : null,
                'meta_title'        => $d['meta_title'] ?? null,
                'meta_description'  => $d['meta_description'] ?? null,
                'featured'          => (int)(bool)($d['featured'] ?? false),
                'category_id'       => $cat['id'] ?? null,
                'category_slug'     => $cat['slug'] ?? null,
                'category_name'     => $cat['name'] ?? null,
                'affiliate_link_id' => $aff['id'] ?? null,
                'affiliate_slug'    => $aff['tracking_slug'] ?? null,
                'updated_at'        => self::stamp($d['updated'] ?? null, $doc['mtime']),
            ];
        }
        return $out;
    }

    /**
     * @param array<string, array<string, mixed>> $categories
     * @param array<string, array<string, mixed>> $authors
     * @param array<string, array<string, mixed>> $products
     * @return array<string, array<string, mixed>>
     */
    private static function loadArticles(string $root, array $categories, array $authors, array $products): array
    {
        $docs = self::readDir($root . '/articles');
        $out = [];
        $i = 0;
        foreach ($docs as $slug => $doc) {
            $d = $doc['data'];
            $where = "articles/$slug.md";
            self::require($d, ['title'], $where);

            $type = (string)($d['type'] ?? 'guide');
            if (!in_array($type, self::ARTICLE_TYPES, true)) {
                throw new \InvalidArgumentException(
                    "$where: type '$type' invalido (validos: " . implode(', ', self::ARTICLE_TYPES) . ')'
                );
            }

            $status = (string)($d['status'] ?? 'draft');
            if (!in_array($status, self::ARTICLE_STATES, true)) {
                throw new \InvalidArgumentException(
                    "$where: status '$status' invalido (validos: " . implode(', ', self::ARTICLE_STATES) . ')'
                );
            }

            $cat    = self::resolveRef($d['category'] ?? null, $categories, $where, 'category');
            $author = self::resolveRef($d['author'] ?? null, $authors, $where, 'author');

            // Productos relacionados por slug -> ids.
            $relatedIds = [];
            foreach (self::listOf($d['products'] ?? null) ?? [] as $pslug) {
                if (!isset($products[$pslug])) {
                    throw new \InvalidArgumentException("$where: el producto '$pslug' no existe");
                }
                $relatedIds[] = $products[$pslug]['id'];
            }

            $contentHtml = $doc['body'] !== '' ? Markdown::toHtml($doc['body']) : '';
            self::assertNoH1($contentHtml, $where);

            $publishedAt = self::stamp($d['published'] ?? null, null);
            if ($status === 'published' && $publishedAt === null) {
                throw new \InvalidArgumentException("$where: status published requiere 'published: YYYY-MM-DD'");
            }

            $out[$slug] = [
                'id'                  => ++$i,
                'site_id'             => self::SITE_ID,
                'slug'                => $slug,
                'title'               => (string)$d['title'],
                'subtitle'            => $d['subtitle'] ?? null,
                'excerpt'             => $d['excerpt'] ?? null,
                'content'             => $doc['body'],
                'content_html'        => $contentHtml,
                'featured_image'      => $d['featured_image'] ?? null,
                'article_type'        => $type,
                'related_product_ids' => $relatedIds,
                'rating'              => self::rating($d['rating'] ?? null, $where),
                'verdict'             => $d['verdict'] ?? null,
                'pros'                => self::listOf($d['pros'] ?? null),
                'cons'                => self::listOf($d['cons'] ?? null),
                'meta_title'          => $d['meta_title'] ?? null,
                'meta_description'    => $d['meta_description'] ?? null,
                'status'              => $status,
                'published_at'        => $publishedAt,
                'updated_at'          => self::stamp($d['updated'] ?? null, $doc['mtime']),
                'views_count'         => 0,
                'category_id'         => $cat['id'] ?? null,
                'category_slug'       => $cat['slug'] ?? null,
                'category_name'       => $cat['name'] ?? null,
                'author_id'           => $author['id'] ?? null,
                'author_slug'         => $author['slug'] ?? null,
                'author_name'         => $author['name'] ?? null,
                'author_avatar'       => $author['avatar_url'] ?? null,
            ];
        }
        return $out;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private static function loadRedirects(string $root): array
    {
        $path = $root . '/redirects.php';
        if (!is_readable($path)) {
            return [];
        }
        $rows = require $path;
        if (!is_array($rows)) {
            throw new \InvalidArgumentException('content/redirects.php debe devolver un array');
        }

        $out = [];
        foreach ($rows as $from => $to) {
            $code = 301;
            if (is_array($to)) {
                $code = (int)($to['status'] ?? 301);
                $to   = (string)($to['to'] ?? '');
            }
            if (!in_array($code, [301, 302, 307, 308], true)) {
                throw new \InvalidArgumentException("redirects.php[$from]: status $code invalido");
            }
            if ($to === '') {
                throw new \InvalidArgumentException("redirects.php[$from]: destino vacio");
            }
            $out[] = [
                'from_path'   => '/' . ltrim(rtrim((string)$from, '/'), '/'),
                'to_path'     => (string)$to,
                'status_code' => $code,
            ];
        }
        return $out;
    }

    // -------------------------------------------------------------------------
    // Utilidades
    // -------------------------------------------------------------------------

    /**
     * Lee todos los .md de un directorio, indexados por slug (el nombre del
     * archivo). Orden alfabetico para que los ids sean deterministas.
     *
     * @return array<string, array{data:array<string,mixed>, body:string, mtime:int}>
     */
    private static function readDir(string $dir): array
    {
        if (!is_dir($dir)) {
            return [];
        }
        $files = glob($dir . '/*.md') ?: [];
        sort($files);
        // content/_plantillas/ queda fuera: no es contenido publicable.

        $out = [];
        foreach ($files as $file) {
            $slug = basename($file, '.md');
            // El slug del archivo es la URL: exigimos que sea limpio.
            if (!preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug)) {
                throw new \InvalidArgumentException(
                    "Nombre de archivo invalido: " . basename($file)
                    . " — usa minusculas, numeros y guiones (ej. mi-articulo.md)"
                );
            }
            try {
                $parsed = FrontMatter::parse((string)file_get_contents($file));
            } catch (\InvalidArgumentException $e) {
                throw new \InvalidArgumentException(basename($dir) . "/$slug.md: " . $e->getMessage());
            }
            $out[$slug] = [
                'data'  => $parsed['data'],
                'body'  => $parsed['body'],
                'mtime' => (int)filemtime($file),
            ];
        }
        return $out;
    }

    /**
     * El cuerpo no puede traer un H1: el H1 de la pagina es el titulo, y dos
     * H1 en el mismo documento es un error de SEO.
     *
     * Se chequea sobre el HTML ya renderizado, no sobre el markdown: asi un
     * "# comentario" dentro de un bloque de codigo no cuenta, porque el parser
     * ya lo resolvio como codigo.
     */
    private static function assertNoH1(string $html, string $where): void
    {
        if (preg_match('~<h1[\s>]~i', $html)) {
            throw new \InvalidArgumentException(
                "$where: el cuerpo tiene un encabezado H1 (# Titulo). El H1 de la "
                . "pagina ya es el titulo del front-matter — usa ## para las secciones."
            );
        }
    }

    /**
     * @param array<string, mixed> $data
     * @param string[] $keys
     */
    private static function require(array $data, array $keys, string $where): void
    {
        foreach ($keys as $k) {
            if (($data[$k] ?? null) === null || $data[$k] === '') {
                throw new \InvalidArgumentException("$where: falta el campo obligatorio '$k'");
            }
        }
    }

    /**
     * Resuelve una referencia por slug contra una coleccion.
     *
     * @param array<string, array<string, mixed>> $collection
     * @return array<string, mixed>|null
     */
    private static function resolveRef($slug, array $collection, string $where, string $field): ?array
    {
        if ($slug === null || $slug === '') {
            return null;
        }
        $slug = (string)$slug;
        if (!isset($collection[$slug])) {
            throw new \InvalidArgumentException("$where: $field '$slug' no existe");
        }
        return $collection[$slug];
    }

    /**
     * Normaliza un valor a lista de strings, o null si viene vacio.
     *
     * @return array<int, string>|null
     */
    private static function listOf($value): ?array
    {
        if ($value === null || $value === '' || $value === []) {
            return null;
        }
        if (!is_array($value)) {
            $value = [$value];
        }
        $out = [];
        foreach ($value as $v) {
            $v = trim((string)$v);
            if ($v !== '') { $out[] = $v; }
        }
        return $out ?: null;
    }

    /**
     * Valida una nota 0-5.
     */
    private static function rating($value, string $where): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }
        if (!is_numeric($value)) {
            throw new \InvalidArgumentException("$where: rating '$value' no es un numero");
        }
        $r = (float)$value;
        if ($r < 0 || $r > 5) {
            throw new \InvalidArgumentException("$where: rating $r fuera del rango 0-5");
        }
        return round($r, 2);
    }

    /**
     * Normaliza una fecha del front-matter a 'Y-m-d H:i:s'. Si no hay fecha
     * declarada usa el fallback (mtime del archivo) o null.
     */
    private static function stamp($value, ?int $fallback): ?string
    {
        if ($value === null || $value === '') {
            return $fallback !== null ? date('Y-m-d H:i:s', $fallback) : null;
        }
        $ts = strtotime((string)$value);
        if ($ts === false) {
            throw new \InvalidArgumentException("Fecha invalida: '$value'");
        }
        return date('Y-m-d H:i:s', $ts);
    }
}
