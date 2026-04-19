<?php
use Core\Site;

TestRunner::group('Site::normalizeHost', function () {

    TestRunner::run('lowercase', function () {
        assert_eq('foo.com', Site::normalizeHost('FOO.com'));
    });

    TestRunner::run('strip puerto', function () {
        assert_eq('foo.com', Site::normalizeHost('foo.com:8080'));
    });

    TestRunner::run('strip www', function () {
        assert_eq('foo.com', Site::normalizeHost('www.foo.com'));
    });

    TestRunner::run('combinacion', function () {
        assert_eq('foo.com', Site::normalizeHost('WWW.FOO.COM:443'));
    });

    TestRunner::run('host vacio', function () {
        assert_eq('', Site::normalizeHost(''));
    });
});
