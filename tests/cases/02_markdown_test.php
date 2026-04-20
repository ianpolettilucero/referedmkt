<?php
use Core\Markdown;

TestRunner::group('Markdown', function () {

    TestRunner::run('headings', function () {
        $h = Markdown::toHtml("# Titulo\n\n## Sub");
        assert_contains('<h1>Titulo</h1>', $h);
        assert_contains('<h2>Sub</h2>', $h);
    });

    TestRunner::run('parrafos', function () {
        $h = Markdown::toHtml("Hola mundo.\n\nOtro parrafo.");
        assert_contains('<p>Hola mundo.</p>', $h);
        assert_contains('<p>Otro parrafo.</p>', $h);
    });

    TestRunner::run('bold y italic', function () {
        $h = Markdown::toHtml("Esto es **muy** *importante*.");
        assert_contains('<strong>muy</strong>', $h);
        assert_contains('<em>importante</em>', $h);
    });

    TestRunner::run('code inline', function () {
        $h = Markdown::toHtml("Usa `npm install`.");
        assert_contains('<code>npm install</code>', $h);
    });

    TestRunner::run('fenced code block', function () {
        $h = Markdown::toHtml("```bash\necho hola\n```");
        assert_contains('<pre><code class="language-bash">echo hola</code></pre>', $h);
    });

    TestRunner::run('link interno sin nofollow', function () {
        $h = Markdown::toHtml('[productos](/productos)');
        assert_contains('<a href="/productos">productos</a>', $h);
    });

    TestRunner::run('link externo lleva rel nofollow noopener', function () {
        $h = Markdown::toHtml('[ejemplo](https://example.com)');
        assert_contains('rel="nofollow noopener"', $h);
        assert_contains('target="_blank"', $h);
    });

    TestRunner::run('XSS: javascript: queda neutralizado', function () {
        $h = Markdown::toHtml('[xss](javascript:alert(1))');
        assert_not_contains('javascript:', $h);
    });

    TestRunner::run('XSS: input HTML escapado', function () {
        $h = Markdown::toHtml('<script>alert(1)</script>');
        assert_not_contains('<script>', $h);
        assert_contains('&lt;script&gt;', $h);
    });

    TestRunner::run('lista ul', function () {
        $h = Markdown::toHtml("- uno\n- dos\n- tres");
        assert_contains('<ul>', $h);
        assert_contains('<li>uno</li>', $h);
    });

    TestRunner::run('lista ol', function () {
        $h = Markdown::toHtml("1. uno\n2. dos");
        assert_contains('<ol>', $h);
        assert_contains('<li>uno</li>', $h);
    });

    TestRunner::run('blockquote', function () {
        $h = Markdown::toHtml("> Citado");
        assert_contains('<blockquote>', $h);
        assert_contains('Citado', $h);
    });

    TestRunner::run('imagenes con loading lazy', function () {
        $h = Markdown::toHtml('![alt](https://x/y.png)');
        assert_contains('loading="lazy"', $h);
        assert_contains('alt="alt"', $h);
    });

    TestRunner::run('hr', function () {
        $h = Markdown::toHtml("---");
        assert_contains('<hr>', $h);
    });

    TestRunner::run('tabla basica', function () {
        $md = "| Nombre | Precio |\n|---|---|\n| Bitdefender | 50 |\n| ESET | 40 |";
        $h = Markdown::toHtml($md);
        assert_contains('<table>', $h);
        assert_contains('<th>Nombre</th>', $h);
        assert_contains('<th>Precio</th>', $h);
        assert_contains('<td>Bitdefender</td>', $h);
        assert_contains('<td>50</td>', $h);
    });

    TestRunner::run('tabla con alineamiento', function () {
        $md = "| L | C | R |\n|:---|:---:|---:|\n| a | b | c |";
        $h = Markdown::toHtml($md);
        assert_contains('style="text-align:left"', $h);
        assert_contains('style="text-align:center"', $h);
        assert_contains('style="text-align:right"', $h);
    });

    TestRunner::run('tabla con bold en celdas', function () {
        $md = "| col |\n|---|\n| **hola** |";
        $h = Markdown::toHtml($md);
        assert_contains('<strong>hola</strong>', $h);
    });
});
