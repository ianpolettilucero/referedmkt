<?php
namespace Models;

use Core\Content;

final class Author extends Model
{
    protected const COLLECTION = 'authors';

    /** @return array<int, array<string, mixed>> */
    public static function all(int $siteId): array
    {
        return self::sort(array_values(Content::authors()), [['name', 'asc']]);
    }
}
