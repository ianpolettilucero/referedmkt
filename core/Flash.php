<?php
namespace Core;

/**
 * Mensajes flash de una sola lectura (post-redirect-get).
 */
final class Flash
{
    private const KEY = '_flash';

    public static function add(string $type, string $message): void
    {
        Session::start();
        $bag = Session::get(self::KEY, []);
        $bag[] = ['type' => $type, 'message' => $message];
        Session::set(self::KEY, $bag);
    }

    public static function success(string $message): void { self::add('success', $message); }
    public static function error(string $message): void   { self::add('error', $message); }
    public static function info(string $message): void    { self::add('info', $message); }

    /**
     * Consume y devuelve todos los mensajes acumulados.
     * @return array<int, array{type:string, message:string}>
     */
    public static function consume(): array
    {
        Session::start();
        $bag = Session::get(self::KEY, []);
        Session::forget(self::KEY);
        return is_array($bag) ? $bag : [];
    }
}
