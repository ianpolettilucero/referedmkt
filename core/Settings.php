<?php
namespace Core;

/**
 * Settings del sitio (ex tabla `settings`). Ahora es el bloque 'settings' de
 * content/site.php: en memoria y de solo lectura.
 *
 * Se conserva la firma con $siteId de la epoca multi-tenant para no tocar a los
 * llamadores (el layout y el partial del newsletter). Hoy el parametro se ignora.
 *
 * Uso:
 *   Settings::get($site->id, 'theme_preset', 'indigo-night');
 *   Settings::getBool($site->id, 'newsletter_enabled', false);
 */
final class Settings
{
    /**
     * @param mixed $default
     * @return mixed
     */
    public static function get(int $siteId, string $key, $default = null)
    {
        $all = self::all($siteId);
        if (!array_key_exists($key, $all)) {
            return $default;
        }
        $v = $all[$key];
        // Los booleanos del archivo de config se exponen como '1'/'0' para que
        // los callers que castean a string se comporten igual que con la DB.
        if (is_bool($v)) {
            return $v ? '1' : '0';
        }
        return $v;
    }

    public static function getBool(int $siteId, string $key, bool $default = false): bool
    {
        $all = self::all($siteId);
        if (!array_key_exists($key, $all)) {
            return $default;
        }
        return filter_var($all[$key], FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return array<string, mixed>
     */
    public static function all(int $siteId = 1): array
    {
        $site = Content::site();
        return is_array($site['settings'] ?? null) ? $site['settings'] : [];
    }
}
