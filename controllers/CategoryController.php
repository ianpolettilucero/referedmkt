<?php
namespace Controllers;

use Core\Database;
use Models\Product;

final class CategoryController extends Controller
{
    /**
     * Catalogo global de productos (sin filtro de categoria).
     */
    public function index(): void
    {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $data = Product::catalog($this->site->id, null, $page, 24);

        $this->seo
            ->title('Catálogo de productos')
            ->description('Comparativa y analisis de productos disponibles.')
            ->canonical('/productos')
            ->breadcrumb([['Inicio', '/'], ['Productos', '/productos']]);

        $this->render('category', [
            'category'     => null,
            'products'     => $data['items'],
            'total'        => $data['total'],
            'page'         => $data['page'],
            'per_page'     => $data['per_page'],
            'all_products' => true,
        ]);
    }

    /**
     * Productos filtrados por categoria.
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

        $page = max(1, (int)($_GET['page'] ?? 1));
        $data = Product::catalog($this->site->id, (int)$cat['id'], $page, 24);

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
            'category'     => $cat,
            'products'     => $data['items'],
            'total'        => $data['total'],
            'page'         => $data['page'],
            'per_page'     => $data['per_page'],
            'all_products' => false,
        ]);
    }
}
