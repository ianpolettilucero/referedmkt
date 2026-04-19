<?php
namespace Core;

/**
 * Resuelve redirects definidos en la tabla `redirects` (de la admin) antes de
 * que el router siquiera vea la request. Permite cambiar URLs sin perder SEO.
 *
 * Match exacto sobre el path normalizado (sin trailing slash, sin query).
 */
final class Redirects
{
    /**
     * Si hay redirect, emite el header y exit; si no, retorna y la request sigue.
     */
    public static function maybeHandle(int $siteId, string $path): void
    {
        $path = self::normalize($path);
        if ($path === '') { return; }

        $row = Database::instance()->fetch(
            'SELECT to_path, status_code FROM redirects
             WHERE site_id = :s AND from_path = :p AND active = 1
             LIMIT 1',
            ['s' => $siteId, 'p' => $path]
        );
        if (!$row) { return; }

        $to = (string)$row['to_path'];
        $code = (int)$row['status_code'];
        if (!in_array($code, [301, 302, 307, 308], true)) {
            $code = 301;
        }

        // Preservar querystring del request original.
        $qs = $_SERVER['QUERY_STRING'] ?? '';
        if ($qs !== '' && strpos($to, '?') === false) {
            $to .= '?' . $qs;
        }

        header('Location: ' . $to, true, $code);
        exit;
    }

    private static function normalize(string $path): string
    {
        if ($path === '/' || $path === '') { return '/'; }
        return rtrim($path, '/') ?: '/';
    }
}
