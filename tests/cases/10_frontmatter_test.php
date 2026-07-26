<?php
use Core\FrontMatter;

TestRunner::group('FrontMatter', function () {

    TestRunner::run('separa front-matter y cuerpo', function () {
        $r = FrontMatter::parse("---\ntitle: Hola\n---\n## Cuerpo\n\nTexto.");
        assert_eq('Hola', $r['data']['title']);
        assert_eq("## Cuerpo\n\nTexto.", $r['body']);
    });

    TestRunner::run('sin front-matter todo es cuerpo', function () {
        $r = FrontMatter::parse("## Solo markdown");
        assert_eq([], $r['data']);
        assert_eq('## Solo markdown', $r['body']);
    });

    TestRunner::run('front-matter sin cerrar es error', function () {
        $failed = false;
        try { FrontMatter::parse("---\ntitle: X\n## cuerpo"); }
        catch (\InvalidArgumentException $e) { $failed = true; }
        assert_true($failed, 'deberia lanzar');
    });

    TestRunner::run('linea invalida es error con numero de linea', function () {
        $failed = '';
        try { FrontMatter::parse("---\ntitle: X\nesto no es valido\n---\n"); }
        catch (\InvalidArgumentException $e) { $failed = $e->getMessage(); }
        assert_contains('linea 3', $failed);
    });

    TestRunner::run('tipos escalares', function () {
        $r = FrontMatter::parse(
            "---\ns: texto\ni: 42\nneg: -7\nf: 4.5\nt: true\nf2: false\ny: yes\nn: no\nnul: null\ntil: ~\nvacio:\n---\n"
        );
        $d = $r['data'];
        assert_eq('texto', $d['s']);
        assert_eq(42, $d['i']);
        assert_eq(-7, $d['neg']);
        assert_eq(4.5, $d['f']);
        assert_eq(true, $d['t']);
        assert_eq(false, $d['f2']);
        assert_eq(true, $d['y']);
        assert_eq(false, $d['n']);
        assert_eq(null, $d['nul']);
        assert_eq(null, $d['til']);
        assert_eq(null, $d['vacio']);
    });

    TestRunner::run('comillas permiten : y # adentro', function () {
        $r = FrontMatter::parse("---\na: \"Guia: como empezar # 1\"\nb: 'Otro: caso'\n---\n");
        assert_eq('Guia: como empezar # 1', $r['data']['a']);
        assert_eq('Otro: caso', $r['data']['b']);
    });

    TestRunner::run('valor sin comillas conserva : interno', function () {
        // "Bitdefender: analisis" es un title valido y no debe truncarse.
        $r = FrontMatter::parse("---\ntitle: Bitdefender: analisis 2026\n---\n");
        assert_eq('Bitdefender: analisis 2026', $r['data']['title']);
    });

    TestRunner::run('comentario al final de valor sin comillas se descarta', function () {
        $r = FrontMatter::parse("---\nrating: 4.5 # nota editorial\n---\n");
        assert_eq(4.5, $r['data']['rating']);
    });

    TestRunner::run('linea de comentario se ignora', function () {
        $r = FrontMatter::parse("---\n# esto es un comentario\ntitle: X\n---\n");
        assert_eq(['title' => 'X'], $r['data']);
    });

    TestRunner::run('lista inline', function () {
        $r = FrontMatter::parse("---\nproducts: [uno, dos, tres]\nvacia: []\n---\n");
        assert_eq(['uno', 'dos', 'tres'], $r['data']['products']);
        assert_eq([], $r['data']['vacia']);
    });

    TestRunner::run('lista inline respeta comas dentro de comillas', function () {
        $r = FrontMatter::parse("---\na: [\"uno, con coma\", dos]\n---\n");
        assert_eq(['uno, con coma', 'dos'], $r['data']['a']);
    });

    TestRunner::run('lista en bloque', function () {
        $r = FrontMatter::parse("---\npros:\n  - Deteccion alta\n  - Bajo consumo\ncons:\n  - Caro\n---\n");
        assert_eq(['Deteccion alta', 'Bajo consumo'], $r['data']['pros']);
        assert_eq(['Caro'], $r['data']['cons']);
    });

    TestRunner::run('lista en bloque seguida de otra clave', function () {
        $r = FrontMatter::parse("---\npros:\n  - A\n  - B\ntitle: Despues\n---\n");
        assert_eq(['A', 'B'], $r['data']['pros']);
        assert_eq('Despues', $r['data']['title']);
    });

    TestRunner::run('bloque literal | preserva saltos', function () {
        $r = FrontMatter::parse("---\nverdict: |\n  Linea uno.\n  Linea dos.\ntitle: X\n---\n");
        assert_eq("Linea uno.\nLinea dos.", $r['data']['verdict']);
        assert_eq('X', $r['data']['title']);
    });

    TestRunner::run('bloque plegado > colapsa saltos', function () {
        $r = FrontMatter::parse("---\nexcerpt: >\n  Una frase\n  cortada en dos.\n---\n");
        assert_eq('Una frase cortada en dos.', $r['data']['excerpt']);
    });

    TestRunner::run('bloque literal preserva linea vacia interna', function () {
        $r = FrontMatter::parse("---\nv: |\n  Parrafo uno.\n\n  Parrafo dos.\ntitle: X\n---\n");
        assert_eq("Parrafo uno.\n\nParrafo dos.", $r['data']['v']);
        assert_eq('X', $r['data']['title']);
    });

    TestRunner::run('cuerpo con --- interno no rompe', function () {
        // Un <hr> markdown en el cuerpo no debe cortar nada.
        $r = FrontMatter::parse("---\ntitle: X\n---\nUno\n\n---\n\nDos");
        assert_eq('X', $r['data']['title']);
        assert_contains('Uno', $r['body']);
        assert_contains('Dos', $r['body']);
    });

    TestRunner::run('acepta CRLF y BOM', function () {
        $r = FrontMatter::parse("\xEF\xBB\xBF---\r\ntitle: X\r\n---\r\nCuerpo");
        assert_eq('X', $r['data']['title']);
        assert_eq('Cuerpo', $r['body']);
    });

    TestRunner::run('claves con guion y underscore', function () {
        $r = FrontMatter::parse("---\nmeta_title: A\nfeatured-image: /x.jpg\n---\n");
        assert_eq('A', $r['data']['meta_title']);
        assert_eq('/x.jpg', $r['data']['featured-image']);
    });

    TestRunner::run('fecha queda como string', function () {
        $r = FrontMatter::parse("---\npublished: 2026-01-15\nfull: 2026-01-15 10:30:00\n---\n");
        assert_eq('2026-01-15', $r['data']['published']);
        assert_eq('2026-01-15 10:30:00', $r['data']['full']);
    });

    TestRunner::run('escape de \\n en comillas dobles', function () {
        $r = FrontMatter::parse("---\na: \"uno\\ndos\"\n---\n");
        assert_eq("uno\ndos", $r['data']['a']);
    });

    TestRunner::run('front-matter vacio', function () {
        $r = FrontMatter::parse("---\n---\nCuerpo");
        assert_eq([], $r['data']);
        assert_eq('Cuerpo', $r['body']);
    });

    TestRunner::run('mapa en bloque (specs)', function () {
        $r = FrontMatter::parse(
            "---\nspecs:\n  Plataformas: Windows, macOS\n  Consola: Cloud\nname: X\n---\n"
        );
        assert_eq(['Plataformas' => 'Windows, macOS', 'Consola' => 'Cloud'], $r['data']['specs']);
        assert_eq('X', $r['data']['name']);
    });

    TestRunner::run('mapa en bloque admite claves con espacios y acentos', function () {
        $r = FrontMatter::parse("---\nspecs:\n  Sistema operativo: Linux\n  Precio máximo: 100\n---\n");
        assert_eq('Linux', $r['data']['specs']['Sistema operativo']);
        assert_eq(100, $r['data']['specs']['Precio máximo']);
    });

    TestRunner::run('lista y mapa no se confunden', function () {
        $r = FrontMatter::parse("---\npros:\n  - Uno\nspecs:\n  A: B\n---\n");
        assert_eq(['Uno'], $r['data']['pros']);
        assert_eq(['A' => 'B'], $r['data']['specs']);
    });

    TestRunner::run('clave sin valor ni bloque queda null', function () {
        $r = FrontMatter::parse("---\navatar_url:\nname: X\n---\n");
        assert_eq(null, $r['data']['avatar_url']);
        assert_eq('X', $r['data']['name']);
    });

    TestRunner::run('numero gigante queda string', function () {
        $r = FrontMatter::parse("---\nid: 999999999999999999999999\n---\n");
        assert_eq('999999999999999999999999', $r['data']['id']);
    });
});
