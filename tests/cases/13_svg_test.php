<?php
use Core\Markdown;
use Core\Svg;

/**
 * El bloque ```svg es la unica via por la que entra markup sin escapar al
 * HTML del sitio, asi que es la que mas tests merece. La mitad de estos son
 * intentos de ataque: si alguno pasa, hay XSS.
 */
TestRunner::group('SVG', function () {

    TestRunner::run('un diagrama valido se emite', function () {
        $h = Markdown::toHtml("```svg\n<svg viewBox=\"0 0 10 10\"><rect x=\"1\" y=\"1\" width=\"8\" height=\"8\"/></svg>\n```");
        assert_contains('<figure class="article-diagram">', $h);
        assert_contains('<rect', $h);
        assert_contains('viewBox="0 0 10 10"', $h);
    });

    // --- Intentos de inyeccion -------------------------------------------

    TestRunner::run('script adentro del svg se elimina', function () {
        $out = Svg::sanitize('<svg viewBox="0 0 10 10"><script>alert(1)</script><rect/></svg>', 'd1');
        assert_true($out !== null, 'deberia sanear, no rechazar');
        assert_not_contains('script', $out);
        assert_not_contains('alert', $out);
    });

    TestRunner::run('atributos on* se eliminan', function () {
        $out = Svg::sanitize('<svg viewBox="0 0 10 10"><rect onload="alert(1)" onclick="x()"/></svg>', 'd1');
        assert_not_contains('onload', $out);
        assert_not_contains('onclick', $out);
        assert_not_contains('alert', $out);
    });

    TestRunner::run('foreignObject se elimina con su contenido', function () {
        $out = Svg::sanitize('<svg viewBox="0 0 10 10"><foreignObject><body xmlns="http://www.w3.org/1999/xhtml">hola</body></foreignObject></svg>', 'd1');
        assert_not_contains('foreignObject', $out);
        assert_not_contains('hola', $out);
    });

    TestRunner::run('href y xlink:href se eliminan siempre', function () {
        $out = Svg::sanitize('<svg xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 10 10"><rect href="javascript:alert(1)" xlink:href="http://x.com/a.svg"/></svg>', 'd1');
        assert_not_contains('javascript', $out);
        assert_not_contains('x.com', $out);
    });

    TestRunner::run('image y use se eliminan', function () {
        $out = Svg::sanitize('<svg viewBox="0 0 10 10"><image href="http://x.com/a.png"/><use href="#x"/><rect/></svg>', 'd1');
        assert_not_contains('<image', $out);
        assert_not_contains('<use', $out);
    });

    TestRunner::run('animate se elimina: podria mutar atributos ya saneados', function () {
        $out = Svg::sanitize('<svg viewBox="0 0 10 10"><rect><animate attributeName="href" to="javascript:alert(1)"/></rect></svg>', 'd1');
        assert_not_contains('animate', $out);
        assert_not_contains('javascript', $out);
    });

    TestRunner::run('DOCTYPE y ENTITY se rechazan de plano (XXE)', function () {
        $xxe = '<!DOCTYPE svg [<!ENTITY xxe SYSTEM "file:///etc/passwd">]><svg viewBox="0 0 10 10"><text>&xxe;</text></svg>';
        assert_true(Svg::sanitize($xxe, 'd1') === null, 'un DOCTYPE tiene que rechazar el bloque entero');
    });

    TestRunner::run('el elemento style se elimina: CSS puede traer url() remoto', function () {
        $out = Svg::sanitize('<svg viewBox="0 0 10 10"><style>@import url(http://x.com/e.css);</style><rect/></svg>', 'd1');
        assert_not_contains('style', $out);
        assert_not_contains('x.com', $out);
    });

    TestRunner::run('url() remoto en fill se descarta, local se conserva', function () {
        $out = Svg::sanitize('<svg viewBox="0 0 10 10"><rect fill="url(http://x.com/e.svg#g)"/><circle fill="url(#grad)"/></svg>', 'd9');
        assert_not_contains('x.com', $out);
        assert_contains('url(#d9-grad)', $out);
    });

    // --- Comportamiento ---------------------------------------------------

    TestRunner::run('los id se namespacean para no chocar entre diagramas', function () {
        $out = Svg::sanitize('<svg viewBox="0 0 10 10"><defs><linearGradient id="grad"><stop offset="0"/></linearGradient></defs><rect fill="url(#grad)"/></svg>', 'd7');
        assert_contains('id="d7-grad"', $out);
        assert_contains('url(#d7-grad)', $out);
        assert_not_contains('id="grad"', $out);
    });

    TestRunner::run('una raiz que no es svg se rechaza', function () {
        assert_true(Svg::sanitize('<div><svg viewBox="0 0 1 1"/></div>', 'd1') === null);
    });

    TestRunner::run('XML mal formado se rechaza en vez de emitirse a medias', function () {
        assert_true(Svg::sanitize('<svg viewBox="0 0 1 1"><rect>', 'd1') === null);
    });

    // Falla cerrado: si no se puede sanear, se muestra como codigo escapado.
    TestRunner::run('un svg irrecuperable cae a bloque de codigo escapado', function () {
        $h = Markdown::toHtml("```svg\n<svg><rect>\n```");
        assert_not_contains('<figure', $h);
        assert_contains('<pre><code', $h);
        assert_contains('&lt;svg&gt;', $h);
    });

    TestRunner::run('dos diagramas en un documento no comparten prefijo de id', function () {
        $h = Markdown::toHtml(
            "```svg\n<svg viewBox=\"0 0 1 1\"><defs><linearGradient id=\"g\"><stop offset=\"0\"/></linearGradient></defs></svg>\n```\n\n"
            . "```svg\n<svg viewBox=\"0 0 1 1\"><defs><linearGradient id=\"g\"><stop offset=\"0\"/></linearGradient></defs></svg>\n```"
        );
        preg_match_all('~id="(d\d+)-g"~', $h, $m);
        assert_true(count($m[1]) === 2, 'deberia haber dos diagramas');
        assert_true($m[1][0] !== $m[1][1], 'los prefijos tienen que ser distintos');
    });

    TestRunner::run('el texto del diagrama se escapa', function () {
        $out = Svg::sanitize('<svg viewBox="0 0 10 10"><text>a &lt; b &amp; c</text></svg>', 'd1');
        assert_not_contains('<b', $out);
        assert_contains('&lt;', $out);
    });
});
