<?php
/**
 * Bootstrap compartido por el frontend publico y los CLI.
 * - Define APP_ROOT
 * - Registra autoloader
 * - Carga config
 * - Configura timezone y error reporting segun env
 *
 * No hay base de datos: el contenido se lee de content/ via Core\Content.
 */

if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

require APP_ROOT . '/core/Autoloader.php';

\Core\Autoloader::register();
\Core\Autoloader::addNamespace('Core',        APP_ROOT . '/core');
\Core\Autoloader::addNamespace('Models',      APP_ROOT . '/models');
\Core\Autoloader::addNamespace('Controllers', APP_ROOT . '/controllers');

/** @var array $config */
$config = require APP_ROOT . '/config/config.php';

date_default_timezone_set($config['app']['tz']);

if ($config['app']['debug']) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(E_ALL & ~E_DEPRECATED);
    ini_set('display_errors', '0');
}

require APP_ROOT . '/core/helpers/functions.php';
require APP_ROOT . '/core/helpers/slug.php';

return $config;
