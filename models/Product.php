<?php
namespace Models;

final class Product extends Model
{
    protected const TABLE = 'products';
    protected const JSON_COLUMNS = ['features', 'pros', 'cons', 'specs'];

    /** @return array<int, array<string, mixed>> */
    public static function featured(int $siteId, int $limit = 6): array
    {
        $stmt = self::db()->query(
            "SELECT p.*, c.slug AS category_slug, c.name AS category_name
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             WHERE p.site_id = :site AND p.featured = 1
             ORDER BY p.rating DESC, p.updated_at DESC
             LIMIT $limit",
            ['site' => $siteId]
        );
        return self::hydrateAll($stmt->fetchAll());
    }

    /**
     * Catalogo paginado con filtro opcional por categoria.
     * @return array{items: array<int, array<string, mixed>>, total: int, page: int, per_page: int}
     */
    public static function catalog(int $siteId, ?int $categoryId, int $page = 1, int $perPage = 24): array
    {
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;

        $where = 'p.site_id = :site';
        $params = ['site' => $siteId];
        if ($categoryId !== null) {
            $where .= ' AND p.category_id = :cat';
            $params['cat'] = $categoryId;
        }

        $total = (int)self::db()->fetchColumn(
            "SELECT COUNT(*) FROM products p WHERE $where",
            $params
        );

        $rows = self::db()->fetchAll(
            "SELECT p.*, c.slug AS category_slug, c.name AS category_name
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             WHERE $where
             ORDER BY p.featured DESC, p.rating DESC, p.updated_at DESC
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

    /** @return array<int, array<string, mixed>> */
    public static function byIds(int $siteId, array $ids): array
    {
        $ids = array_values(array_filter(array_map('intval', $ids)));
        if (!$ids) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = self::db()->query(
            "SELECT * FROM products WHERE site_id = ? AND id IN ($placeholders)",
            array_merge([$siteId], $ids)
        );
        return self::hydrateAll($stmt->fetchAll());
    }
}
