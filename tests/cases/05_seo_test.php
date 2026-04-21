<?php
use Core\SEO;

/**
 * Construye un Site fake sin DB. Usamos reflection porque el ctor de Site es privado.
 */
function make_fake_site(): \Core\Site
{
    $ref = new \ReflectionClass(\Core\Site::class);
    $site = $ref->newInstanceWithoutConstructor();
    $site->id = 1;
    $site->domain = 'example.com';
    $site->name = 'Example';
    $site->slug = 'example';
    $site->themeName = 'default';
    $site->primaryColor = null;
    $site->logoUrl = 'https://example.com/logo.png';
    $site->faviconUrl = null;
    $site->affiliateDisclosureText = null;
    $site->googleAnalyticsId = null;
    $site->googleSearchConsoleVerification = null;
    $site->defaultLanguage = 'es';
    $site->defaultCountry = 'AR';
    $site->metaTitleTemplate = '{title} | Example';
    $site->metaDescriptionTemplate = null;
    $site->active = true;

    // Forzar el singleton para que site_url() / Site::current() funcionen.
    $prop = $ref->getProperty('current');
    $prop->setAccessible(true);
    $prop->setValue(null, $site);

    $_SERVER['HTTP_HOST']   = 'example.com';
    $_SERVER['REQUEST_URI'] = '/';
    return $site;
}

TestRunner::group('SEO', function () {

    TestRunner::run('title aplica template', function () {
        $site = make_fake_site();
        $seo = new SEO($site);
        $seo->title('Mi pagina');
        $head = $seo->renderHead();
        assert_contains('<title>Mi pagina | Example</title>', $head);
    });

    TestRunner::run('title NO duplica cuando coincide con site name', function () {
        // Caso tipico: home del site, ->title($site->name) no deberia generar
        // "Example | Example" (template).
        $site = make_fake_site();
        $seo = new SEO($site);
        $seo->title('Example');
        $head = $seo->renderHead();
        assert_contains('<title>Example</title>', $head);
        assert_not_contains('Example | Example', $head);
    });

    TestRunner::run('title NO duplica case-insensitive', function () {
        $site = make_fake_site();
        $seo = new SEO($site);
        $seo->title('EXAMPLE'); // uppercase
        $head = $seo->renderHead();
        assert_not_contains('EXAMPLE | Example', $head);
        assert_not_contains('Example | EXAMPLE', $head);
    });

    TestRunner::run('meta description y canonical', function () {
        $site = make_fake_site();
        $seo = new SEO($site);
        $seo->title('X')->description('descripcion test')->canonical('/foo');
        $head = $seo->renderHead();
        assert_contains('<meta name="description" content="descripcion test">', $head);
        assert_contains('<link rel="canonical" href="', $head);
        assert_contains('/foo"', $head);
    });

    TestRunner::run('breadcrumb genera JSON-LD', function () {
        $site = make_fake_site();
        $seo = new SEO($site);
        $seo->breadcrumb([['Inicio', '/'], ['Productos', '/productos']]);
        $head = $seo->renderHead();
        assert_contains('"@type":"BreadcrumbList"', $head);
        assert_contains('"position":1', $head);
    });

    TestRunner::run('schemaProduct con rating', function () {
        $site = make_fake_site();
        $seo = new SEO($site);
        $seo->schemaProduct([
            'name' => 'Bitdefender', 'brand' => 'Bitdefender', 'slug' => 'bd',
            'description_short' => 'd', 'price_from' => 50, 'price_currency' => 'USD',
            'pricing_model' => 'yearly', 'rating' => 4.6, 'logo_url' => null,
        ]);
        $head = $seo->renderHead();
        assert_contains('"@type":"Product"', $head);
        assert_contains('"aggregateRating"', $head);
        assert_contains('"ratingValue":"4.6"', $head);
    });

    TestRunner::run('JSON-LD escapa </ para evitar break-out', function () {
        $site = make_fake_site();
        $seo = new SEO($site);
        $seo->schemaProduct([
            'name' => 'Hack</script><script>alert(1)</script>',
            'slug' => 'x', 'price_from' => null, 'rating' => null,
            'description_short' => '', 'price_currency' => null,
            'pricing_model' => 'custom', 'brand' => null, 'logo_url' => null,
        ]);
        $head = $seo->renderHead();
        // No debe aparecer el cierre </script> dentro del JSON.
        $jsonStart = strpos($head, '<script type="application/ld+json">');
        $jsonEnd   = strpos($head, '</script>', $jsonStart + 1);
        $payload   = substr($head, $jsonStart, $jsonEnd - $jsonStart);
        assert_not_contains('</script>', $payload);
        assert_contains('<\/script>', $payload);
    });

    TestRunner::run('noindex', function () {
        $site = make_fake_site();
        $seo = new SEO($site);
        $seo->noindex();
        $head = $seo->renderHead();
        assert_contains('content="noindex, nofollow"', $head);
    });
});
