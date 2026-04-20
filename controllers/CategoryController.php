<?php
namespace Controllers;

use Core\Database;
use Models\Product;

final class CategoryController extends Controller
{
    /**
     * Catalogo global de productos (sin filtro de categoria en URL;
     * pero si puede filtrarse via query string ?cat=slug).
     */
    public function index(): void
    {
        $filters = $this->extractFilters(null);
        $page = max(1, (int)($_GET['page'] ?? 1));
        $data = Product::catalog($this->site->id, $filters, $page, 24);

        $cats = Database::instance()->fetchAll(
            'SELECT id, slug, name FROM categories WHERE site_id = :s ORDER BY sort_order, name',
            ['s' => $this->site->id]
        );

        $this->seo
            ->title('Catálogo de productos')
            ->description('Comparativa y analisis de productos disponibles.')
            ->canonical('/productos')
            ->breadcrumb([['Inicio', '/'], ['Productos', '/productos']]);

        $this->render('category', [
            'category'        => null,
            'products'        => $data['items'],
            'total'           => $data['total'],
            'page'            => $data['page'],
            'per_page'        => $data['per_page'],
            'all_products'    => true,
            'filters'         => $filters,
            'sorts'           => Product::SORTS,
            'brands'          => $data['brands'],
            'categories'      => $cats,
            'selected_cat_id' => $filters['category_id'] ?? null,
        ]);
    }

    /**
     * Productos filtrados por categoria (URL /productos/{slug}) + filtros
     * adicionales via query string.
     */
    public function show(array $params): void
    {
        $slug = $params['slug'] ?? '';
        $cat = Database::instance()->fetch(
            'SELECT * FROM categories WHERE site_id = :site AND slug = :slug LIMIT 1',
            ['site' => $this->site->id, 'slug' => $slug]
        );
        if (!$cat) {
            $this->notFound("Categoría no encontrada");
            return;
        }

        $filters = $this->extractFilters((int)$cat['id']);
        $page = max(1, (int)($_GET['page'] ?? 1));
        $data = Product::catalog($this->site->id, $filters, $page, 24);

        $cats = Database::instance()->fetchAll(
            'SELECT id, slug, name FROM categories WHERE site_id = :s ORDER BY sort_order, name',
            ['s' => $this->site->id]
        );

        $this->seo
            ->title($cat['meta_title'] ?: $cat['name'])
            ->description($cat['meta_description'] ?: $cat['description'])
            ->canonical(category_url($cat))
            ->breadcrumb([
                ['Inicio', '/'],
                ['Productos', '/productos'],
                [$cat['name'], category_url($cat)],
            ]);

        $this->render('category', [
            'category'        => $cat,
            'products'        => $data['items'],
            'total'           => $data['total'],
            'page'            => $data['page'],
            'per_page'        => $data['per_page'],
            'all_products'    => false,
            'filters'         => $filters,
            'sorts'           => Product::SORTS,
            'brands'          => $data['brands'],
            'categories'      => $cats,
            'selected_cat_id' => (int)$cat['id'],
        ]);
    }

    /**
     * @param ?int $forcedCategoryId Si la URL ya trae slug de categoria, fuerza el filtro
     * @return array<string, mixed>
     */
    private function extractFilters(?int $forcedCategoryId): array
    {
        $f = [
            'category_id' => $forcedCategoryId,
            'brand'       => trim((string)($_GET['brand'] ?? '')) ?: null,
            'min_rating'  => isset($_GET['min_rating']) && $_GET['min_rating'] !== ''
                             ? (float)$_GET['min_rating'] : null,
            'max_price'   => isset($_GET['max_price']) && $_GET['max_price'] !== ''
                             ? (float)$_GET['max_price'] : null,
            'sort'        => isset($_GET['sort']) && array_key_exists($_GET['sort'], Product::SORTS)
                             ? $_GET['sort'] : 'featured',
        ];
        // Para el listado global, permitir cambiar de categoria via ?cat=slug
        if ($forcedCategoryId === null && !empty($_GET['cat'])) {
            $catRow = Database::instance()->fetch(
                'SELECT id FROM categories WHERE site_id = :s AND slug = :slug LIMIT 1',
                ['s' => $this->site->id, 'slug' => (string)$_GET['cat']]
            );
            if ($catRow) {
                $f['category_id'] = (int)$catRow['id'];
            }
        }
        return $f;
    }
}
