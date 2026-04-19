<?php
/**
 * App-wide configuration. Valores sensibles deben cargarse desde .env (no commiteado).
 * En Hostinger definir variables de entorno o incluir un config.local.php sin committear.
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
        'salt'  => getenv('APP_SALT')  ?: 'change-me-in-env',
        'tz'    => getenv('APP_TZ')    ?: 'UTC',
    ],
    'db' => [
        'host'    => getenv('DB_HOST')    ?: 'localhost',
        'port'    => (int)(getenv('DB_PORT') ?: 3306),
        'name'    => getenv('DB_NAME')    ?: 'referedmkt',
        'user'    => getenv('DB_USER')    ?: 'root',
        'pass'    => getenv('DB_PASS')    ?: '',
        'charset' => 'utf8mb4',
    ],
    'paths' => [
        'root'    => APP_ROOT,
        'themes'  => APP_ROOT . '/themes',
        'uploads' => APP_ROOT . '/public/uploads',
    ],
];
