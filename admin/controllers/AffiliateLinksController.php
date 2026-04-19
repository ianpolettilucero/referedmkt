<?php
namespace Admin\Controllers;

use Core\Database;
use Core\Flash;

final class AffiliateLinksController extends BaseController
{
    public function index(): void
    {
        $site = $this->requireSite();
        $rows = Database::instance()->fetchAll(
            "SELECT al.*,
                    (SELECT COUNT(*) FROM affiliate_clicks c WHERE c.affiliate_link_id = al.id) AS clicks_total,
                    (SELECT COUNT(*) FROM affiliate_clicks c WHERE c.affiliate_link_id = al.id AND c.clicked_at >= (NOW() - INTERVAL 30 DAY)) AS clicks_30d
             FROM affiliate_links al
             WHERE al.site_id = :s
             ORDER BY al.name",
            ['s' => $site['id']]
        );
        $this->render('affiliate_links/list', ['rows' => $rows, 'page_title' => 'Afiliados']);
    }

    public function create(): void
    {
        $this->requireSite();
        $this->render('affiliate_links/form', [
            'row' => [
                'name' => '', 'destination_url' => '', 'tracking_slug' => '',
                'network_name' => '', 'commission_structure' => '', 'notes' => '', 'active' => 1,
            ],
            'is_new' => true, 'page_title' => 'Nuevo afiliado',
        ]);
    }

    public function store(): void
    {
        $this->requireCsrf();
        $site = $this->requireSite();
        try {
            $id = Database::instance()->insert('affiliate_links', $this->collect($site['id']));
            Flash::success("Afiliado #$id creado.");
        } catch (\Throwable $e) {
            Flash::error('Error al guardar: ' . $e->getMessage());
            $this->redirect('/admin/affiliate-links/new');
            return;
        }
        $this->redirect('/admin/affiliate-links');
    }

    public function edit(array $params): void
    {
        $site = $this->requireSite();
        $row = Database::instance()->fetch(
            'SELECT * FROM affiliate_links WHERE id = :id AND site_id = :s',
            ['id' => (int)$params['id'], 's' => $site['id']]
        );
        if (!$row) { $this->redirect('/admin/affiliate-links'); return; }
        $this->render('affiliate_links/form', [
            'row' => $row, 'is_new' => false, 'page_title' => 'Editar afiliado: ' . $row['name'],
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
                'UPDATE affiliate_links SET ' . implode(', ', $sets) . ' WHERE id = :id AND site_id = :s',
                $data
            );
            Flash::success('Afiliado actualizado.');
        } catch (\Throwable $e) {
            Flash::error('Error al guardar: ' . $e->getMessage());
        }
        $this->redirect('/admin/affiliate-links/' . $id . '/edit');
    }

    public function destroy(array $params): void
    {
        $this->requireCsrf();
        $site = $this->requireSite();
        Database::instance()->query(
            'DELETE FROM affiliate_links WHERE id = :id AND site_id = :s',
            ['id' => (int)$params['id'], 's' => $site['id']]
        );
        Flash::success('Afiliado eliminado.');
        $this->redirect('/admin/affiliate-links');
    }

    private function collect(int $siteId): array
    {
        $name = trim((string)$this->input('name', ''));
        $slug = trim((string)$this->input('tracking_slug', ''));
        if ($slug === '') { $slug = slugify($name); }

        return [
            'site_id'              => $siteId,
            'name'                 => $name,
            'destination_url'      => trim((string)$this->input('destination_url', '')),
            'tracking_slug'        => $slug,
            'network_name'         => trim((string)$this->input('network_name', '')) ?: null,
            'commission_structure' => trim((string)$this->input('commission_structure', '')) ?: null,
            'notes'                => trim((string)$this->input('notes', '')) ?: null,
            'active'               => $this->boolInput('active') ? 1 : 0,
        ];
    }
}
