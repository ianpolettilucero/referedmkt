<?php
namespace Controllers;

use Core\Database;
use Core\Site;

/**
 * llms.txt (estándar propuesto en llmstxt.org).
 *
 * Le dice a los LLMs (ChatGPT, Claude, Perplexity, Gemini, etc.) qué contenido
 * tiene este sitio y cómo navegarlo, en formato Markdown estructurado.
 *
 * Dos endpoints:
 *   - GET /llms.txt       → índice resumido (links + descripciones cortas)
 *   - GET /llms-full.txt  → mismo índice + contenido completo de cada artículo
 *                           (para que el LLM lea el sitio entero en 1 request)
 */
final class LlmsController
{
    /**
     * Indice resumido: solo listado de URLs con descripciones cortas.
     */
    public function index(): void
    {
        $site = Site::current();
        $db = Database::instance();
        $base = $this->siteBase($site);

        header('Content-Type: text/plain; charset=utf-8');
        header('Cache-Control: public, max-age=1800');

        echo "# " . $site->name . "\n\n";
        if ($site->metaDescriptionTemplate) {
            echo "> " . $this->oneLine($site->metaDescriptionTemplate) . "\n\n";
        }

        // Autores (credibilidad para el LLM)
        $authors = $db->fetchAll(
            'SELECT name, slug, bio, expertise FROM authors WHERE site_id = :s ORDER BY name',
            ['s' => $site->id]
        );
        if ($authors) {
            echo "## Autores\n\n";
            foreach ($authors as $a) {
                echo "- [" . $a['name'] . "](" . $base . "/autor/" . $a['slug'] . ")";
                if (!empty($a['expertise'])) { echo " — " . $this->oneLine($a['expertise']); }
                echo "\n";
            }
            echo "\n";
        }

        // Categorias
        $cats = $db->fetchAll(
            'SELECT name, slug, description FROM categories WHERE site_id = :s ORDER BY sort_order, name',
            ['s' => $site->id]
        );
        if ($cats) {
            echo "## Categorías\n\n";
            foreach ($cats as $c) {
                echo "- [" . $c['name'] . "](" . $base . "/productos/" . $c['slug'] . ")";
                if (!empty($c['description'])) { echo ": " . $this->oneLine($c['description'], 140); }
                echo "\n";
            }
            echo "\n";
        }

        // Productos (top 100)
        $products = $db->fetchAll(
            "SELECT name, slug, brand, description_short
             FROM products WHERE site_id = :s
             ORDER BY featured DESC, rating DESC, updated_at DESC LIMIT 100",
            ['s' => $site->id]
        );
        if ($products) {
            echo "## Productos\n\n";
            foreach ($products as $p) {
                echo "- [" . $p['name'] . "](" . $base . "/producto/" . $p['slug'] . ")";
                if (!empty($p['brand'])) { echo " (" . $p['brand'] . ")"; }
                if (!empty($p['description_short'])) {
                    echo ": " . $this->oneLine($p['description_short'], 140);
                }
                echo "\n";
            }
            echo "\n";
        }

        // Articulos por tipo
        $sections = [
            'guide'      => 'Guías',
            'comparison' => 'Comparativas',
            'review'     => 'Reseñas',
            'news'       => 'Noticias',
        ];
        foreach ($sections as $type => $label) {
            $rows = $db->fetchAll(
                "SELECT title, slug, excerpt, published_at
                 FROM articles
                 WHERE site_id = :s AND article_type = :t
                   AND status = 'published' AND published_at <= NOW()
                 ORDER BY published_at DESC",
                ['s' => $site->id, 't' => $type]
            );
            if (!$rows) { continue; }
            echo "## " . $label . "\n\n";
            foreach ($rows as $r) {
                echo "- [" . $r['title'] . "](" . $base . $this->articlePath($type, $r['slug']) . ")";
                if (!empty($r['excerpt'])) { echo ": " . $this->oneLine($r['excerpt'], 180); }
                echo "\n";
            }
            echo "\n";
        }

        echo "## Optional\n\n";
        echo "- [Sitemap XML](" . $base . "/sitemap.xml)\n";
        echo "- [RSS Feed](" . $base . "/feed.xml)\n";
        echo "- [Full markdown content](" . $base . "/llms-full.txt): todos los artículos en un solo archivo para indexación completa por LLMs.\n";
    }

