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
        // Una categoria muestra los productos y las notas que tiene adentro,
        // asi que se mueve cuando se mueve cualquiera de ellos, no solo cuando
        // se edita su propio .md. Ademas hay categorias sin updated_at propio y
        // quedaban sin lastmod, que es la senal que Google usa para decidir si
        // vale la pena volver.
        foreach ($categories as &$c) {
            $stamps = [];
            if (!empty($c['updated_at'])) { $stamps[] = (string)$c['updated_at']; }
            foreach ($products as $p) {
                if ((int)($p['category_id'] ?? 0) === (int)$c['id'] && !empty($p['updated_at'])) {
                    $stamps[] = (string)$p['updated_at'];
                }
            }
            foreach ($articles as $a) {
                if ((int)($a['category_id'] ?? 0) === (int)$c['id'] && !empty($a['lastmod'])) {
                    $stamps[] = (string)$a['lastmod'];
                }
            }
            $c['lastmod'] = $stamps ? max($stamps) : null;
        }
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

        // La home y los listados eran las unicas cinco URLs del sitemap sin
        // lastmod, y son justamente las que cambian cada vez que se publica
        // algo: su contenido es la lista de lo ultimo. Sin lastmod, Google no
        // tiene senal de que valga la pena volver a rastrearlas, que es lo
        // contrario de lo que se quiere en un sitio que publica seguido.
        // Cada listado hereda la fecha de la nota mas reciente que muestra.
        $newestOverall = null;
        $newestByType  = [];
        foreach ($articles as $a) {
            $stamp = (string)$a['lastmod'];
            if ($stamp === '') { continue; }
            if ($newestOverall === null || $stamp > $newestOverall) { $newestOverall = $stamp; }
            $t = $a['article_type'] ?? '';
            if (!isset($newestByType[$t]) || $stamp > $newestByType[$t]) { $newestByType[$t] = $stamp; }
        }

        // El listado de productos se mueve cuando se toca una ficha, no una nota.
        $newestProduct = null;
        foreach ($products as $p) {
            $stamp = (string)($p['lastmod'] ?? '');
            if ($stamp !== '' && ($newestProduct === null || $stamp > $newestProduct)) {
                $newestProduct = $stamp;
            }
        }

        $this->url($base . '/', $newestOverall);
        $this->url($base . '/productos', $newestProduct);
        // Solo secciones con contenido: un listado vacio en el sitemap invita a
        // Google a rastrear una pagina sin valor.
        foreach (active_sections() as $type => $sec) {
            $this->url($base . $sec['path'], $newestByType[$type] ?? $newestOverall);
        }

        // Mismo criterio que arriba con las secciones: una categoria sin un
        // solo producto ni articulo es un listado vacio, y meterla en el
        // sitemap es pedirle a Google que gaste rastreo en una pagina sin nada.
        // El lastmod nulo es justamente la senal de que no tiene contenido: se
        // calcula arriba a partir de lo que la categoria lista.
        foreach ($categories as $c) {
            if ($c['lastmod'] === null) { continue; }
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
