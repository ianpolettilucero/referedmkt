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

        // Aggregado diario para "trending de la semana". Defensivo: si la
        // migracion 006 no esta aplicada todavia, skipeamos sin romper la
        // carga del articulo.
        try {
            self::db()->query(
                "INSERT INTO article_views_daily (article_id, day, views)
                 VALUES (:id, CURDATE(), 1)
                 ON DUPLICATE KEY UPDATE views = views + 1",
                ['id' => $id]
            );
        } catch (\Throwable $e) {
            error_log('[referedmkt] article_views_daily insert failed: ' . $e->getMessage());
        }
    }

    /**
     * Articulos relacionados para mostrar al final de una nota.
     *
     * Prioriza (en orden): misma categoria, mismo tipo, mas recientes.
     * Excluye el articulo actual para evitar auto-link.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function related(
        int $siteId,
        int $excludeId,
        ?int $categoryId,
        string $articleType,
        int $limit = 3
    ): array {
        $limit = max(1, min(12, $limit));

        // ORDER BY que ranquea por afinidad: categoria igual (2 puntos) vs
        // tipo igual (1 punto), fallback por recencia. Una sola query.
        $params = [
            'site'    => $siteId,
            'exclude' => $excludeId,
            'cat'     => $categoryId ?: 0,
            'type'    => $articleType,
        ];
        $rows = self::db()->fetchAll(
            "SELECT * FROM articles
             WHERE site_id = :site
               AND status = 'published'
               AND published_at <= NOW()
               AND id <> :exclude
             ORDER BY
                (category_id IS NOT NULL AND category_id = :cat) DESC,
                (article_type = :type) DESC,
                published_at DESC
             LIMIT $limit",
            $params
        );
        return self::hydrateAll($rows);
    }

    /**
     * Articulos mas leidos en los ultimos N dias (default 7).
     *
     * Requiere tabla article_views_daily (migracion 006). Si no existe o
     * no hay suficientes datos, devuelve array vacio — el caller decide
     * si mostrar o no el widget.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function trendingWeek(int $siteId, int $limit = 4, int $days = 7): array
    {
        $limit = max(1, min(20, $limit));
        $days  = max(1, min(30, $days));
        try {
            $rows = self::db()->fetchAll(
                "SELECT a.*, SUM(avd.views) AS weekly_views
                 FROM article_views_daily avd
                 JOIN articles a ON a.id = avd.article_id
                 WHERE a.site_id = :site
                   AND a.status = 'published'
                   AND a.published_at <= NOW()
                   AND avd.day >= (CURDATE() - INTERVAL $days DAY)
                 GROUP BY a.id
                 ORDER BY weekly_views DESC, a.published_at DESC
                 LIMIT $limit",
                ['site' => $siteId]
            );
            return self::hydrateAll($rows);
        } catch (\Throwable $e) {
            error_log('[referedmkt] trendingWeek failed: ' . $e->getMessage());
            return [];
        }
    }

    public const SORTS = [
        'recent'  => 'Más recientes',
        'oldest'  => 'Más antiguos',
        'popular' => 'Más vistos',
        'az'      => 'Título A-Z',
    ];

    /**
     * @param array{type?:?string, category_id?:?int, sort?:string} $filters
     * @return array{items: array<int, array<string, mixed>>, total: int, page: int, per_page: int}
     */
    public static function paginate(int $siteId, array $filters = [], int $page = 1, int $perPage = 20): array
    {
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;

        $where = "a.site_id = :site AND a.status = 'published' AND a.published_at <= NOW()";
        $params = ['site' => $siteId];

        if (!empty($filters['type'])) {
            $where .= ' AND a.article_type = :type';
            $params['type'] = (string)$filters['type'];
        }
        if (!empty($filters['category_id'])) {
            $where .= ' AND a.category_id = :cat';
            $params['cat'] = (int)$filters['category_id'];
        }

        $sort = $filters['sort'] ?? 'recent';
        $orderBy = match ($sort) {
            'oldest'  => 'a.published_at ASC',
            'popular' => 'a.views_count DESC, a.published_at DESC',
            'az'      => 'a.title ASC',
            default   => 'a.published_at DESC',
        };

        $total = (int)self::db()->fetchColumn("SELECT COUNT(*) FROM articles a WHERE $where", $params);

        $rows = self::db()->fetchAll(
            "SELECT a.*, c.slug AS category_slug, c.name AS category_name
             FROM articles a
             LEFT JOIN categories c ON c.id = a.category_id
             WHERE $where
             ORDER BY $orderBy
             LIMIT $perPage OFFSET $offset",
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
