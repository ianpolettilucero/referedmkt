<?php
use Core\Upload;

TestRunner::group('Upload', function () {

    TestRunner::run('whitelist de MIME no acepta text/html', function () {
        assert_false(isset(Upload::ALLOWED['text/html']));
        assert_false(isset(Upload::ALLOWED['application/x-php']));
    });

    TestRunner::run('MIME permitidos tienen extension forzada', function () {
        assert_eq('jpg',  Upload::ALLOWED['image/jpeg']);
        assert_eq('png',  Upload::ALLOWED['image/png']);
        assert_eq('webp', Upload::ALLOWED['image/webp']);
        assert_eq('svg',  Upload::ALLOWED['image/svg+xml']);
    });

    TestRunner::run('detectMime reconoce PNG real', function () {
        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
        $tmp = tempnam(sys_get_temp_dir(), 'refmkt-test-');
        file_put_contents($tmp, $png);
        $mime = Upload::detectMime($tmp);
        unlink($tmp);
        assert_eq('image/png', $mime);
    });

    TestRunner::run('detectMime rechaza texto plano como imagen', function () {
        $tmp = tempnam(sys_get_temp_dir(), 'refmkt-test-');
        file_put_contents($tmp, "<?php echo 'hacked'; ?>");
        $mime = Upload::detectMime($tmp);
        unlink($tmp);
        assert_false(isset(Upload::ALLOWED[$mime]));
    });

    TestRunner::run('delete bloquea path traversal', function () {
        // Path fuera de /public/uploads no se puede borrar.
        assert_false(Upload::delete('../../etc/passwd'));
        assert_false(Upload::delete('/etc/passwd'));
    });
});
