<?php
namespace Models;

use Core\Content;

final class Product extends Model
{
    protected const COLLECTION = 'products';

    /** @return array<int, array<string, mixed>> */
    public static function featured(int $siteId, int $limit = 6): array
    {
        $rows = array_values(array_filter(
            Content::products(),
            fn($p) => !empty($p['featured'])
        ));
        $rows = self::sort($rows, [['rating', 'desc'], ['updated_at', 'desc']]);
        return array_slice($rows, 0, max(1, $limit));
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
     * Catalogo paginado con filtros + orden.
     *
     * @param array{category_id?:?int, brand?:string, min_rating?:?float, max_price?:?float, sort?:string} $filters
     * @return array{items: array<int, array<string, mixed>>, total: int, page: int, per_page: int, brands: array<int,string>}
     */
    public static function catalog(int $siteId, array $filters = [], int $page = 1, int $perPage = 24): array
    {
        $page = max(1, $page);
        $rows = array_values(Content::products());

        if (!empty($filters['category_id'])) {
            $cat = (int)$filters['category_id'];
            $rows = array_filter($rows, fn($p) => (int)($p['category_id'] ?? 0) === $cat);
        }
        if (!empty($filters['brand'])) {
            $rows = array_filter($rows, fn($p) => ($p['brand'] ?? null) === $filters['brand']);
        }
        if (!empty($filters['min_rating'])) {
            $min = (float)$filters['min_rating'];
            $rows = array_filter($rows, fn($p) => ($p['rating'] ?? 0) >= $min);
        }
        if (!empty($filters['max_price'])) {
            $max = (float)$filters['max_price'];
            // Sin precio declarado no se descarta: no sabemos si entra o no.
            $rows = array_filter($rows, fn($p) => $p['price_from'] === null || $p['price_from'] <= $max);
        }
        $rows = array_values($rows);

        $rows = match ($filters['sort'] ?? 'featured') {
            'rating'     => self::sort($rows, [['rating', 'desc'], ['updated_at', 'desc']]),
            'price-asc'  => self::sort($rows, [['price_from', 'asc'], ['rating', 'desc']]),
            'price-desc' => self::sort($rows, [['price_from', 'desc'], ['rating', 'desc']]),
            'recent'     => self::sort($rows, [['updated_at', 'desc']]),
            'az'         => self::sort($rows, [['name', 'asc']]),
            default      => self::sort($rows, [['featured', 'desc'], ['rating', 'desc'], ['updated_at', 'desc']]),
        };

        return [
            'items'    => array_slice($rows, ($page - 1) * $perPage, $perPage),
            'total'    => count($rows),
            'page'     => $page,
            'per_page' => $perPage,
            'brands'   => self::brands($siteId),
        ];
    }

    /**
     * Marcas distintas presentes en el catalogo, para el filtro del front.
     *
     * @return array<int, string>
     */
    public static function brands(int $siteId): array
    {
        $brands = [];
        foreach (Content::products() as $p) {
            $b = trim((string)($p['brand'] ?? ''));
            if ($b !== '' && !in_array($b, $brands, true)) { $brands[] = $b; }
        }
        sort($brands);
        return $brands;
    }

    /**
     * @param array<int, int|string> $ids ids enteros o slugs
     * @return array<int, array<string, mixed>>
     */
    public static function byIds(int $siteId, array $ids): array
    {
        $all = Content::products();
        $out = [];
        foreach ($ids as $ref) {
            // Aceptamos slug o id: las URLs internas usan slug, pero un
            // /comparar?ids=1,2 viejo tiene que seguir resolviendo.
            if (is_string($ref) && isset($all[$ref])) {
                $out[] = $all[$ref];
                continue;
            }
            $row = Content::byId($all, (int)$ref);
            if ($row !== null) { $out[] = $row; }
        }
        return $out;
    }

    /**
     * Catalogo agrupado por categoria: solo categorias con productos, cada una
     * con sus N mejores. Se renderiza como secciones H2 (mejor estructura de
     * headings para SEO que una grilla plana).
     *
     * @return array<int, array{category:array<string,mixed>, products:array<int,array<string,mixed>>, total:int}>
     */
    public static function groupedByCategory(int $siteId, int $limitPerCat = 8): array
    {
        $cats = self::sort(array_values(Content::categories()), [['sort_order', 'asc'], ['name', 'asc']]);
        $products = array_values(Content::products());

        $out = [];
        foreach ($cats as $cat) {
            $inCat = array_values(array_filter(
                $products,
                fn($p) => (int)($p['category_id'] ?? 0) === (int)$cat['id']
            ));
            if (!$inCat) { continue; }
            $sorted = self::sort($inCat, [['featured', 'desc'], ['rating', 'desc'], ['updated_at', 'desc']]);
            $out[] = [
                'category' => $cat,
                'products' => array_slice($sorted, 0, $limitPerCat),
                'total'    => count($inCat),
            ];
        }

        // Productos sin categoria, al final.
        $orphans = array_values(array_filter($products, fn($p) => empty($p['category_id'])));
        if ($orphans) {
            $sorted = self::sort($orphans, [['featured', 'desc'], ['rating', 'desc']]);
            $out[] = [
                'category' => ['id' => 0, 'slug' => null, 'name' => 'Otros productos', 'description' => null],
                'products' => array_slice($sorted, 0, $limitPerCat),
                'total'    => count($orphans),
            ];
        }

        return $out;
    }
}