    /**
     * Version "full": incluye el contenido completo de cada articulo publicado.
     * Util para que un LLM (Claude, ChatGPT) procese el sitio entero en un solo
     * request sin tener que crawlear individualmente cada URL.
     */
    public function full(): void
    {
        $site = Site::current();
        $db = Database::instance();
        $base = $this->siteBase($site);

        header('Content-Type: text/plain; charset=utf-8');
        header('Cache-Control: public, max-age=1800');

        echo "# " . $site->name . " — Full content\n\n";
        if ($site->metaDescriptionTemplate) {
            echo "> " . $this->oneLine($site->metaDescriptionTemplate) . "\n\n";
        }
        echo "Este archivo agrega el contenido completo de todos los artículos publicados, en formato Markdown, para que modelos de lenguaje puedan indexar el sitio en un solo request.\n\n";
        echo "Base URL: " . $base . "\n";
        echo "Generado: " . date('c') . "\n\n";
        echo "---\n\n";

        $articles = $db->fetchAll(
            "SELECT a.*, au.name AS author_name, c.name AS category_name, c.slug AS category_slug
             FROM articles a
             LEFT JOIN authors au ON au.id = a.author_id
             LEFT JOIN categories c ON c.id = a.category_id
             WHERE a.site_id = :s
               AND a.status = 'published' AND a.published_at <= NOW()
             ORDER BY a.published_at DESC",
            ['s' => $site->id]
        );

        foreach ($articles as $a) {
            $path = $this->articlePath($a['article_type'], $a['slug']);
            echo "# " . $a['title'] . "\n\n";
            echo "- URL: " . $base . $path . "\n";
            echo "- Tipo: " . ucfirst($a['article_type']) . "\n";
            if (!empty($a['category_name'])) {
                echo "- Categoría: " . $a['category_name'] . "\n";
            }
            if (!empty($a['author_name'])) {
                echo "- Autor: " . $a['author_name'] . "\n";
            }
            if (!empty($a['published_at'])) {
                echo "- Publicado: " . date('Y-m-d', strtotime($a['published_at'])) . "\n";
            }
            if (!empty($a['updated_at'])) {
                echo "- Actualizado: " . date('Y-m-d', strtotime($a['updated_at'])) . "\n";
            }
            echo "\n";
            if (!empty($a['subtitle'])) {
                echo "**" . $a['subtitle'] . "**\n\n";
            }
            if (!empty($a['excerpt'])) {
                echo "> " . $this->oneLine($a['excerpt']) . "\n\n";
            }
            echo $a['content'] . "\n\n";
            echo "---\n\n";
        }

        // Resumen de productos para contexto
        $products = $db->fetchAll(
            "SELECT p.name, p.slug, p.brand, p.rating, p.price_from, p.price_currency, p.pricing_model,
                    p.description_short, p.description_long, c.name AS category_name
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             WHERE p.site_id = :s
             ORDER BY p.featured DESC, p.rating DESC",
            ['s' => $site->id]
        );
        if ($products) {
            echo "# Catálogo de productos\n\n";
            foreach ($products as $p) {
                echo "## " . $p['name'] . "\n\n";
                echo "- URL: " . $base . "/producto/" . $p['slug'] . "\n";
                if (!empty($p['brand'])) { echo "- Marca: " . $p['brand'] . "\n"; }
                if (!empty($p['category_name'])) { echo "- Categoría: " . $p['category_name'] . "\n"; }
                if (!empty($p['rating'])) { echo "- Rating: " . $p['rating'] . "/5\n"; }
                if (!empty($p['price_from'])) {
                    echo "- Precio: " . ($p['price_currency'] ?? 'USD') . " " . $p['price_from']
                        . " (" . $p['pricing_model'] . ")\n";
                }
                echo "\n";
                if (!empty($p['description_short'])) {
                    echo $p['description_short'] . "\n\n";
                }
                if (!empty($p['description_long'])) {
                    echo $p['description_long'] . "\n\n";
                }
                echo "---\n\n";
            }
        }
    }

    private function siteBase(Site $site): string
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https') ? 'https' : 'http';
        return $scheme . '://' . $site->domain;
    }

    private function oneLine(string $s, ?int $maxChars = null): string
    {
        $s = trim(preg_replace('/\s+/', ' ', strip_tags($s)));
        if ($maxChars && mb_strlen($s) > $maxChars) {
            $s = rtrim(mb_substr($s, 0, $maxChars - 1)) . '…';
        }
        return $s;
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
}
