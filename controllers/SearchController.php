<?php
namespace Controllers;

use Core\Content;

/**
 * Busqueda full-site de articulos y productos.
 *
 * Estrategia: scan en memoria sobre el contenido ya compilado, con el mismo
 * scoring por campo que tenia la version SQL. A esta escala (cientos de
 * documentos) recorrer el array cuesta menos de lo que costaba abrir la
 * conexion a MySQL, y desaparecen los wildcards de LIKE y su escaping.
 *
 * Sanitizacion:
 *   - Strip de control chars (\x00-\x1F, \x7F)
 *   - Cap de 100 chars
 *   - Longitud minima de 2 chars
 *   - Output siempre escapado en el view (e()/htmlspecialchars)
 */
final class SearchController extends Controller
{
    private const MAX_LEN = 100;
    private const MIN_LEN = 2;
    private const RESULTS_LIMIT = 25;

    /** Peso de cada campo en el score. */
    private const ARTICLE_WEIGHTS = ['title' => 10, 'subtitle' => 6, 'excerpt' => 4, 'content' => 1];
    private const PRODUCT_WEIGHTS = ['name' => 10, 'brand' => 6, 'description_short' => 4, 'description_long' => 1];

    public function index(): void
    {
        $q = $this->sanitize((string)($_GET['q'] ?? ''));

        $articles = [];
        $products = [];
        if ($q !== '' && mb_strlen($q) >= self::MIN_LEN) {
            $articles = $this->search(Content::publishedArticles(), self::ARTICLE_WEIGHTS, $q, 'published_at');
            $products = $this->search(array_values(Content::products()), self::PRODUCT_WEIGHTS, $q, 'rating');
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
     * Sanitiza el query: strip de controles, trim y cap de longitud.
     */
    private function sanitize(string $q): string
    {
        $q = preg_replace('/[\x00-\x1F\x7F]+/u', ' ', $q) ?? '';
        $q = trim($q);
        if (mb_strlen($q) > self::MAX_LEN) {
            $q = mb_substr($q, 0, self::MAX_LEN);
        }
        return $q;
    }

    /**
     * Suma el peso de cada campo donde aparece el termino. Desempata por
     * $tieBreak descendente (fecha en articulos, rating en productos).
     *
     * @param array<int, array<string, mixed>> $rows
     * @param array<string, int> $weights
     * @return array<int, array<string, mixed>>
     */
    private function search(array $rows, array $weights, string $q, string $tieBreak): array
    {
        $needle = mb_strtolower($q);

        $hits = [];
        foreach ($rows as $row) {
            $score = 0;
            foreach ($weights as $field => $weight) {
                $value = $row[$field] ?? null;
                if ($value === null || $value === '') { continue; }
                if (mb_strpos(mb_strtolower((string)$value), $needle) !== false) {
                    $score += $weight;
                }
            }
            if ($score > 0) {
                $row['score'] = $score;
                $hits[] = $row;
            }
        }

        usort($hits, static function (array $a, array $b) use ($tieBreak): int {
            if ($a['score'] !== $b['score']) {
                return $b['score'] <=> $a['score'];
            }
            return strcmp((string)($b[$tieBreak] ?? ''), (string)($a[$tieBreak] ?? ''));
        });

        return array_slice($hits, 0, self::RESULTS_LIMIT);
    }
}
