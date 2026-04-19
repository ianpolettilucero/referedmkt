<?php
/**
 * Bootstrap de tests. NO inicializa Database (para que los tests unitarios
 * no requieran MySQL). Tests que necesiten DB la booteen explicitamente.
 */

if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}

require APP_ROOT . '/core/Autoloader.php';
\Core\Autoloader::register();
\Core\Autoloader::addNamespace('Core',        APP_ROOT . '/core');
\Core\Autoloader::addNamespace('Models',      APP_ROOT . '/models');
\Core\Autoloader::addNamespace('Controllers', APP_ROOT . '/controllers');
\Core\Autoloader::addNamespace('Admin',       APP_ROOT . '/admin');

require APP_ROOT . '/core/helpers/functions.php';
require APP_ROOT . '/core/helpers/slug.php';
require __DIR__ . '/TestRunner.php';
