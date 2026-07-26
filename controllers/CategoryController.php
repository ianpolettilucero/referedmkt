<?php
namespace Controllers;

use Models\Category;
use Models\Product;

final class CategoryController extends Controller
{
    /**
     * Catalogo global de productos. Si no hay filtros activos, se muestra
     * agrupado por categoria (mejor SEO por H2s). Si hay filtros, grilla plana
     * paginada.
     */
    public function index(): void
    {
        $filters = $this->extractFilters(null);
        $page = max(1, (int)($_GET['page'] ?? 1));

        $hasActiveFilters = !empty($filters['category_id'])
            || !empty($filters['brand'])
            || !empty($filters['min_rating'])
            || !empty($filters['max_price'])
            || (($filters['sort'] ?? 'featured') !== 'featured')
            || $page > 1;

        $cats = Category::all($this->site->id);

        $this->seo
            ->title('Catálogo de productos')
            ->description('Comparativa y analisis de productos disponibles.')
            ->canonical('/productos')
            ->breadcrumb([['Inicio', '/'], ['Productos', '/productos']]);

        // Si hay filtros activos: noindex follow para evitar duplicate content
        // (canonical sigue apuntando a /productos sin params).
        if ($hasActiveFilters) {
            $this->seo->noindex(true);
        }

        if (!$hasActiveFilters) {
            // Vista agrupada por categoria con H2s
            $grouped = Product::groupedByCategory($this->site->id, 8);
            $totalProducts = count(\Core\Content::products());

            $this->render('category', [
                'category'        => null,
                'all_products'    => true,
                'view_mode'       => 'grouped',
                'grouped'         => $grouped,
                'total'           => $totalProducts,
                'filters'         => $filters,
                'sorts'           => Product::SORTS,
                'brands'          => Product::brands($this->site->id),
                'categories'      => $cats,
                'selected_cat_id' => null,
                'products'        => [],
                'page'            => 1,
                'per_page'        => 0,
            ]);
            return;
        }

        // Vista flat (con filtros o paginando)
        $data = Product::catalog($this->site->id, $filters, $page, 24);

        $this->render('category', [
            'category'        => null,
            'all_products'    => true,
            'view_mode'       => 'flat',
            'products'        => $data['items'],
            'total'           => $data['total'],
            'page'            => $data['page'],
            'per_page'        => $data['per_page'],
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
        $cat = Category::findBySlug($this->site->id, $slug);
        if (!$cat) {
            $this->notFound("Categoría no encontrada");
            return;
        }

        $filters = $this->extractFilters((int)$cat['id']);
        $page = max(1, (int)($_GET['page'] ?? 1));
        $data = Product::catalog($this->site->id, $filters, $page, 24);

        $cats = Category::all($this->site->id);

        // Filtros que NO sean la categoria base (que viene fija de la URL)
        // hacen que esta variante se considere parametrizada -> noindex follow.
        $hasExtraFilters = !empty($filters['brand'])
            || !empty($filters['min_rating'])
            || !empty($filters['max_price'])
            || (($filters['sort'] ?? 'featured') !== 'featured')
            || $page > 1;

        $this->seo
            ->title($cat['meta_title'] ?: $cat['name'])
            ->description($cat['meta_description'] ?: $cat['description'])
            ->canonical(category_url($cat))
            ->breadcrumb([
                ['Inicio', '/'],
                ['Productos', '/productos'],
                [$cat['name'], category_url($cat)],
            ]);

        if ($hasExtraFilters) {
            $this->seo->noindex(true);
        }

        $this->render('category', [
            'category'        => $cat,
            'view_mode'       => 'flat',
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
            $catRow = Category::findBySlug($this->site->id, (string)$_GET['cat']);
            if ($catRow) {
                $f['category_id'] = (int)$catRow['id'];
            }
        }
        return $f;
    }
}
