<?php
use Core\Faq;

TestRunner::group('Faq', function () {

    TestRunner::run('extrae preguntas de la seccion FAQ', function () {
        $md = "## Intro\nTexto.\n\n## Preguntas frecuentes\n\n"
            . "### ¿Sirve para 10 personas?\nSi, el plan base cubre hasta 25 endpoints.\n\n"
            . "### ¿Cuanto cuesta?\nDesde USD 49 por año.\n";
        $faqs = Faq::extract($md);
        assert_eq(2, count($faqs));
        assert_eq('¿Sirve para 10 personas?', $faqs[0]['question']);
        assert_contains('25 endpoints', $faqs[0]['answer']);
        assert_eq('¿Cuanto cuesta?', $faqs[1]['question']);
    });

    TestRunner::run('acepta variantes del titulo (FAQ, Preguntas comunes)', function () {
        foreach (['## FAQ', '## FAQs', '## Preguntas comunes', '## preguntas frecuentes'] as $h) {
            $faqs = Faq::extract("$h\n### P\nR\n");
            assert_eq(1, count($faqs), "fallo con heading: $h");
        }
    });

    TestRunner::run('ignora h3 fuera de la seccion FAQ', function () {
        $md = "## Analisis\n### Rendimiento\nBueno.\n\n## Preguntas frecuentes\n### ¿Y el precio?\nCaro.\n";
        $faqs = Faq::extract($md);
        assert_eq(1, count($faqs));
        assert_eq('¿Y el precio?', $faqs[0]['question']);
    });

    TestRunner::run('el proximo h2 cierra la seccion', function () {
        $md = "## Preguntas frecuentes\n### P1\nR1\n\n## Conclusion\n### No es pregunta\nTexto.\n";
        $faqs = Faq::extract($md);
        assert_eq(1, count($faqs));
        assert_eq('P1', $faqs[0]['question']);
    });

    TestRunner::run('descarta preguntas sin respuesta', function () {
        // Un item vacio invalida el FAQPage entero para Google.
        $md = "## Preguntas frecuentes\n### Sin respuesta\n\n### Con respuesta\nAca si.\n";
        $faqs = Faq::extract($md);
        assert_eq(1, count($faqs));
        assert_eq('Con respuesta', $faqs[0]['question']);
    });

    TestRunner::run('limpia markdown inline de pregunta y respuesta', function () {
        $md = "## Preguntas frecuentes\n### ¿Sirve con **SSO**?\n"
            . "Si, mira la [doc oficial](https://example.com/doc) para `configurarlo`.\n";
        $faqs = Faq::extract($md);
        assert_eq('¿Sirve con SSO?', $faqs[0]['question']);
        assert_contains('doc oficial', $faqs[0]['answer']);
        assert_not_contains('https://example.com', $faqs[0]['answer']);
        assert_not_contains('`', $faqs[0]['answer']);
    });

    TestRunner::run('aplana bullets a texto corrido', function () {
        $md = "## Preguntas frecuentes\n### ¿Que incluye?\n- Antivirus\n- EDR\n- Consola\n";
        $faqs = Faq::extract($md);
        assert_contains('Antivirus EDR Consola', $faqs[0]['answer']);
        assert_not_contains('- Antivirus', $faqs[0]['answer']);
    });

    TestRunner::run('no interpreta headings dentro de un code fence', function () {
        $md = "## Preguntas frecuentes\n### ¿Como se configura?\n"
            . "Asi:\n```\n## no soy un heading\n### tampoco\n```\nListo.\n";
        $faqs = Faq::extract($md);
        assert_eq(1, count($faqs));
        assert_eq('¿Como se configura?', $faqs[0]['question']);
    });

    TestRunner::run('sin seccion FAQ devuelve vacio', function () {
        assert_eq([], Faq::extract("## Intro\nTexto\n### Sub\nMas texto\n"));
        assert_eq([], Faq::extract(''));
        assert_eq([], Faq::extract('   '));
    });

    TestRunner::run('trunca respuestas larguisimas', function () {
        $long = str_repeat('palabra ', 400); // ~3200 chars
        $faqs = Faq::extract("## Preguntas frecuentes\n### ¿Larga?\n$long\n");
        assert_eq(1, count($faqs));
        assert_true(mb_strlen($faqs[0]['answer']) <= 1000, 'respuesta no truncada');
        assert_contains('…', $faqs[0]['answer']);
    });
});
