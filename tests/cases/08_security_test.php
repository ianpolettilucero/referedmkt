<?php
use Core\Security;

TestRunner::group('Security', function () {

    TestRunner::run('isValidIp acepta IPv4', function () {
        assert_true(Security::isValidIp('1.2.3.4'));
        assert_true(Security::isValidIp('192.168.1.1'));
        assert_true(Security::isValidIp('8.8.8.8'));
    });

    TestRunner::run('isValidIp acepta IPv6', function () {
        assert_true(Security::isValidIp('::1'));
        assert_true(Security::isValidIp('2001:db8::1'));
        assert_true(Security::isValidIp('fe80::1ff:fe23:4567:890a'));
    });

    TestRunner::run('isValidIp rechaza basura', function () {
        assert_false(Security::isValidIp(''));
        assert_false(Security::isValidIp('not-an-ip'));
        assert_false(Security::isValidIp('999.999.999.999'));
        assert_false(Security::isValidIp('1.2.3'));
        assert_false(Security::isValidIp('<script>'));
        assert_false(Security::isValidIp('; DROP TABLE users;'));
    });

    TestRunner::run('getClientIp prefiere CF-Connecting-IP', function () {
        $_SERVER['HTTP_CF_CONNECTING_IP'] = '9.9.9.9';
        $_SERVER['HTTP_X_FORWARDED_FOR']  = '8.8.8.8';
        $_SERVER['REMOTE_ADDR']           = '1.2.3.4';
        assert_eq('9.9.9.9', Security::getClientIp());
        unset($_SERVER['HTTP_CF_CONNECTING_IP']);
    });

    TestRunner::run('getClientIp fallback a X-Forwarded-For primera IP', function () {
        unset($_SERVER['HTTP_CF_CONNECTING_IP']);
        $_SERVER['HTTP_X_FORWARDED_FOR'] = '7.7.7.7, 8.8.8.8, 9.9.9.9';
        $_SERVER['REMOTE_ADDR']          = '1.2.3.4';
        assert_eq('7.7.7.7', Security::getClientIp());
        unset($_SERVER['HTTP_X_FORWARDED_FOR']);
    });

    TestRunner::run('getClientIp fallback a REMOTE_ADDR', function () {
        unset($_SERVER['HTTP_CF_CONNECTING_IP']);
        unset($_SERVER['HTTP_X_FORWARDED_FOR']);
        $_SERVER['REMOTE_ADDR'] = '5.6.7.8';
        assert_eq('5.6.7.8', Security::getClientIp());
    });

    TestRunner::run('getClientIp devuelve 0.0.0.0 si invalida', function () {
        unset($_SERVER['HTTP_CF_CONNECTING_IP']);
        unset($_SERVER['HTTP_X_FORWARDED_FOR']);
        $_SERVER['REMOTE_ADDR'] = 'basura-total';
        assert_eq('0.0.0.0', Security::getClientIp());
    });

    TestRunner::run('getClientIp normaliza ::1 a 127.0.0.1', function () {
        unset($_SERVER['HTTP_CF_CONNECTING_IP']);
        unset($_SERVER['HTTP_X_FORWARDED_FOR']);
        $_SERVER['REMOTE_ADDR'] = '::1';
        assert_eq('127.0.0.1', Security::getClientIp());
    });

    TestRunner::run('constantes de threshold son razonables', function () {
        assert_true(Security::LOGIN_FAIL_THRESHOLD >= 2 && Security::LOGIN_FAIL_THRESHOLD <= 10);
        assert_true(Security::LOGIN_WINDOW_MIN >= 5 && Security::LOGIN_WINDOW_MIN <= 60);
        assert_true(Security::DEFAULT_BAN_HOURS >= 1);
    });
});
