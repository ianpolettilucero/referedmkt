<?php
use Core\Router;

TestRunner::group('Router', function () {

    TestRunner::run('match estatico', function () {
        $r = new Router();
        $hit = null;
        $r->get('/', function () use (&$hit) { $hit = 'home'; });
        $r->dispatch('GET', '/');
        assert_eq('home', $hit);
    });

    TestRunner::run('placeholder simple', function () {
        $r = new Router();
        $captured = null;
        $r->get('/producto/{slug}', function ($p) use (&$captured) { $captured = $p['slug']; });
        $r->dispatch('GET', '/producto/bitdefender-gravityzone');
        assert_eq('bitdefender-gravityzone', $captured);
    });

    TestRunner::run('placeholder con regex', function () {
        $r = new Router();
        $hit = null;
        $r->get('/items/{id:\d+}', function ($p) use (&$hit) { $hit = $p['id']; });
        $r->dispatch('GET', '/items/42');
        assert_eq('42', $hit);
    });

    TestRunner::run('regex no matchea no-numerico', function () {
        $r = new Router();
        $hit = false;
        $r->get('/items/{id:\d+}', function () use (&$hit) { $hit = true; });
        $r->setNotFound(function () {});
        ob_start();
        $r->dispatch('GET', '/items/abc');
        ob_end_clean();
        assert_false($hit);
    });

    TestRunner::run('grupo con prefix', function () {
        $r = new Router();
        $hit = null;
        $r->group('/admin', function (Router $r) use (&$hit) {
            $r->get('/dashboard', function () use (&$hit) { $hit = 'dash'; });
        });
        $r->dispatch('GET', '/admin/dashboard');
        assert_eq('dash', $hit);
    });

    TestRunner::run('HEAD cae en GET', function () {
        $r = new Router();
        $hit = null;
        $r->get('/', function () use (&$hit) { $hit = 'g'; });
        $r->dispatch('HEAD', '/');
        assert_eq('g', $hit);
    });

    TestRunner::run('404 default', function () {
        $r = new Router();
        $captured = null;
        $r->setNotFound(function ($path) use (&$captured) { $captured = $path; });
        $r->dispatch('GET', '/nope');
        assert_eq('/nope', $captured);
    });

    TestRunner::run('trailing slash normalizado', function () {
        $r = new Router();
        $hit = null;
        $r->get('/productos', function () use (&$hit) { $hit = true; });
        $r->dispatch('GET', '/productos/');
        assert_true((bool)$hit);
    });

    TestRunner::run('handler array [Class, method]', function () {
        $r = new Router();
        $r->get('/x', [TestsRouterDummyHandler::class, 'hit']);
        ob_start();
        $r->dispatch('GET', '/x');
        $out = ob_get_clean();
        assert_eq('OK', $out);
    });
});

class TestsRouterDummyHandler {
    public function hit(): void { echo 'OK'; }
}
