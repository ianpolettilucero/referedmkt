<?php
namespace Controllers;

use Core\Database;

/**
 * Busqueda full-site de articulos y productos.
 *
 * Estrategia: LIKE plano con scoring en SQL. Sin MATCH AGAINST porque
 * requiere indices FULLTEXT que no siempre estan presentes y arman 500
 * silenciosos. Para sites <10k items LIKE escala bien.
 *
 * Sanitizacion:
 *   - Cap de 100 chars
 *   - Strip de control chars (\x00-\x1F, \x7F)
 *   - Escape de wildcards LIKE (% y _)
 *   - Validacion de longitud minima (2 chars)
 *   - Output siempre escapado en el view (e()/htmlspecialchars)
 */
final class SearchController extends Controller
{
    private const MAX_LEN = 100;
    private const MIN_LEN = 2;
    private const RESULTS_LIMIT = 25;

    public function index(): void
    {
        $rawQ = (string)($_GET['q'] ?? '');
        $q = $this->sanitize($rawQ);

        $articles = [];
        $products = [];
        if ($q !== '' && mb_strlen($q) >= self::MIN_LEN) {
            $articles = $this->searchArticles($q);
            $products = $this->searchProducts($q);
        }

        $this->seo
            ->title($q !== '' ? 'Búsqueda: ' . $q : 'Búsqueda')
            ->description('Resultados de búsqueda en ' . $this->site->name)
            ->canonical('/buscar')
            ->noindex()
            ->breadcrumb([['Inicio', '/'], ['Búsqueda', '/buscar']]);

        $this->render('search', [
            'q'        => $q,
            'articles' => $articles,
            'products' => $products,
            'total'    => count($articles) + count($products),
        ]);
    }

    /**
     * Sanitiza el query: trim, cap, strip de controles. Devuelve string vacio
     * si queda sin contenido valido despues de limpiar.
     */
    private function sanitize(string $q): string
    {
        // Strip de control chars (newline, tab, NUL, etc.). Conservamos espacios.
        $q = preg_replace('/[\x00-\x1F\x7F]+/u', ' ', $q) ?? '';
        $q = trim($q);
        // Cap longitud (proteccion DoS y limite practico)
        if (mb_strlen($q) > self::MAX_LEN) {
            $q = mb_substr($q, 0, self::MAX_LEN);
        }
        return $q;
    }

    /**
     * Convierte el query a un patron LIKE escapando wildcards. El usuario
     * escribiendo "%" no matchea TODO, solo el "%" literal.
     */
    private function likePattern(string $q): string
    {
        // Orden importa: backslash primero para no doble-escapar
        $escaped = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $q);
        return '%' . $escaped . '%';
    }

    /**
     * Busca en title, subtitle, excerpt y content (markdown). Score por
     * donde matchea: title=10, subtitle=6, excerpt=4, content=1.
     *
     * @return array<int, array<string, mixed>>
     */
    private function searchArticles(string $q): array
    {
        $pat = $this->likePattern($q);
        // PDO con ATTR_EMULATE_PREPARES=false no permite reusar placeholders →
        // todos unicos aunque el valor sea el mismo.
        $sql = "SELECT a.id, a.slug, a.title, a.subtitle, a.excerpt, a.article_type,
                       a.published_at, a.featured_image,
                       ( (CASE WHEN a.title    LIKE :p1 THEN 10 ELSE 0 END)
                       + (CASE WHEN a.subtitle LIKE :p2 THEN 6  ELSE 0 END)
                       + (CASE WHEN a.excerpt  LIKE :p3 THEN 4  ELSE 0 END)
                       + (CASE WHEN a.content  LIKE :p4 THEN 1  ELSE 0 END) ) AS score
                FROM articles a
                WHERE a.site_id = :site
                  AND a.status = 'published'
                  AND a.published_at <= NOW()
                  AND ( a.title    LIKE :p5
                     OR a.subtitle LIKE :p6
                     OR a.excerpt  LIKE :p7
                     OR a.content  LIKE :p8 )
                ORDER BY score DESC, a.published_at DESC
                LIMIT " . self::RESULTS_LIMIT;
        return Database::instance()->fetchAll($sql, [
            'site' => $this->site->id,
            'p1' => $pat, 'p2' => $pat, 'p3' => $pat, 'p4' => $pat,
            'p5' => $pat, 'p6' => $pat, 'p7' => $pat, 'p8' => $pat,
        ]);
    }

    /**
     * Busca en name, brand, description_short y description_long.
     * Score: name=10, brand=6, description_short=4, description_long=1.
     *
     * @return array<int, array<string, mixed>>
     */
    private function searchProducts(string $q): array
    {
        $pat = $this->likePattern($q);
        $sql = "SELECT p.id, p.slug, p.name, p.brand, p.description_short,
                       p.rating, p.price_from, p.price_currency, p.pricing_model, p.logo_url,
                       ( (CASE WHEN p.name              LIKE :p1 THEN 10 ELSE 0 END)
                       + (CASE WHEN p.brand             LIKE :p2 THEN 6  ELSE 0 END)
                       + (CASE WHEN p.description_short LIKE :p3 THEN 4  ELSE 0 END)
                       + (CASE WHEN p.description_long  LIKE :p4 THEN 1  ELSE 0 END) ) AS score
                FROM products p
                WHERE p.site_id = :site
                  AND ( p.name              LIKE :p5
                     OR p.brand             LIKE :p6
                     OR p.description_short LIKE :p7
                     OR p.description_long  LIKE :p8 )
                ORDER BY score DESC, p.rating DESC, p.updated_at DESC
                LIMIT " . self::RESULTS_LIMIT;
        return Database::instance()->fetchAll($sql, [
            'site' => $this->site->id,
            'p1' => $pat, 'p2' => $pat, 'p3' => $pat, 'p4' => $pat,
            'p5' => $pat, 'p6' => $pat, 'p7' => $pat, 'p8' => $pat,
        ]);
    }
}
