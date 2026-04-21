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

    /**
     * Health check: verifica con HEAD requests en paralelo que las URLs
     * destino de cada afiliado activo respondan. Detecta links rotos /
     * redirects no deseados / 403 por geo.
     *
     * Timeout 5s por URL, max 50 links por run (defensivo).
     */
    public function health(): void
    {
        $site = $this->requireSite();
        $rows = Database::instance()->fetchAll(
            'SELECT id, name, destination_url, network_name, active
             FROM affiliate_links
             WHERE site_id = :s
             ORDER BY active DESC, name
             LIMIT 50',
            ['s' => $site['id']]
        );

        $results = $rows ? $this->probeUrls($rows) : [];
        $this->render('affiliate_links/health', [
            'results'    => $results,
            'page_title' => 'Health check de afiliados',
        ]);
    }

    /**
     * HEAD requests en paralelo con curl_multi. Sigue hasta 3 redirects.
     *
     * @param array<int,array<string,mixed>> $rows
     * @return array<int,array<string,mixed>>
     */
    private function probeUrls(array $rows): array
    {
        if (!function_exists('curl_multi_init')) {
            // Fallback sin curl_multi: solo devolvemos "sin datos"
            return array_map(static function ($r) {
                return $r + ['status' => null, 'ms' => null, 'final_url' => null, 'error' => 'cURL multi no disponible'];
            }, $rows);
        }

        $mh = curl_multi_init();
        $handles = [];
        foreach ($rows as $idx => $r) {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL            => $r['destination_url'],
                CURLOPT_NOBODY         => true,  // HEAD
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS      => 3,
                CURLOPT_TIMEOUT        => 5,
                CURLOPT_CONNECTTIMEOUT => 3,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_USERAGENT      => 'referedmkt-health-check/1.0',
            ]);
            curl_multi_add_handle($mh, $ch);
            $handles[$idx] = $ch;
        }

        $running = null;
        do {
            $status = curl_multi_exec($mh, $running);
            if ($running) {
                curl_multi_select($mh, 1.0);
            }
        } while ($running && $status === CURLM_OK);

        $out = [];
        foreach ($handles as $idx => $ch) {
            $code      = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
            $totalTime = (float)curl_getinfo($ch, CURLINFO_TOTAL_TIME);
            $finalUrl  = (string)curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
            $err       = curl_error($ch) ?: null;
            curl_multi_remove_handle($mh, $ch);
            curl_close($ch);

            $out[] = $rows[$idx] + [
                'status'    => $code > 0 ? $code : null,
                'ms'        => $totalTime > 0 ? (int)round($totalTime * 1000) : null,
                'final_url' => $finalUrl !== '' ? $finalUrl : null,
                'error'     => $err,
            ];
        }
        curl_multi_close($mh);
        return $out;
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
