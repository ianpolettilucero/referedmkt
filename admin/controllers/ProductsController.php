<?php
namespace Admin\Controllers;

use Core\Database;
use Core\Flash;

final class ProductsController extends BaseController
{
    public function index(): void
    {
        $site = $this->requireSite();
        $rows = Database::instance()->fetchAll(
            'SELECT p.*, c.name AS category_name
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             WHERE p.site_id = :s
             ORDER BY p.featured DESC, p.updated_at DESC',
            ['s' => $site['id']]
        );
        $this->render('products/list', ['rows' => $rows, 'page_title' => 'Productos']);
    }

    public function create(): void
    {
        $site = $this->requireSite();
        $this->render('products/form', [
            'row'             => $this->emptyRow(),
            'is_new'          => true,
            'categories'      => $this->categoryOptions($site['id']),
            'affiliate_links' => $this->affiliateLinkOptions($site['id']),
            'page_title'      => 'Nuevo producto',
        ]);
    }

    public function store(): void
    {
        $this->requireCsrf();
        $site = $this->requireSite();
        try {
            $id = Database::instance()->insert('products', $this->collect($site['id']));
            Flash::success("Producto #$id creado.");
            $this->redirect('/admin/products/' . $id . '/edit');
            return;
        } catch (\Throwable $e) {
            Flash::error('Error al guardar: ' . $e->getMessage());
            $this->redirect('/admin/products/new');
        }
    }

    public function edit(array $params): void
    {
        $site = $this->requireSite();
        $row = Database::instance()->fetch(
            'SELECT * FROM products WHERE id = :id AND site_id = :s',
            ['id' => (int)$params['id'], 's' => $site['id']]
        );
        if (!$row) { $this->redirect('/admin/products'); return; }
        foreach (['features','pros','cons','specs'] as $col) {
            $row[$col] = is_string($row[$col] ?? null) ? (json_decode($row[$col], true) ?: null) : ($row[$col] ?? null);
        }
        $this->render('products/form', [
            'row'             => $row,
            'is_new'          => false,
            'categories'      => $this->categoryOptions($site['id']),
            'affiliate_links' => $this->affiliateLinkOptions($site['id']),
            'page_title'      => 'Editar producto: ' . $row['name'],
        ]);
    }

    public function update(array $params): void
    {
        $this->requireCsrf();
        $site = $this->requireSite();
        $id = (int)$params['id'];
        $data = $this->collect($site['id']);
        unset($data['site_id']);
        $sets = [];
        foreach (array_keys($data) as $k) { $sets[] = "`$k` = :$k"; }
        $data['id'] = $id;
        $data['s']  = $site['id'];
        try {
            Database::instance()->query(
                'UPDATE products SET ' . implode(', ', $sets) . ' WHERE id = :id AND site_id = :s',
                $data
            );
            Flash::success('Producto actualizado.');
        } catch (\Throwable $e) {
            Flash::error('Error al guardar: ' . $e->getMessage());
        }
        $this->redirect('/admin/products/' . $id . '/edit');
    }

    public function destroy(array $params): void
    {
        $this->requireCsrf();
        $site = $this->requireSite();
        Database::instance()->query(
            'DELETE FROM products WHERE id = :id AND site_id = :s',
            ['id' => (int)$params['id'], 's' => $site['id']]
        );
        Flash::success('Producto eliminado.');
        $this->redirect('/admin/products');
    }

    private function emptyRow(): array
    {
        return [
            'category_id' => null, 'affiliate_link_id' => null,
            'slug' => '', 'name' => '', 'brand' => '',
            'description_short' => '', 'description_long' => '', 'logo_url' => '',
            'rating' => null, 'price_from' => null, 'price_currency' => 'USD',
            'pricing_model' => 'custom',
            'features' => [], 'pros' => [], 'cons' => [], 'specs' => [],
            'meta_title' => '', 'meta_description' => '', 'featured' => 0,
        ];
    }

    private function collect(int $siteId): array
    {
        $name = trim((string)$this->input('name', ''));
        $slug = trim((string)$this->input('slug', ''));
        if ($slug === '') { $slug = slugify($name); }

        $specsRaw = trim((string)$this->input('specs', ''));
        $specs = null;
        if ($specsRaw !== '') {
            $decoded = json_decode($specsRaw, true);
            if (is_array($decoded)) {
                $specs = $decoded;
            } else {
                // KEY: VALUE por linea
                $specs = [];
                foreach (preg_split('/\r?\n/', $specsRaw) as $line) {
                    if (strpos($line, ':') === false) { continue; }
                    [$k, $v] = array_map('trim', explode(':', $line, 2));
                    if ($k !== '') { $specs[$k] = $v; }
                }
                $specs = $specs ?: null;
            }
        }

        $features = $this->jsonInput('features');
        $pros     = $this->jsonInput('pros');
        $cons     = $this->jsonInput('cons');

        $rating = $this->input('rating');
        $rating = ($rating === '' || $rating === null) ? null : (float)$rating;
        $price  = $this->input('price_from');
        $price  = ($price === '' || $price === null) ? null : (float)$price;

        return [
            'site_id'           => $siteId,
            'category_id'       => $this->intInput('category_id'),
            'affiliate_link_id' => $this->intInput('affiliate_link_id'),
            'slug'              => $slug,
            'name'              => $name,
            'brand'             => trim((string)$this->input('brand', '')) ?: null,
            'description_short' => trim((string)$this->input('description_short', '')) ?: null,
            'description_long'  => trim((string)$this->input('description_long', '')) ?: null,
            'logo_url'          => trim((string)$this->input('logo_url', '')) ?: null,
            'rating'            => $rating,
            'price_from'        => $price,
            'price_currency'    => strtoupper(substr(trim((string)$this->input('price_currency', 'USD')), 0, 3)) ?: null,
            'pricing_model'     => in_array($this->input('pricing_model'), ['one_time','monthly','yearly','free','custom'], true)
                                   ? $this->input('pricing_model') : 'custom',
            'features'          => $features ? json_encode($features, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null,
            'pros'              => $pros     ? json_encode($pros,     JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null,
            'cons'              => $cons     ? json_encode($cons,     JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null,
            'specs'             => $specs    ? json_encode($specs,    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null,
            'meta_title'        => trim((string)$this->input('meta_title', '')) ?: null,
            'meta_description'  => trim((string)$this->input('meta_description', '')) ?: null,
            'featured'          => $this->boolInput('featured') ? 1 : 0,
        ];
    }

    private function categoryOptions(int $siteId): array
    {
        return Database::instance()->fetchAll(
            'SELECT id, name FROM categories WHERE site_id = :s ORDER BY name',
            ['s' => $siteId]
        );
    }

    private function affiliateLinkOptions(int $siteId): array
    {
        return Database::instance()->fetchAll(
            'SELECT id, name FROM affiliate_links WHERE site_id = :s ORDER BY name',
            ['s' => $siteId]
        );
    }
}
