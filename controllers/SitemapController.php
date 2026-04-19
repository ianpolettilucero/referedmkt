<?php
namespace Controllers;

use Core\Database;
use Core\Site;

/**
 * Sitemap.xml generado desde DB. Stub inicial: lista articles publicados + products.
 * Se ampliara en la Fase 1 final con paginacion/segmentacion cuando >10k URLs.
 */
final class SitemapController
{
    public function index(): void
    {
        $site = Site::current();
        $db = Database::instance();

        $base = 'https://' . $site->domain;

        $articles = $db->fetchAll(
            "SELECT slug, article_type, COALESCE(updated_at, published_at) AS lastmod
             FROM articles
             WHERE site_id = :site AND status = 'published'
             ORDER BY published_at DESC",
            ['site' => $site->id]
        );

        $products = $db->fetchAll(
            "SELECT slug, updated_at AS lastmod
             FROM products
             WHERE site_id = :site
             ORDER BY updated_at DESC",
            ['site' => $site->id]
        );

        $categories = $db->fetchAll(
            "SELECT slug, updated_at AS lastmod
             FROM categories WHERE site_id = :site
             ORDER BY updated_at DESC",
            ['site' => $site->id]
        );

        $authors = $db->fetchAll(
            "SELECT a.slug, MAX(art.updated_at) AS lastmod
             FROM authors a
             LEFT JOIN articles art
               ON art.author_id = a.id AND art.site_id = a.site_id AND art.status = 'published'
             WHERE a.site_id = :site
             GROUP BY a.id, a.slug",
            ['site' => $site->id]
        );

        header('Content-Type: application/xml; charset=utf-8');
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        $this->url($base . '/', null);
        $this->url($base . '/productos', null);
        foreach (['guias','comparativas','resenas','noticias'] as $section) {
            $this->url($base . '/' . $section, null);
        }

        foreach ($categories as $c) {
            $this->url($base . '/productos/' . $c['slug'], $c['lastmod']);
        }
        foreach ($articles as $a) {
            $path = $this->articlePath($a['article_type'], $a['slug']);
            $this->url($base . $path, $a['lastmod']);
        }
        foreach ($products as $p) {
            $this->url($base . '/producto/' . $p['slug'], $p['lastmod']);
        }
        foreach ($authors as $au) {
            $this->url($base . '/autor/' . $au['slug'], $au['lastmod']);
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

    private function url(string $loc, ?string $lastmod): void
    {
        echo "  <url>\n";
        echo "    <loc>" . htmlspecialchars($loc, ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</loc>\n";
        if ($lastmod) {
            echo "    <lastmod>" . date('c', strtotime($lastmod)) . "</lastmod>\n";
        }
        echo "  </url>\n";
    }
}
