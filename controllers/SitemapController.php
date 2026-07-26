<?php
namespace Controllers;

use Core\Content;
use Core\Site;

/**
 * Sitemap.xml generado desde content/.
 *
 * Incluye image:image extension para que Google Image Search indexe las
 * featured_images de articles y logos de productos.
 */
final class SitemapController
{
    public function index(): void
    {
        $site = Site::current();

        $base = 'https://' . $site->domain;

        // Solo lo publicado: un borrador en el sitemap es una URL que devuelve 404.
        $articles = Content::publishedArticles();
        foreach ($articles as &$a) {
            $a['lastmod'] = $a['updated_at'] ?? $a['published_at'];
        }
        unset($a);

        $products   = array_values(Content::products());
        $categories = array_values(Content::categories());
        foreach ($products as &$p)   { $p['lastmod'] = $p['updated_at'] ?? null; }
        unset($p);
        foreach ($categories as &$c) { $c['lastmod'] = $c['updated_at'] ?? null; }
        unset($c);

        // lastmod del autor = la nota suya mas recientemente tocada.
        $authors = [];
        foreach (Content::authors() as $au) {
            $lastmod = null;
            foreach ($articles as $a) {
                if ((int)($a['author_id'] ?? 0) !== (int)$au['id']) { continue; }
                if ($lastmod === null || (string)$a['updated_at'] > $lastmod) {
                    $lastmod = (string)$a['updated_at'];
                }
            }
            $au['lastmod'] = $lastmod;
            $authors[] = $au;
        }

        header('Content-Type: application/xml; charset=utf-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
        echo '        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

        $this->url($base . '/', null);
        $this->url($base . '/productos', null);
        // Solo secciones con contenido: un listado vacio en el sitemap invita a
        // Google a rastrear una pagina sin valor.
        foreach (active_sections() as $sec) {
            $this->url($base . $sec['path'], null);
        }

        foreach ($categories as $c) {
            $this->url($base . '/productos/' . $c['slug'], $c['lastmod'],
                $c['featured_image'] ?? null, $c['name'] ?? null);
        }
        foreach ($articles as $a) {
            $path = $this->articlePath($a['article_type'], $a['slug']);
            $this->url($base . $path, $a['lastmod'],
                $a['featured_image'] ?? null, $a['title'] ?? null);
        }
        foreach ($products as $p) {
            $this->url($base . '/producto/' . $p['slug'], $p['lastmod'],
                $p['logo_url'] ?? null, $p['name'] ?? null);
        }
        foreach ($authors as $au) {
            $this->url($base . '/autor/' . $au['slug'], $au['lastmod'],
                $au['avatar_url'] ?? null, null);
        }

        echo '</urlset>';
    }

    private function articlePath(string $type, string $slug): string
    {
        return match ($type) {
            'review'     => '/resena/' . $slug,
            'comparison' => '/comparativa/' . $slug,
            'news'       => '/noticia/' . $slug,
            default      => '/guia/' . $slug,
        };
    }

    /**
     * @param string|null $imageUrl si esta presente, agrega <image:image> al url entry
     * @param string|null $imageTitle title opcional para el image entry
     */
    private function url(string $loc, ?string $lastmod, ?string $imageUrl = null, ?string $imageTitle = null): void
    {
        echo "  <url>\n";
        echo "    <loc>" . $this->xml($loc) . "</loc>\n";
        if ($lastmod) {
            echo "    <lastmod>" . date('c', strtotime($lastmod)) . "</lastmod>\n";
        }
        if ($imageUrl) {
            // Las URLs de imagen pueden ser relativas; las convertimos a absolutas si es necesario.
            $imageAbs = (strpos($imageUrl, 'http') === 0)
                ? $imageUrl
                : 'https://' . ($_SERVER['HTTP_HOST'] ?? '') . '/' . ltrim($imageUrl, '/');
            echo "    <image:image>\n";
            echo "      <image:loc>" . $this->xml($imageAbs) . "</image:loc>\n";
            if ($imageTitle) {
                echo "      <image:title>" . $this->xml($imageTitle) . "</image:title>\n";
            }
            echo "    </image:image>\n";
        }
        echo "  </url>\n";
    }

    private function xml(string $s): string
    {
        return htmlspecialchars($s, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
