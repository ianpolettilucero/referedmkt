<?php
namespace Controllers;

use Core\Database;

final class SearchController extends Controller
{
    public function index(): void
    {
        $q = trim((string)($_GET['q'] ?? ''));
        $articles = [];
        $products = [];

        if ($q !== '' && mb_strlen($q) >= 2) {
            $articles = $this->searchArticles($q);
            $products = $this->searchProducts($q);
        }

        $this->seo
            ->title('Búsqueda: ' . ($q !== '' ? $q : 'vacía'))
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
     * @return array<int, array<string, mixed>>
     */
    private function searchArticles(string $q): array
    {
        // MATCH ... AGAINST con IN NATURAL LANGUAGE MODE. Relevance score descendente.
        // El LIKE fallback cubre queries cortas donde fulltext no match por stopwords.
        $db = Database::instance();
        $sql = "SELECT id, slug, title, subtitle, excerpt, article_type, published_at, featured_image,
                       MATCH(title, excerpt) AGAINST (:q IN NATURAL LANGUAGE MODE) AS score
                FROM articles
                WHERE site_id = :s
                  AND status = 'published' AND published_at <= NOW()
                  AND (
                      MATCH(title, excerpt) AGAINST (:q IN NATURAL LANGUAGE MODE)
                      OR title LIKE :like OR excerpt LIKE :like
                  )
                ORDER BY score DESC, published_at DESC
                LIMIT 25";
        return $db->fetchAll($sql, [
            's'    => $this->site->id,
            'q'    => $q,
            'like' => '%' . $q . '%',
        ]);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function searchProducts(string $q): array
    {
        $db = Database::instance();
        $sql = "SELECT id, slug, name, brand, description_short, rating, price_from, price_currency,
                       pricing_model, logo_url,
                       MATCH(name, brand, description_short) AGAINST (:q IN NATURAL LANGUAGE MODE) AS score
                FROM products
                WHERE site_id = :s
                  AND (
                      MATCH(name, brand, description_short) AGAINST (:q IN NATURAL LANGUAGE MODE)
                      OR name LIKE :like OR brand LIKE :like
                  )
                ORDER BY score DESC, rating DESC, updated_at DESC
                LIMIT 25";
        return $db->fetchAll($sql, [
            's'    => $this->site->id,
            'q'    => $q,
            'like' => '%' . $q . '%',
        ]);
    }
}
