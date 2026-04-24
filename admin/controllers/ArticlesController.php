<?php
namespace Admin\Controllers;

use Core\Csrf;
use Core\Database;
use Core\Flash;
use Core\Markdown;

final class ArticlesController extends BaseController
{
    public function index(): void
    {
        $site = $this->requireSite();

        // Filtro por tipo via query string (?type=review|comparison|guide|news)
        $validTypes = ['guide', 'review', 'comparison', 'news'];
        $type = $_GET['type'] ?? null;
        $typeFilter = in_array($type, $validTypes, true) ? $type : null;

        $where = 'a.site_id = :s';
        $params = ['s' => $site['id']];
        if ($typeFilter !== null) {
            $where .= ' AND a.article_type = :t';
            $params['t'] = $typeFilter;
        }

        $rows = Database::instance()->fetchAll(
            "SELECT a.*, au.name AS author_name, c.name AS category_name
             FROM articles a
             LEFT JOIN authors au ON au.id = a.author_id
             LEFT JOIN categories c ON c.id = a.category_id
             WHERE $where
             ORDER BY COALESCE(a.published_at, a.created_at) DESC",
            $params
        );

        // Conteo por tipo para los tabs
        $countsRaw = Database::instance()->fetchAll(
            'SELECT article_type, COUNT(*) AS c FROM articles WHERE site_id = :s GROUP BY article_type',
            ['s' => $site['id']]
        );
        $counts = ['all' => 0, 'guide' => 0, 'review' => 0, 'comparison' => 0, 'news' => 0];
        foreach ($countsRaw as $r) {
            $counts[$r['article_type']] = (int)$r['c'];
            $counts['all'] += (int)$r['c'];
        }

        $this->render('articles/list', [
            'rows'       => $rows,
            'type'       => $typeFilter,
            'counts'     => $counts,
            'page_title' => 'Artículos',
        ]);
    }

    public function create(): void
    {
        $site = $this->requireSite();
        $row = $this->emptyRow();

        // Si viene ?type=review|comparison|guide|news, pre-selecciona el tipo
        $validTypes = ['guide', 'review', 'comparison', 'news'];
        $type = $_GET['type'] ?? null;
        if (in_array($type, $validTypes, true)) {
            $row['article_type'] = $type;
        }
        $this->render('articles/form', [
            'row'        => $row,
            'is_new'     => true,
            'categories' => $this->categoryOptions($site['id']),
            'authors'    => $this->authorOptions($site['id']),
            'products'   => $this->productOptions($site['id']),
            'page_title' => 'Nuevo artículo',
        ]);
    }

    public function store(): void
    {
        $this->requireCsrf();
        $site = $this->requireSite();
        try {
            $data = $this->collect($site['id']);
            $id = Database::instance()->insert('articles', $data);
            Flash::success("Articulo #$id creado.");
            $this->pingIndexNowForArticle((int)$site['id'], $id, $data);
            $this->redirect('/admin/articles/' . $id . '/edit');
            return;
        } catch (\Throwable $e) {
            Flash::error('Error al guardar: ' . $e->getMessage());
            $this->redirect('/admin/articles/new');
        }
    }

    public function edit(array $params): void
    {
        $site = $this->requireSite();
        $row = Database::instance()->fetch(
            'SELECT * FROM articles WHERE id = :id AND site_id = :s',
            ['id' => (int)$params['id'], 's' => $site['id']]
        );
        if (!$row) { $this->redirect('/admin/articles'); return; }
        $row['related_product_ids'] = is_string($row['related_product_ids'] ?? null)
            ? (json_decode($row['related_product_ids'], true) ?: [])
            : ($row['related_product_ids'] ?? []);
        $brokenLinks = \Core\LinkChecker::brokenForArticle((int)$row['id']);
        $this->render('articles/form', [
            'row'          => $row,
            'is_new'       => false,
            'categories'   => $this->categoryOptions($site['id']),
            'authors'      => $this->authorOptions($site['id']),
            'products'     => $this->productOptions($site['id']),
            'broken_links' => $brokenLinks,
            'page_title'   => 'Editar: ' . $row['title'],
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
                'UPDATE articles SET ' . implode(', ', $sets) . ' WHERE id = :id AND site_id = :s',
                $data
            );
            Flash::success('Articulo actualizado.');
            $this->pingIndexNowForArticle((int)$site['id'], $id, $data);
        } catch (\Throwable $e) {
            Flash::error('Error al guardar: ' . $e->getMessage());
        }
        $this->redirect('/admin/articles/' . $id . '/edit');
    }

