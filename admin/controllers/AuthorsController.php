<?php
namespace Admin\Controllers;

use Core\Database;
use Core\Flash;

final class AuthorsController extends BaseController
{
    public function index(): void
    {
        $site = $this->requireSite();
        $rows = Database::instance()->fetchAll(
            'SELECT * FROM authors WHERE site_id = :s ORDER BY name',
            ['s' => $site['id']]
        );
        $this->render('authors/list', ['rows' => $rows, 'page_title' => 'Autores']);
    }

    public function create(): void
    {
        $this->requireSite();
        $this->render('authors/form', [
            'row'        => ['name' => '', 'slug' => '', 'bio' => '', 'avatar_url' => '', 'expertise' => '', 'social_links' => []],
            'is_new'     => true,
            'page_title' => 'Nuevo autor',
        ]);
    }

    public function store(): void
    {
        $this->requireCsrf();
        $site = $this->requireSite();
        try {
            $id = Database::instance()->insert('authors', $this->collect($site['id']));
            Flash::success("Autor #$id creado.");
        } catch (\Throwable $e) {
            Flash::error('Error al guardar: ' . $e->getMessage());
            $this->redirect('/admin/authors/new');
            return;
        }
        $this->redirect('/admin/authors');
    }

    public function edit(array $params): void
    {
        $site = $this->requireSite();
        $row = Database::instance()->fetch(
            'SELECT * FROM authors WHERE id = :id AND site_id = :s',
            ['id' => (int)$params['id'], 's' => $site['id']]
        );
        if (!$row) { $this->redirect('/admin/authors'); return; }
        $row['social_links'] = is_string($row['social_links']) ? (json_decode($row['social_links'], true) ?: []) : [];
        $this->render('authors/form', [
            'row'        => $row,
            'is_new'     => false,
            'page_title' => 'Editar autor: ' . $row['name'],
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
                'UPDATE authors SET ' . implode(', ', $sets) . ' WHERE id = :id AND site_id = :s',
                $data
            );
            Flash::success('Autor actualizado.');
        } catch (\Throwable $e) {
            Flash::error('Error al guardar: ' . $e->getMessage());
        }
        $this->redirect('/admin/authors/' . $id . '/edit');
    }

    public function destroy(array $params): void
    {
        $this->requireCsrf();
        $site = $this->requireSite();
        Database::instance()->query(
            'DELETE FROM authors WHERE id = :id AND site_id = :s',
            ['id' => (int)$params['id'], 's' => $site['id']]
        );
        Flash::success('Autor eliminado.');
        $this->redirect('/admin/authors');
    }

    private function collect(int $siteId): array
    {
        $name = trim((string)$this->input('name', ''));
        $slug = trim((string)$this->input('slug', ''));
        if ($slug === '') { $slug = slugify($name); }

        $social = [];
        foreach (['twitter','linkedin','website','github'] as $net) {
            $v = trim((string)$this->input('social_' . $net, ''));
            if ($v !== '') { $social[$net] = $v; }
        }

        return [
            'site_id'      => $siteId,
            'name'         => $name,
            'slug'         => $slug,
            'bio'          => trim((string)$this->input('bio', '')) ?: null,
            'avatar_url'   => trim((string)$this->input('avatar_url', '')) ?: null,
            'social_links' => $social ? json_encode($social, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : null,
            'expertise'    => trim((string)$this->input('expertise', '')) ?: null,
        ];
    }
}
