<?php
namespace Models;

/**
 * CRUD helpers sobre la tabla sites. El resolver de tenant en request vive en
 * Core\Site; aca lo que se maneja son listados y fetch para admin.
 */
final class Site extends Model
{
    protected const TABLE = 'sites';
    protected const TENANT_SCOPED = false;

    /** @return array<int, array<string, mixed>> */
    public static function allActive(): array
    {
        return self::db()->fetchAll(
            "SELECT * FROM sites WHERE active = 1 ORDER BY name ASC"
        );
    }

    public static function findByDomain(string $domain): ?array
    {
        return self::db()->fetch(
            "SELECT * FROM sites WHERE domain = :d LIMIT 1",
            ['d' => strtolower($domain)]
        );
    }
}
