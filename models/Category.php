<?php
namespace Models;

final class Category extends Model
{
    protected const TABLE = 'categories';

    /** @return array<int, array<string, mixed>> */
    public static function all(int $siteId): array
    {
        return self::db()->fetchAll(
            "SELECT * FROM categories WHERE site_id = :site ORDER BY sort_order ASC, name ASC",
            ['site' => $siteId]
        );
    }

    /** @return array<int, array<string, mixed>> Categorias top-level (parent_id IS NULL). */
    public static function topLevel(int $siteId): array
    {
        return self::db()->fetchAll(
            "SELECT * FROM categories WHERE site_id = :site AND parent_id IS NULL
             ORDER BY sort_order ASC, name ASC",
            ['site' => $siteId]
        );
    }
}
