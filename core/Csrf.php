<?php
namespace Core;

/**
 * CSRF tokens. Un solo token por sesion (rotado post-login).
 */
final class Csrf
{
    private const KEY = '_csrf_token';

    public static function token(): string
    {
        Session::start();
        $t = Session::get(self::KEY);
        if (!is_string($t) || $t === '') {
            $t = bin2hex(random_bytes(32));
            Session::set(self::KEY, $t);
        }
        return $t;
    }

    public static function validate(?string $provided): bool
    {
        if (!is_string($provided) || $provided === '') {
            return false;
        }
        Session::start();
        $stored = Session::get(self::KEY);
        return is_string($stored) && hash_equals($stored, $provided);
    }

    public static function rotate(): void
    {
        Session::start();
        Session::set(self::KEY, bin2hex(random_bytes(32)));
    }

    public static function input(): string
    {
        return '<input type="hidden" name="_csrf" value="' . htmlspecialchars(self::token(), ENT_QUOTES, 'UTF-8') . '">';
    }

    /**
     * Usar en handlers POST/PUT/DELETE; aborta con 419 si falla.
     */
    public static function requireValid(): void
    {
        $provided = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        if (!self::validate($provided)) {
            http_response_code(419);
            header('Content-Type: text/plain; charset=utf-8');
            echo '419 - CSRF token invalido. Volve atras y reintenta.';
            exit;
        }
    }
}
