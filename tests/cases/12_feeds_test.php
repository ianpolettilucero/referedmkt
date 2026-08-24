<?php
use Controllers\LlmsController;
use Controllers\SitemapController;
use Core\Content;

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

    // llms-full.txt publicaba el Markdown crudo, y con el entraba cada diagrama
    // entero: coordenadas, rellenos y anchos de trazo. Son cientos de lineas de
    // markup compitiendo por la ventana de contexto del modelo que lo lee. Se
    // aplanan al aria-label, que por convencion del sitio describe el diagrama
    // con sus datos porque es lo que oye un lector de pantalla.
    $conDiagrama = function (string $md): string {
        seed_content();
        $datos = Content::load();
        $datos['articles']['vieja']['content'] = $md;
        Content::seed($datos);
        return $md;
    };

    TestRunner::run('llms-full.txt aplana el svg a su aria-label', function () use ($render, $conDiagrama) {
        $conDiagrama(
            "Antes.\n\n```svg\n<svg viewBox=\"0 0 10 10\" role=\"img\" "
            . "aria-label=\"Firewalls 21 por ciento y VPNs 8 por ciento\">\n"
            . "  <rect x=\"1\" y=\"2\" width=\"3\" height=\"4\" fill=\"currentColor\"/>\n"
            . "</svg>\n```\n\nDespues."
        );
        $txt = $render(fn () => (new LlmsController())->full());

        assert_contains('[Diagrama: Firewalls 21 por ciento y VPNs 8 por ciento]', $txt);
        assert_not_contains('<rect', $txt);
        assert_not_contains('viewBox', $txt);
        assert_contains('Antes.', $txt);
        assert_contains('Despues.', $txt);
    });

    TestRunner::run('un svg sin aria-label deja la marca de diagrama', function () use ($render, $conDiagrama) {
        $conDiagrama("Texto.\n\n```svg\n<svg viewBox=\"0 0 10 10\"><rect x=\"1\"/></svg>\n```");
        $txt = $render(fn () => (new LlmsController())->full());

        assert_contains('[Diagrama]', $txt);
        assert_not_contains('<rect', $txt);
    });

    // Un bloque de codigo comun no se toca: solo se aplana el lenguaje svg.
    TestRunner::run('llms-full.txt no toca los bloques de codigo normales', function () use ($render, $conDiagrama) {
        $conDiagrama("```powershell\nGet-MpComputerStatus\n```");
        $txt = $render(fn () => (new LlmsController())->full());

        assert_contains('Get-MpComputerStatus', $txt);
        assert_not_contains('[Diagrama', $txt);
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
