<?php
namespace Models;

use Core\Content;

final class Category extends Model
{
    protected const COLLECTION = 'categories';

    /** @return array<int, array<string, mixed>> */
    public static function all(int $siteId): array
    {
        return self::sort(array_values(Content::categories()), [['sort_order', 'asc'], ['name', 'asc']]);
    }

    /** @return array<int, array<string, mixed>> Categorias raiz (sin parent). */
    public static function topLevel(int $siteId): array
    {
        $rows = array_values(array_filter(
            Content::categories(),
            fn($c) => empty($c['parent_id'])
        ));
        return self::sort($rows, [['sort_order', 'asc'], ['name', 'asc']]);
    }
}
