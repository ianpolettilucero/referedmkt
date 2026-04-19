<?php
namespace Core;

/**
 * Autoloader PSR-4 minimal.
 *
 * Namespaces registrados por defecto:
 *   Core\      -> core/
 *   Models\    -> models/
 *   Controllers\ -> controllers/
 *   Admin\     -> admin/
 */
final class Autoloader
{
    /** @var array<string, string> */
    private static array $map = [];

    public static function register(): void
    {
        spl_autoload_register([self::class, 'load']);
    }

    public static function addNamespace(string $prefix, string $baseDir): void
    {
        $prefix  = trim($prefix, '\\') . '\\';
        $baseDir = rtrim($baseDir, '/') . '/';
        self::$map[$prefix] = $baseDir;
    }

    public static function load(string $class): void
    {
        foreach (self::$map as $prefix => $baseDir) {
            if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
                continue;
            }
            $relative = substr($class, strlen($prefix));
            $file = $baseDir . str_replace('\\', '/', $relative) . '.php';
            if (is_file($file)) {
                require $file;
                return;
            }
        }
    }
}
