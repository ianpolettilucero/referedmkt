<?php
namespace Controllers;

use Core\Content;
use Core\Site;
use Models\Author;
use Models\Category;
use Models\Product;

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
        $base = $this->siteBase($site);

        header('Content-Type: text/plain; charset=utf-8');
        header('Cache-Control: public, max-age=1800');

        echo "# " . $site->name . "\n\n";
        if ($site->metaDescriptionTemplate) {
            echo "> " . $this->oneLine($site->metaDescriptionTemplate) . "\n\n";
        }

        // Autores (credibilidad para el LLM)
        $authors = Author::all($site->id);
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
        $cats = Category::all($site->id);
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
        $products = array_slice(Product::catalog($site->id, [], 1, 100)['items'], 0, 100);
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
            $rows = array_values(array_filter(
                Content::publishedArticles(),
                fn($a) => $a['article_type'] === $type
            ));
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

        $articles = Content::publishedArticles();

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
            echo $this->flattenDiagrams($a['content']) . "\n\n";
            echo "---\n\n";
        }

        // Resumen de productos para contexto
        $products = Product::catalog($site->id, [], 1, 1000)['items'];
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

    /**
     * Reemplaza cada bloque ```svg por una linea de texto con su aria-label.
     *
     * Este archivo lo consumen modelos de lenguaje, y hasta ahora recibian el
     * Markdown crudo: cada diagrama entraba entero, con sus coordenadas,
     * rellenos y anchos de trazo. Con diez diagramas publicados eso son cientos
     * de lineas de markup que no aportan nada y compiten por la ventana de
     * contexto del que lee.
     *
     * El aria-label no es un resumen aproximado: la convencion del sitio es que
     * describa el contenido del diagrama con sus datos, porque es lo que oye
     * quien usa lector de pantalla. Asi que aplanar a esa etiqueta conserva la
     * informacion y tira el andamiaje. Sin aria-label se deja la marca sola,
     * para que el lector sepa que ahi habia una figura.
     */
    private function flattenDiagrams(string $md): string
    {
        return preg_replace_callback(
            '/^```svg\s*\n(.*?)\n?^```[ \t]*$/ms',
            function (array $m): string {
                if (!preg_match('/\baria-label\s*=\s*"([^"]*)"/', $m[1], $lab)) {
                    return '[Diagrama]';
                }
                $texto = html_entity_decode($lab[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $texto = trim(preg_replace('/\s+/', ' ', $texto));
                return $texto === '' ? '[Diagrama]' : '[Diagrama: ' . $texto . ']';
            },
            $md
        );
    }

    /**
     * https fijo, igual que sitemap.xml y robots.txt.
     *
     * Antes se deducia del request. En produccion daba bien, pero dependia de
     * que el proxy mandara HTTPS o X-Forwarded-Proto: si algun dia deja de
     * hacerlo, estos archivos empiezan a publicar URLs http en silencio, y son
     * justo los que consumen los crawlers de LLM. El sitio fuerza HTTPS y
     * manda HSTS por .htaccess, asi que no hay caso legitimo de http.
     */
    private function siteBase(Site $site): string
    {
        return 'https://' . $site->domain;
    }

    /**
     * Aplana un texto a una sola linea de prosa.
     *
     * strip_tags() sacaba el HTML pero no el Markdown, y las descripciones de
     * categoria y producto son Markdown crudo. El resultado era que llms.txt
     * publicaba entradas como "Hostings y Cloud: ## Servicios de Hosting para
     * Empresas El **hosting** es la base…", con los almohadillas y los
     * asteriscos adentro. Se limpia antes de colapsar los espacios: despues
     * los saltos de linea ya se perdieron y un "##" de titulo queda pegado al
     * medio de la frase, donde no hay ancla para reconocerlo.
     */
    private function oneLine(string $s, ?int $maxChars = null): string
    {
        $s = strip_tags($s);

        $s = preg_replace('/^\s*#{1,6}\s+/m', '', $s);        // titulos
        $s = preg_replace('/^\s*>\s?/m', '', $s);             // citas
        $s = preg_replace('/^\s*[-*+]\s+/m', '', $s);         // listas
        $s = preg_replace('/^\s*\d+\.\s+/m', '', $s);         // listas numeradas
        $s = preg_replace('/!?\[([^\]]*)\]\([^)]*\)/', '$1', $s); // links e imagenes
        $s = preg_replace('/(\*\*\*|\*\*|\*|___|__|_)(?=\S)(.+?)(?<=\S)\1/', '$2', $s); // enfasis
        $s = preg_replace('/`([^`]*)`/', '$1', $s);           // codigo
        $s = preg_replace('/^\s*(-{3,}|\*{3,}|_{3,})\s*$/m', '', $s); // separadores

        $s = trim(preg_replace('/\s+/', ' ', $s));
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
