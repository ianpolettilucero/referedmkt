<?php
/**
 * Entry point CLI. Uso: php tests/run.php
 */

if (PHP_SAPI !== 'cli') {
    exit("CLI solamente.\n");
}

require __DIR__ . '/bootstrap.php';

foreach (glob(__DIR__ . '/cases/*.php') as $f) {
    require $f;
}

exit(TestRunner::summary());
