<?php
use Controllers\LlmsController;
use Controllers\SitemapController;

/**
 * sitemap.xml y llms.txt son los dos archivos por los que el sitio se explica
 * ante buscadores y modelos. Nadie los mira a ojo, asi que si se degradan lo
 * hacen en silencio: estos tests fijan las tres cosas que ya se rompieron una
 * vez.
 *
 * Se reusa el seed de 11_content_test.php, que ya arma un sitio sintetico sin
 * tocar content/ ni el disco.
 */
TestRunner::group('Feeds (sitemap y llms)', function () {

    // Corre el controller capturando lo que escribe. Los controllers llaman a
    // header() y en CLI el runner ya imprimio, asi que PHP avisa "headers
    // already sent". Es ruido esperado y propio de correr un controller HTTP
    // fuera de una request: se silencia solo durante el render, no en general.
    $render = function (callable $fn): string {
        $antes = error_reporting();
        error_reporting($antes & ~E_WARNING);
        ob_start();
        try { $fn(); } finally { $out = ob_get_clean(); error_reporting($antes); }
        return $out;
    };

    // Cada <url> del sitemap tiene que traer <lastmod>. La home y los listados
    // eran las unicas cinco sin el, y son las que mas cambian: su contenido es
    // la lista de lo ultimo publicado. Sin lastmod, Google no tiene senal para
    // volver a rastrearlas.
    TestRunner::run('todas las urls del sitemap traen lastmod', function () use ($render) {
        seed_content();
        $xml = $render(fn () => (new SitemapController())->index());

        preg_match_all('~<url>(.*?)</url>~s', $xml, $m);
        assert_true(count($m[1]) > 0, 'el sitemap salio vacio');

        $sin = [];
        foreach ($m[1] as $bloque) {
            if (strpos($bloque, '<lastmod>') === false) {
                preg_match('~<loc>(.*?)</loc>~', $bloque, $loc);
                $sin[] = $loc[1] ?? '?';
            }
        }
        assert_true($sin === [], 'urls sin lastmod: ' . implode(', ', $sin));
    });

    TestRunner::run('el sitemap es XML bien formado', function () use ($render) {
        seed_content();
        $xml = $render(fn () => (new SitemapController())->index());

        $prev = libxml_use_internal_errors(true);
        $doc  = simplexml_load_string($xml);
        $errs = libxml_get_errors();
        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        assert_true($doc !== false, 'no parsea: ' . ($errs[0]->message ?? 'sin detalle'));
    });

    // Las descripciones de categoria y producto son Markdown crudo. strip_tags()
    // sacaba el HTML pero no el Markdown, asi que llms.txt publicaba entradas
    // como "Hostings y Cloud: ## Servicios de Hosting El **hosting** es...".
    TestRunner::run('llms.txt no filtra sintaxis de markdown', function () use ($render) {
        seed_content();
        $txt = $render(fn () => (new LlmsController())->index());

        assert_true(
            !preg_match('~\):\s*#~', $txt),
            'quedo un encabezado markdown dentro de una descripcion'
        );
        assert_not_contains('**', $txt);
    });

    // El esquema se deducia del request. En produccion daba bien, pero si el
    // proxy dejaba de mandar X-Forwarded-Proto el archivo empezaba a publicar
    // URLs http en silencio, justo el que consumen los crawlers de LLM.
    TestRunner::run('llms.txt siempre emite https', function () use ($render) {
        seed_content();
        $txt = $render(fn () => (new LlmsController())->index());

        assert_not_contains('http://example.com', $txt);
        assert_contains('https://example.com', $txt);
    });
});
