<?php
/**
 * Configuracion de entorno. Lo publico del sitio (dominio, nombre, tracking IDs,
 * textos) vive en content/site.php; aca solo queda lo que cambia entre entornos.
 *
 * Valores sensibles se cargan desde .env, que no se commitea.
 */

if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

// Carga simple de .env sin depender de phpdotenv (lo sumamos despues si hace falta).
$envFile = APP_ROOT . '/.env';
if (is_readable($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        if ($line[0] === '#' || strpos($line, '=') === false) {
            continue;
        }
        [$k, $v] = array_map('trim', explode('=', $line, 2));
        $v = trim($v, "\"'");
        if (getenv($k) === false) {
            putenv("$k=$v");
            $_ENV[$k] = $v;
        }
    }
}

return [
    'app' => [
        'env'   => getenv('APP_ENV')   ?: 'production',
        'debug' => filter_var(getenv('APP_DEBUG') ?: 'false', FILTER_VALIDATE_BOOLEAN),
        'tz'    => getenv('APP_TZ')    ?: 'UTC',
    ],
    'paths' => [
        'root'    => APP_ROOT,
        'content' => APP_ROOT . '/content',
        'themes'  => APP_ROOT . '/themes',
        'cache'   => APP_ROOT . '/var',
    ],
];
