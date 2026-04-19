<?php
namespace Admin\Controllers;

use Core\Database;
use Core\Flash;

final class RedirectsController extends BaseController
{
    public function index(): void
    {
        $site = $this->requireSite();
        $rows = Database::instance()->fetchAll(
            'SELECT * FROM redirects WHERE site_id = :s ORDER BY from_path',
            ['s' => $site['id']]
        );
        $this->render('redirects/list', ['rows' => $rows, 'page_title' => 'Redirects']);
    }

    public function create(): void
    {
        $this->requireSite();
        $this->render('redirects/form', [
            'row'        => ['from_path' => '', 'to_path' => '', 'status_code' => 301, 'active' => 1],
            'is_new'     => true,
            'page_title' => 'Nuevo redirect',
        ]);
    }

    public function store(): void
    {
        $this->requireCsrf();
        $site = $this->requireSite();
        try {
            $id = Database::instance()->insert('redirects', $this->collect($site['id']));
            Flash::success("Redirect #$id creado.");
        } catch (\Throwable $e) {
            Flash::error('Error al guardar: ' . $e->getMessage());
            $this->redirect('/admin/redirects/new');
            return;
        }
        $this->redirect('/admin/redirects');
    }

    public function edit(array $params): void
    {
        $site = $this->requireSite();
        $row = Database::instance()->fetch(
            'SELECT * FROM redirects WHERE id = :id AND site_id = :s',
            ['id' => (int)$params['id'], 's' => $site['id']]
        );
        if (!$row) { $this->redirect('/admin/redirects'); return; }
        $this->render('redirects/form', ['row' => $row, 'is_new' => false, 'page_title' => 'Editar redirect']);
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
                'UPDATE redirects SET ' . implode(', ', $sets) . ' WHERE id = :id AND site_id = :s',
                $data
            );
            Flash::success('Redirect actualizado.');
        } catch (\Throwable $e) {
            Flash::error('Error al guardar: ' . $e->getMessage());
        }
        $this->redirect('/admin/redirects/' . $id . '/edit');
    }

    public function destroy(array $params): void
    {
        $this->requireCsrf();
        $site = $this->requireSite();
        Database::instance()->query(
            'DELETE FROM redirects WHERE id = :id AND site_id = :s',
            ['id' => (int)$params['id'], 's' => $site['id']]
        );
        Flash::success('Redirect eliminado.');
        $this->redirect('/admin/redirects');
    }

    private function collect(int $siteId): array
    {
        $from = (string)$this->input('from_path', '');
        $from = '/' . ltrim(rtrim(trim($from), '/'), '/');
        if ($from === '/') {
            // permitir redirect desde '/', no tocarlo
        }
        $code = (int)$this->input('status_code', 301);
        if (!in_array($code, [301,302,307,308], true)) { $code = 301; }

        return [
            'site_id'     => $siteId,
            'from_path'   => $from,
            'to_path'     => trim((string)$this->input('to_path', '')),
            'status_code' => $code,
            'active'      => $this->boolInput('active') ? 1 : 0,
        ];
    }
}
