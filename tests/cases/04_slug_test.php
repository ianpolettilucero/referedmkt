<?php
TestRunner::group('slugify', function () {

    TestRunner::run('basico', function () {
        assert_eq('hola-mundo', slugify('Hola Mundo'));
    });

    TestRunner::run('acentos y eñe', function () {
        assert_eq('cancion-en-espanol', slugify('Canción en Español'));
    });

    TestRunner::run('puntuacion', function () {
        assert_eq('como-estas', slugify('¿Cómo estás?'));
    });

    TestRunner::run('multiples espacios', function () {
        assert_eq('uno-dos-tres', slugify('uno   dos   tres'));
    });

    TestRunner::run('strip leading/trailing dashes', function () {
        assert_eq('foo-bar', slugify('---foo bar---'));
    });

    TestRunner::run('vacio', function () {
        assert_eq('item', slugify(''));
        assert_eq('item', slugify('!!!'));
    });
});
