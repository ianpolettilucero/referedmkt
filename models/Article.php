<?php
namespace Models;

final class Article extends Model
{
    protected const TABLE = 'articles';
    protected const JSON_COLUMNS = ['related_product_ids'];

    /** @return array<int, array<string, mixed>> */
    public static function recent(int $siteId, int $limit = 10, ?string $type = null): array
    {
        $where = "site_id = :site AND status = 'published' AND published_at <= NOW()";
        $params = ['site' => $siteId];
        if ($type !== null) {
            $where .= ' AND article_type = :type';
            $params['type'] = $type;
        }
        $rows = self::db()->fetchAll(
            "SELECT * FROM articles WHERE $where ORDER BY published_at DESC LIMIT $limit",
            $params
        );
        return self::hydrateAll($rows);
    }

    public static function findPublished(int $siteId, string $slug): ?array
    {
        $row = self::db()->fetch(
            "SELECT a.*, au.name AS author_name, au.slug AS author_slug, au.avatar_url AS author_avatar
             FROM articles a
             LEFT JOIN authors au ON au.id = a.author_id
             WHERE a.site_id = :site AND a.slug = :slug AND a.status = 'published'
             LIMIT 1",
            ['site' => $siteId, 'slug' => $slug]
        );
        return self::hydrate($row);
    }

    public static function incrementViews(int $id): void
    {
        self::db()->query('UPDATE articles SET views_count = views_count + 1 WHERE id = :id', ['id' => $id]);
    }

    /**
     * @return array{items: array<int, array<string, mixed>>, total: int, page: int, per_page: int}
     */
    public static function paginate(int $siteId, ?string $type, int $page = 1, int $perPage = 20): array
    {
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;

        $where = "site_id = :site AND status = 'published' AND published_at <= NOW()";
        $params = ['site' => $siteId];
        if ($type !== null) {
            $where .= ' AND article_type = :type';
            $params['type'] = $type;
        }

        $total = (int)self::db()->fetchColumn("SELECT COUNT(*) FROM articles WHERE $where", $params);

        $rows = self::db()->fetchAll(
            "SELECT * FROM articles WHERE $where ORDER BY published_at DESC LIMIT $perPage OFFSET $offset",
            $params
        );

        return [
            'items'    => self::hydrateAll($rows),
            'total'    => $total,
            'page'     => $page,
            'per_page' => $perPage,
        ];
    }
}
