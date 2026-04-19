<?php
namespace Admin\Controllers;

use Core\Auth;
use Core\Database;
use Core\Flash;
use Core\Upload;

final class UploadsController extends BaseController
{
    public function index(): void
    {
        $site = $this->requireSite();
        $rows = Database::instance()->fetchAll(
            'SELECT u.*, us.name AS uploader_name
             FROM uploads u
             LEFT JOIN users us ON us.id = u.uploaded_by
             WHERE u.site_id = :s
             ORDER BY u.created_at DESC
             LIMIT 200',
            ['s' => $site['id']]
        );
        $this->render('uploads/list', [
            'rows'       => $rows,
            'page_title' => 'Biblioteca de imágenes',
        ]);
    }

    public function store(): void
    {
        $this->requireCsrf();
        $site = $this->requireSite();
        $user = Auth::user();

        if (!isset($_FILES['file'])) {
            Flash::error('Sin archivo.');
            $this->redirect('/admin/uploads');
            return;
        }

        try {
            $info = Upload::store($_FILES['file'], (int)$site['id'], (string)$site['slug']);
        } catch (\Throwable $e) {
            Flash::error($e->getMessage());
            $this->redirect('/admin/uploads');
            return;
        }

        try {
            $alt = trim((string)$this->input('alt_text', ''));
            $id = Database::instance()->insert('uploads', [
                'site_id'       => (int)$site['id'],
                'uploaded_by'   => $user ? (int)$user['id'] : null,
                'original_name' => mb_substr((string)($_FILES['file']['name'] ?? ''), 0, 255),
                'filename'      => $info['filename'],
                'path'          => $info['path'],
                'mime_type'     => $info['mime'],
                'size_bytes'    => $info['size'],
                'width'         => $info['width'],
                'height'        => $info['height'],
                'alt_text'      => $alt !== '' ? mb_substr($alt, 0, 255) : null,
            ]);
            Flash::success("Imagen #$id subida.");
        } catch (\Throwable $e) {
            Upload::delete($info['path']);
            Flash::error('Error al guardar metadata: ' . $e->getMessage());
        }

        $this->redirect('/admin/uploads');
    }

    public function destroy(array $params): void
    {
        $this->requireCsrf();
        $site = $this->requireSite();
        $id = (int)$params['id'];

        $row = Database::instance()->fetch(
            'SELECT * FROM uploads WHERE id = :id AND site_id = :s',
            ['id' => $id, 's' => $site['id']]
        );
        if (!$row) { $this->redirect('/admin/uploads'); return; }

        Upload::delete($row['path']);
        Database::instance()->query(
            'DELETE FROM uploads WHERE id = :id AND site_id = :s',
            ['id' => $id, 's' => $site['id']]
        );
        Flash::success('Imagen eliminada.');
        $this->redirect('/admin/uploads');
    }

    /**
     * JSON endpoint para el picker dentro de otros formularios (articulos, productos).
     */
    public function listJson(): void
    {
        $site = $this->requireSite();
        $rows = Database::instance()->fetchAll(
            'SELECT id, filename, path, alt_text, width, height, created_at
             FROM uploads WHERE site_id = :s
             ORDER BY created_at DESC LIMIT 100',
            ['s' => $site['id']]
        );
        foreach ($rows as &$r) {
            $r['url'] = '/' . ltrim($r['path'], '/');
        }
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['items' => $rows], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
