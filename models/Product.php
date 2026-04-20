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

    public const SORTS = [
        'featured'   => 'Destacados primero',
        'rating'     => 'Mejor rating',
        'price-asc'  => 'Precio: menor a mayor',
        'price-desc' => 'Precio: mayor a menor',
        'recent'     => 'Actualizado recientemente',
        'az'         => 'Nombre A-Z',
    ];

    /**
     * Catalogo paginado con filtros + orden. Opcional: categoria, brand,
     * rating minimo, precio max, orden.
     *
     * @param array{category_id?:?int, brand?:string, min_rating?:?float, max_price?:?float, sort?:string} $filters
     * @return array{items: array<int, array<string, mixed>>, total: int, page: int, per_page: int, brands: array<int,string>}
     */
    public static function catalog(int $siteId, array $filters = [], int $page = 1, int $perPage = 24): array
    {
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;

        $where  = 'p.site_id = :site';
        $params = ['site' => $siteId];

        if (!empty($filters['category_id'])) {
            $where .= ' AND p.category_id = :cat';
            $params['cat'] = (int)$filters['category_id'];
        }
        if (!empty($filters['brand'])) {
            $where .= ' AND p.brand = :brand';
            $params['brand'] = (string)$filters['brand'];
        }
        if (!empty($filters['min_rating'])) {
            $where .= ' AND p.rating >= :minr';
            $params['minr'] = (float)$filters['min_rating'];
        }
        if (!empty($filters['max_price'])) {
            $where .= ' AND (p.price_from IS NULL OR p.price_from <= :maxp)';
            $params['maxp'] = (float)$filters['max_price'];
        }

        $sort = $filters['sort'] ?? 'featured';
        $orderBy = match ($sort) {
            'rating'     => 'p.rating DESC, p.updated_at DESC',
            'price-asc'  => 'p.price_from IS NULL, p.price_from ASC, p.rating DESC',
            'price-desc' => 'p.price_from DESC, p.rating DESC',
            'recent'     => 'p.updated_at DESC',
            'az'         => 'p.name ASC',
            default      => 'p.featured DESC, p.rating DESC, p.updated_at DESC',
        };

        $total = (int)self::db()->fetchColumn(
            "SELECT COUNT(*) FROM products p WHERE $where",
            $params
        );

        $rows = self::db()->fetchAll(
            "SELECT p.*, c.slug AS category_slug, c.name AS category_name
             FROM products p
             LEFT JOIN categories c ON c.id = p.category_id
             WHERE $where
             ORDER BY $orderBy
             LIMIT $perPage OFFSET $offset",
            $params
        );

        // Lista de brands distintas para el filtro del front (limite 200).
        $brandRows = self::db()->fetchAll(
            "SELECT DISTINCT brand FROM products
             WHERE site_id = :site AND brand IS NOT NULL AND brand <> ''
             ORDER BY brand ASC LIMIT 200",
            ['site' => $siteId]
        );
        $brands = array_column($brandRows, 'brand');

        return [
            'items'    => self::hydrateAll($rows),
            'total'    => $total,
            'page'     => $page,
            'per_page' => $perPage,
            'brands'   => $brands,
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

    /**
     * Catalogo agrupado por categoria: retorna solo categorias con productos,
     * cada una con sus N mejores productos (destacados / rating / recientes).
     *
     * Ideal para renderizar el catalogo global como secciones H2 por categoria
     * (mejor SEO: estructura de headings + keywords de cada nicho visibles).
     *
     * @return array<int, array{category:array<string,mixed>, products:array<int,array<string,mixed>>, total:int}>
     */
    public static function groupedByCategory(int $siteId, int $limitPerCat = 8): array
    {
        $cats = self::db()->fetchAll(
            "SELECT c.id, c.slug, c.name, c.description
             FROM categories c
             WHERE c.site_id = :s
               AND EXISTS (SELECT 1 FROM products p WHERE p.site_id = c.site_id AND p.category_id = c.id)
             ORDER BY c.sort_order, c.name",
            ['s' => $siteId]
        );

        $out = [];
        foreach ($cats as $cat) {
            $products = self::db()->fetchAll(
                "SELECT p.*, c.slug AS category_slug, c.name AS category_name
                 FROM products p
                 LEFT JOIN categories c ON c.id = p.category_id
                 WHERE p.site_id = :s AND p.category_id = :cid
                 ORDER BY p.featured DESC, p.rating DESC, p.updated_at DESC
                 LIMIT $limitPerCat",
                ['s' => $siteId, 'cid' => $cat['id']]
            );
            $total = (int)self::db()->fetchColumn(
                'SELECT COUNT(*) FROM products WHERE site_id = :s AND category_id = :cid',
                ['s' => $siteId, 'cid' => $cat['id']]
            );
            if (!$products) { continue; }
            $out[] = [
                'category' => $cat,
                'products' => self::hydrateAll($products),
                'total'    => $total,
            ];
        }

        // Productos sin categoria (opcional, al final)
        $uncategorized = self::db()->fetchAll(
            "SELECT p.*, NULL AS category_slug, NULL AS category_name
             FROM products p
             WHERE p.site_id = :s AND p.category_id IS NULL
             ORDER BY p.featured DESC, p.rating DESC
             LIMIT $limitPerCat",
            ['s' => $siteId]
        );
        if ($uncategorized) {
            $out[] = [
                'category' => ['id' => 0, 'slug' => null, 'name' => 'Otros productos', 'description' => null],
                'products' => self::hydrateAll($uncategorized),
                'total'    => count($uncategorized),
            ];
        }

        return $out;
    }
}
