<?php
namespace Core;

/**
 * KV settings por sitio. Cache por request (un SELECT por sitio por request).
 *
 * Uso:
 *   Settings::get($siteId, 'newsletter_enabled', '0');
 *   Settings::set($siteId, 'newsletter_enabled', '1');
 *   Settings::all($siteId);
 */
final class Settings
{
    /** @var array<int, array<string, string>> */
    private static array $cache = [];

    public static function get(int $siteId, string $key, $default = null)
    {
        self::loadIfNeeded($siteId);
        return self::$cache[$siteId][$key] ?? $default;
    }

    public static function getBool(int $siteId, string $key, bool $default = false): bool
    {
        $v = self::get($siteId, $key, null);
        if ($v === null) { return $default; }
        return filter_var($v, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return array<string, string>
     */
    public static function all(int $siteId): array
    {
        self::loadIfNeeded($siteId);
        return self::$cache[$siteId];
    }

    public static function set(int $siteId, string $key, ?string $value): void
    {
        if ($value === null || $value === '') {
            Database::instance()->query(
                'DELETE FROM settings WHERE site_id = :s AND `key` = :k',
                ['s' => $siteId, 'k' => $key]
            );
        } else {
            Database::instance()->query(
                'INSERT INTO settings (site_id, `key`, `value`) VALUES (:s, :k, :v)
                 ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), updated_at = CURRENT_TIMESTAMP',
                ['s' => $siteId, 'k' => $key, 'v' => $value]
            );
        }
        unset(self::$cache[$siteId]); // invalidate
    }

    public static function forgetCache(?int $siteId = null): void
    {
        if ($siteId === null) {
            self::$cache = [];
        } else {
            unset(self::$cache[$siteId]);
        }
    }

    private static function loadIfNeeded(int $siteId): void
    {
        if (isset(self::$cache[$siteId])) {
            return;
        }
        $rows = Database::instance()->fetchAll(
            'SELECT `key`, `value` FROM settings WHERE site_id = :s',
            ['s' => $siteId]
        );
        $map = [];
        foreach ($rows as $r) {
            $map[$r['key']] = $r['value'];
        }
        self::$cache[$siteId] = $map;
    }
}
