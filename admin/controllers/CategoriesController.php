<?php
namespace Admin\Controllers;

use Core\Database;
use Core\Flash;

final class CategoriesController extends BaseController
{
    public function index(): void
    {
        $site = $this->requireSite();
        $rows = Database::instance()->fetchAll(
            'SELECT c.*, (SELECT COUNT(*) FROM products p WHERE p.category_id = c.id) AS products_count
             FROM categories c
             WHERE c.site_id = :s
             ORDER BY c.sort_order, c.name',
            ['s' => $site['id']]
        );
        $this->render('categories/list', ['rows' => $rows, 'page_title' => 'Categorías']);
    }

    public function create(): void
    {
        $site = $this->requireSite();
        $this->render('categories/form', [
            'row'        => $this->emptyRow(),
            'is_new'     => true,
            'parents'    => $this->parentOptions($site['id'], null),
            'page_title' => 'Nueva categoría',
        ]);
    }

    public function store(): void
    {
        $this->requireCsrf();
        $site = $this->requireSite();
        $data = $this->collect($site['id']);

        try {
            $id = Database::instance()->insert('categories', $data);
            Flash::success("Categoría #$id creada.");
        } catch (\Throwable $e) {
            Flash::error('Error al guardar: ' . $e->getMessage());
            $this->redirect('/admin/categories/new');
            return;
        }
        $this->redirect('/admin/categories');
    }

    public function edit(array $params): void
    {
        $site = $this->requireSite();
        $row = Database::instance()->fetch(
            'SELECT * FROM categories WHERE id = :id AND site_id = :s',
            ['id' => (int)$params['id'], 's' => $site['id']]
        );
        if (!$row) { $this->redirect('/admin/categories'); return; }
        $this->render('categories/form', [
            'row'        => $row,
            'is_new'     => false,
            'parents'    => $this->parentOptions($site['id'], (int)$row['id']),
            'page_title' => 'Editar categoría: ' . $row['name'],
        ]);
    }

    public function update(array $params): void
    {
        $this->requireCsrf();
        $site = $this->requireSite();
        $id = (int)$params['id'];
        $data = $this->collect($site['id']);
        unset($data['site_id']); // no permitir mover entre sitios

        $sets = [];
        foreach (array_keys($data) as $k) { $sets[] = "`$k` = :$k"; }
        $data['id'] = $id;
        $data['s']  = $site['id'];

        try {
            Database::instance()->query(
                'UPDATE categories SET ' . implode(', ', $sets) . ' WHERE id = :id AND site_id = :s',
                $data
            );
            Flash::success('Categoría actualizada.');
        } catch (\Throwable $e) {
            Flash::error('Error al guardar: ' . $e->getMessage());
        }
        $this->redirect('/admin/categories/' . $id . '/edit');
    }

    public function destroy(array $params): void
    {
        $this->requireCsrf();
        $site = $this->requireSite();
        $id = (int)$params['id'];
        try {
            Database::instance()->query(
                'DELETE FROM categories WHERE id = :id AND site_id = :s',
                ['id' => $id, 's' => $site['id']]
            );
            Flash::success('Categoría eliminada.');
        } catch (\Throwable $e) {
            Flash::error('Error al eliminar: ' . $e->getMessage());
        }
        $this->redirect('/admin/categories');
    }

    private function emptyRow(): array
    {
        return [
            'parent_id' => null, 'slug' => '', 'name' => '', 'description' => '',
            'sort_order' => 0, 'meta_title' => '', 'meta_description' => '', 'featured_image' => '',
        ];
    }

    private function collect(int $siteId): array
    {
        $name = trim((string)$this->input('name', ''));
        $slug = trim((string)$this->input('slug', ''));
        if ($slug === '') { $slug = slugify($name); }

        return [
            'site_id'          => $siteId,
            'parent_id'        => $this->intInput('parent_id'),
            'slug'             => $slug,
            'name'             => $name,
            'description'      => trim((string)$this->input('description', '')) ?: null,
            'sort_order'       => (int)$this->input('sort_order', 0),
            'meta_title'       => trim((string)$this->input('meta_title', '')) ?: null,
            'meta_description' => trim((string)$this->input('meta_description', '')) ?: null,
            'featured_image'   => trim((string)$this->input('featured_image', '')) ?: null,
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function parentOptions(int $siteId, ?int $excludeId): array
    {
        $sql = 'SELECT id, name FROM categories WHERE site_id = :s';
        $params = ['s' => $siteId];
        if ($excludeId !== null) {
            $sql .= ' AND id <> :exclude';
            $params['exclude'] = $excludeId;
        }
        $sql .= ' ORDER BY name';
        return Database::instance()->fetchAll($sql, $params);
    }
}
