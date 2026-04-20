<?php
namespace Admin\Controllers;

use Core\Auth;
use Core\Database;
use Core\Flash;

final class SitesController extends BaseController
{
    public function index(): void
    {
        $rows = Database::instance()->fetchAll('SELECT * FROM sites ORDER BY name ASC');
        $this->render('sites/list', ['sites_list' => $rows, 'page_title' => 'Sitios']);
    }

    public function create(): void
    {
        $this->requireSuperadmin();
        $this->render('sites/form', [
            'row'        => $this->emptyRow(),
            'is_new'     => true,
            'page_title' => 'Nuevo sitio',
        ]);
    }

    public function store(): void
    {
        $this->requireCsrf();
        $this->requireSuperadmin();
        $data = $this->collect();

        try {
            $id = Database::instance()->insert('sites', $data);
            Flash::success("Sitio #$id creado.");
        } catch (\Throwable $e) {
            Flash::error('Error al crear: ' . $e->getMessage());
            $this->redirect('/admin/sites/new');
            return;
        }
        $this->redirect('/admin/sites');
    }

    public function edit(array $params): void
    {
        $id = (int)$params['id'];
        $row = Database::instance()->fetch('SELECT * FROM sites WHERE id = :id', ['id' => $id]);
        if (!$row) { $this->redirect('/admin/sites'); return; }
        $this->render('sites/form', [
            'row'        => $row,
            'is_new'     => false,
            'page_title' => 'Editar sitio: ' . $row['name'],
        ]);
    }

    public function update(array $params): void
    {
        $this->requireCsrf();
        $this->requireSuperadmin();
        $id = (int)$params['id'];
        $data = $this->collect();
        $sets = [];
        foreach (array_keys($data) as $k) { $sets[] = "`$k` = :$k"; }
        $data['id'] = $id;
        try {
            Database::instance()->query(
                'UPDATE sites SET ' . implode(', ', $sets) . ' WHERE id = :id',
                $data
            );
            Flash::success('Sitio actualizado.');
        } catch (\Throwable $e) {
            Flash::error('Error al guardar: ' . $e->getMessage());
        }
        $this->redirect('/admin/sites/' . $id . '/edit');
    }

    public function destroy(array $params): void
    {
        $this->requireCsrf();
        $this->requireSuperadmin();
        $id = (int)$params['id'];
        try {
            Database::instance()->query('DELETE FROM sites WHERE id = :id', ['id' => $id]);
            Flash::success('Sitio eliminado.');
        } catch (\Throwable $e) {
            Flash::error('Error al eliminar: ' . $e->getMessage());
        }
        $this->redirect('/admin/sites');
    }

    private function requireSuperadmin(): void
    {
        if (!Auth::isSuperadmin()) {
            Flash::error('Solo superadmin puede gestionar sitios.');
            $this->redirect('/admin/dashboard');
        }
    }

    private function emptyRow(): array
    {
        return [
            'domain' => '', 'name' => '', 'slug' => '', 'theme_name' => 'default',
            'primary_color' => '', 'logo_url' => '', 'favicon_url' => '',
            'affiliate_disclosure_text' => '', 'google_analytics_id' => '',
            'google_search_console_verification' => '',
            'google_tag_manager_id' => '',
            'default_language' => 'es', 'default_country' => 'AR',
            'meta_title_template' => '', 'meta_description_template' => '',
            'active' => 1,
        ];
    }

    private function collect(): array
    {
        $name = trim((string)$this->input('name', ''));
        $slug = trim((string)$this->input('slug', ''));
        if ($slug === '') { $slug = slugify($name); }

        // Validacion suave de IDs de Google: si el formato es claramente invalido,
        // lo descartamos para evitar inyectar snippets rotos en el head.
        $ga = trim((string)$this->input('google_analytics_id', ''));
        if ($ga !== '' && !preg_match('/^G-[A-Z0-9]{4,20}$/', $ga)) {
            $ga = ''; // formato GA4 esperado: G-XXXXXXXXXX
        }
        $gtm = trim((string)$this->input('google_tag_manager_id', ''));
        if ($gtm !== '' && !preg_match('/^GTM-[A-Z0-9]{4,20}$/', $gtm)) {
            $gtm = ''; // formato GTM esperado: GTM-XXXXXXX
        }

        return [
            'domain'                             => strtolower(trim((string)$this->input('domain', ''))),
            'name'                               => $name,
            'slug'                               => $slug,
            'theme_name'                         => trim((string)$this->input('theme_name', 'default')) ?: 'default',
            'primary_color'                      => trim((string)$this->input('primary_color', '')) ?: null,
            'logo_url'                           => trim((string)$this->input('logo_url', '')) ?: null,
            'favicon_url'                        => trim((string)$this->input('favicon_url', '')) ?: null,
            'affiliate_disclosure_text'          => trim((string)$this->input('affiliate_disclosure_text', '')) ?: null,
            'google_analytics_id'                => $ga ?: null,
            'google_search_console_verification' => trim((string)$this->input('google_search_console_verification', '')) ?: null,
            'google_tag_manager_id'              => $gtm ?: null,
            'default_language'                   => substr(trim((string)$this->input('default_language', 'es')), 0, 5) ?: 'es',
            'default_country'                    => strtoupper(substr(trim((string)$this->input('default_country', 'AR')), 0, 2)) ?: 'AR',
            'meta_title_template'                => trim((string)$this->input('meta_title_template', '')) ?: null,
            'meta_description_template'          => trim((string)$this->input('meta_description_template', '')) ?: null,
            'active'                             => $this->boolInput('active') ? 1 : 0,
        ];
    }
}
