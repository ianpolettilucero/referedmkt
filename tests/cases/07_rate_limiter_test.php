<?php
use Core\RateLimiter;

TestRunner::group('RateLimiter', function () {

    TestRunner::run('ipHash es determinista con misma IP+salt', function () {
        putenv('APP_SALT=test-salt-1');
        $_SERVER['REMOTE_ADDR'] = '1.2.3.4';
        $h1 = RateLimiter::ipHash();
        $h2 = RateLimiter::ipHash();
        assert_eq($h1, $h2);
        assert_eq(64, strlen($h1));
    });

    TestRunner::run('ipHash cambia con IP distinta', function () {
        putenv('APP_SALT=test-salt-1');
        $_SERVER['REMOTE_ADDR'] = '1.2.3.4';
        $h1 = RateLimiter::ipHash();
        $_SERVER['REMOTE_ADDR'] = '5.6.7.8';
        $h2 = RateLimiter::ipHash();
        assert_true($h1 !== $h2);
    });

    TestRunner::run('ipHash cambia con salt distinta', function () {
        $_SERVER['REMOTE_ADDR'] = '1.2.3.4';
        putenv('APP_SALT=salt-A');
        $h1 = RateLimiter::ipHash();
        putenv('APP_SALT=salt-B');
        $h2 = RateLimiter::ipHash();
        assert_true($h1 !== $h2);
    });

    TestRunner::run('ipHash toma primera IP de X-Forwarded-For', function () {
        putenv('APP_SALT=test-salt-1');
        $_SERVER['REMOTE_ADDR'] = '10.0.0.1';
        $_SERVER['HTTP_X_FORWARDED_FOR'] = '9.9.9.9, 10.0.0.1';
        $h1 = RateLimiter::ipHash();
        unset($_SERVER['HTTP_X_FORWARDED_FOR']);
        $_SERVER['REMOTE_ADDR'] = '9.9.9.9';
        $h2 = RateLimiter::ipHash();
        assert_eq($h1, $h2);
    });

    TestRunner::run('constantes definidas con umbrales razonables', function () {
        assert_true(RateLimiter::MAX_PER_IP    >= 3 && RateLimiter::MAX_PER_IP    <= 20);
        assert_true(RateLimiter::MAX_PER_EMAIL >= RateLimiter::MAX_PER_IP);
        assert_true(RateLimiter::WINDOW_MIN    >= 5);
    });
});
