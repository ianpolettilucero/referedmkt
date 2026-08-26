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

    // El sitemap de noticias tiene reglas propias de Google: solo articulos de
    // las ultimas 48 horas y solo notas. Las tres cosas que pueden degradarse
    // en silencio son que se cuele una guia, que quede una nota vieja, o que el
    // XML deje de parsear por un titulo con un caracter especial.
    $conNota = function (array $rows) {
        seed_content();
        $datos = Content::load();
        foreach ($rows as $slug => $r) { $datos['articles'][$slug] = array_merge([
            'site_id' => 1, 'status' => 'published', 'article_type' => 'news',
            'subtitle' => null, 'excerpt' => null, 'content' => '', 'content_html' => '',
            'category_id' => null, 'author_id' => null, 'related_product_ids' => [],
            'rating' => null, 'verdict' => null, 'pros' => null, 'cons' => null,
            'updated_at' => date('Y-m-d H:i:s'), 'slug' => $slug,
        ], $r); }
        Content::seed($datos);
    };

    TestRunner::run('el sitemap de noticias solo trae las ultimas 48 horas', function () use ($render, $conNota) {
        $conNota([
            'hoy'   => ['id' => 90, 'title' => 'De hoy',  'published_at' => date('Y-m-d H:i:s', time() - 3600)],
            'vieja' => ['id' => 91, 'title' => 'De hace una semana', 'published_at' => date('Y-m-d H:i:s', time() - 7 * 86400)],
        ]);
        $xml = $render(fn () => (new SitemapController())->news());

        assert_contains('/noticia/hoy', $xml);
        assert_not_contains('/noticia/vieja', $xml);
    });

    TestRunner::run('el sitemap de noticias excluye lo que no es nota', function () use ($render, $conNota) {
        $conNota([
            'nota' => ['id' => 92, 'title' => 'Una nota', 'published_at' => date('Y-m-d H:i:s', time() - 3600)],
            'guia' => ['id' => 93, 'title' => 'Una guia', 'article_type' => 'guide',
                       'published_at' => date('Y-m-d H:i:s', time() - 3600)],
        ]);
        $xml = $render(fn () => (new SitemapController())->news());

        assert_contains('/noticia/nota', $xml);
        assert_not_contains('/guia/guia', $xml);
    });

    TestRunner::run('el sitemap de noticias es XML bien formado', function () use ($render, $conNota) {
        // Un titulo con & y comillas: si no se escapa, el feed entero no parsea.
        $conNota(['rara' => ['id' => 94, 'title' => 'Fallas en A & B: "criticas" <hoy>',
                             'published_at' => date('Y-m-d H:i:s', time() - 3600)]]);
        $xml = $render(fn () => (new SitemapController())->news());

        $prev = libxml_use_internal_errors(true);
        $doc  = simplexml_load_string($xml);
        $errs = libxml_get_errors();
        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        assert_true($doc !== false, 'no parsea: ' . ($errs[0]->message ?? 'sin detalle'));
        assert_contains('news:publication_date', $xml);
        assert_contains('news:language', $xml);
    });

    // Sin notas recientes el archivo tiene que seguir siendo XML valido y
    // vacio. Es el estado correcto de un dia sin publicar, no un error.
    TestRunner::run('sin notas recientes el sitemap queda vacio pero valido', function () use ($render, $conNota) {
        $conNota(['antigua' => ['id' => 95, 'title' => 'Vieja',
                                'published_at' => date('Y-m-d H:i:s', time() - 30 * 86400)]]);
        $xml = $render(fn () => (new SitemapController())->news());

        $prev = libxml_use_internal_errors(true);
        $doc  = simplexml_load_string($xml);
        libxml_clear_errors();
        libxml_use_internal_errors($prev);

        assert_true($doc !== false, 'un sitemap vacio tiene que seguir parseando');
        assert_not_contains('<url>', $xml);
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
