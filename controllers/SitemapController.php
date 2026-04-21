<?php
namespace Controllers;

use Core\Database;
use Core\Site;

/**
 * Sitemap.xml generado desde DB.
 *
 * Incluye image:image extension para que Google Image Search indexe las
 * featured_images de articles y logos de productos.
 */
final class SitemapController
{
    public function index(): void
    {
        $site = Site::current();
        $db = Database::instance();

        $base = 'https://' . $site->domain;

        $articles = $db->fetchAll(
            "SELECT slug, title, article_type, featured_image,
                    COALESCE(updated_at, published_at) AS lastmod
             FROM articles
             WHERE site_id = :site AND status = 'published'
             ORDER BY published_at DESC",
            ['site' => $site->id]
        );

        $products = $db->fetchAll(
            "SELECT slug, name, logo_url, updated_at AS lastmod
             FROM products
             WHERE site_id = :site
             ORDER BY updated_at DESC",
            ['site' => $site->id]
        );

        $categories = $db->fetchAll(
            "SELECT slug, name, featured_image, updated_at AS lastmod
             FROM categories WHERE site_id = :site
             ORDER BY updated_at DESC",
            ['site' => $site->id]
        );

        $authors = $db->fetchAll(
            "SELECT a.slug, a.avatar_url, MAX(art.updated_at) AS lastmod
             FROM authors a
             LEFT JOIN articles art
               ON art.author_id = a.id AND art.site_id = a.site_id AND art.status = 'published'
             WHERE a.site_id = :site
             GROUP BY a.id, a.slug, a.avatar_url",
            ['site' => $site->id]
        );

        header('Content-Type: application/xml; charset=utf-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
        echo '        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">' . "\n";

        $this->url($base . '/', null);
        $this->url($base . '/productos', null);
        foreach (['guias','comparativas','resenas','noticias'] as $section) {
            $this->url($base . '/' . $section, null);
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