    /**
     * Si el articulo esta publicado, pingeamos IndexNow con su URL.
     * Fire-and-forget, no bloquea el flujo si falla.
     *
     * @param array<string, mixed> $data
     */
    private function pingIndexNowForArticle(int $siteId, int $articleId, array $data): void
    {
        if (($data['status'] ?? '') !== 'published') { return; }
        $site = Database::instance()->fetch('SELECT domain FROM sites WHERE id = :id LIMIT 1', ['id' => $siteId]);
        if (!$site) { return; }
        $type = (string)($data['article_type'] ?? 'guide');
        $slug = (string)($data['slug'] ?? '');
        if ($slug === '') { return; }
        $path = match ($type) {
            'review'     => '/resena/' . $slug,
            'comparison' => '/comparativa/' . $slug,
            'news'       => '/noticia/' . $slug,
            default      => '/guia/' . $slug,
        };
        $url = 'https://' . $site['domain'] . $path;
        \Core\IndexNow::ping($siteId, [$url]);
    }

    public function destroy(array $params): void
    {
        $this->requireCsrf();
        $site = $this->requireSite();
        Database::instance()->query(
            'DELETE FROM articles WHERE id = :id AND site_id = :s',
            ['id' => (int)$params['id'], 's' => $site['id']]
        );
        Flash::success('Articulo eliminado.');
        $this->redirect('/admin/articles');
    }

    /**
     * Endpoint para preview de markdown en el admin (AJAX POST).
     * Devuelve HTML renderizado. Protegido por CSRF y auth.
     */
    public function preview(): void
    {
        Csrf::requireValid();
        $md = (string)($_POST['content'] ?? '');
        header('Content-Type: text/html; charset=utf-8');
        echo Markdown::toHtml($md);
    }

    private function emptyRow(): array
    {
        return [
            'category_id' => null, 'author_id' => null,
            'slug' => '', 'title' => '', 'subtitle' => '', 'excerpt' => '',
            'content' => '', 'featured_image' => '',
            'article_type' => 'guide', 'related_product_ids' => [],
            'meta_title' => '', 'meta_description' => '',
            'status' => 'draft', 'published_at' => null,
        ];
    }

    private function collect(int $siteId): array
    {
        $title = trim((string)$this->input('title', ''));
        $slug  = trim((string)$this->input('slug', ''));
        if ($slug === '') { $slug = slugify($title); }

        $relatedRaw = $_POST['related_product_ids'] ?? [];
        $related = is_array($relatedRaw)
            ? array_values(array_filter(array_map('intval', $relatedRaw)))
            : [];

        $status = $this->input('status', 'draft');
        if (!in_array($status, ['draft','published','archived'], true)) { $status = 'draft'; }

        $publishedAt = trim((string)$this->input('published_at', ''));
        if ($publishedAt === '' && $status === 'published') {
            $publishedAt = date('Y-m-d H:i:s');
        } elseif ($publishedAt !== '') {
            // Acepta "YYYY-MM-DDTHH:MM" (datetime-local input) y lo normaliza.
            $ts = strtotime($publishedAt);
            $publishedAt = $ts ? date('Y-m-d H:i:s', $ts) : null;
        } else {
            $publishedAt = null;
        }

        $type = $this->input('article_type', 'guide');
        if (!in_array($type, ['review','comparison','guide','news'], true)) { $type = 'guide'; }

        return [
            'site_id'             => $siteId,
            'category_id'         => $this->intInput('category_id'),
            'author_id'           => $this->intInput('author_id'),
            'slug'                => $slug,
            'title'               => $title,
            'subtitle'            => trim((string)$this->input('subtitle', '')) ?: null,
            'excerpt'             => trim((string)$this->input('excerpt', '')) ?: null,
            'content'             => (string)$this->input('content', ''),
            'featured_image'      => trim((string)$this->input('featured_image', '')) ?: null,
            'article_type'        => $type,
            'related_product_ids' => $related ? json_encode($related, JSON_UNESCAPED_UNICODE) : null,
            'meta_title'          => trim((string)$this->input('meta_title', '')) ?: null,
            'meta_description'    => trim((string)$this->input('meta_description', '')) ?: null,
            'status'              => $status,
            'published_at'        => $publishedAt,
        ];
    }

    private function categoryOptions(int $siteId): array
    {
        return Database::instance()->fetchAll(
            'SELECT id, name FROM categories WHERE site_id = :s ORDER BY name', ['s' => $siteId]
        );
    }

    private function authorOptions(int $siteId): array
    {
        return Database::instance()->fetchAll(
            'SELECT id, name FROM authors WHERE site_id = :s ORDER BY name', ['s' => $siteId]
        );
    }

    private function productOptions(int $siteId): array
    {
        return Database::instance()->fetchAll(
            'SELECT p.id, p.name, p.brand, p.slug, al.tracking_slug AS affiliate_slug
             FROM products p
             LEFT JOIN affiliate_links al ON al.id = p.affiliate_link_id AND al.active = 1
             WHERE p.site_id = :s ORDER BY p.name',
            ['s' => $siteId]
        );
    }
}
