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

    TestRunner::run('schemaProduct con rating emite Review, no aggregateRating', function () {
        // Nuestra nota es una opinion editorial unica. Un aggregateRating con
        // ratingCount 1 le declara a Google un agregado que no existe.
        $site = make_fake_site();
        $seo = new SEO($site);
        $seo->schemaProduct([
            'name' => 'Bitdefender', 'brand' => 'Bitdefender', 'slug' => 'bd',
            'description_short' => 'd', 'price_from' => 50, 'price_currency' => 'USD',
            'pricing_model' => 'yearly', 'rating' => 4.6, 'logo_url' => null,
        ]);
        $head = $seo->renderHead();
        assert_contains('"@type":"Product"', $head);
        assert_not_contains('aggregateRating', $head);
        assert_contains('"review"', $head);
        assert_contains('"ratingValue":"4.6"', $head);
        assert_contains('"bestRating":"5"', $head);
    });

    /** Articulo minimo de tipo review para los tests de schema. */
    function fake_review_article(array $overrides = []): array
    {
        return array_merge([
            'id' => 7, 'slug' => 'bitdefender-analisis', 'title' => 'Bitdefender: analisis',
            'excerpt' => 'Resumen corto.', 'verdict' => 'Buena opcion para PyMEs.',
            'featured_image' => null, 'article_type' => 'review', 'rating' => 4.5,
            'published_at' => '2026-01-10 10:00:00', 'updated_at' => '2026-03-01 09:00:00',
            'author_name' => 'Equipo Editorial', 'author_slug' => 'equipo-editorial',
        ], $overrides);
    }

    /** Producto minimo para los tests de schema. */
    function fake_reviewed_product(array $overrides = []): array
    {
        return array_merge([
            'name' => 'Bitdefender GravityZone', 'brand' => 'Bitdefender',
            'slug' => 'bitdefender-gravityzone', 'logo_url' => null, 'rating' => 4.6,
        ], $overrides);
    }

    TestRunner::run('schemaReview emite itemReviewed + reviewRating', function () {
        $site = make_fake_site();
        $seo = new SEO($site);
        $seo->schemaReview(fake_review_article(), fake_reviewed_product());
        $head = $seo->renderHead();
        assert_contains('"@type":"Review"', $head);
        assert_contains('"itemReviewed"', $head);
        assert_contains('"reviewRating"', $head);
        assert_contains('"ratingValue":"4.5"', $head);  // gana la nota del articulo
        assert_contains('Bitdefender GravityZone', $head);
        assert_contains('"@type":"Person"', $head);
        assert_contains('"publisher"', $head);
    });

    TestRunner::run('schemaReview cae al rating del producto si el articulo no tiene', function () {
        $site = make_fake_site();
        $seo = new SEO($site);
        $seo->schemaReview(fake_review_article(['rating' => null]), fake_reviewed_product());
        $head = $seo->renderHead();
        assert_contains('"@type":"Review"', $head);
        assert_contains('"ratingValue":"4.6"', $head);
    });

    TestRunner::run('schemaReview degrada a Article sin producto', function () {
        // Un Review sin itemReviewed es invalido: mejor un Article correcto.
        $site = make_fake_site();
        $seo = new SEO($site);
        $seo->schemaReview(fake_review_article(), null);
        $head = $seo->renderHead();
        assert_contains('"@type":"Article"', $head);
        assert_not_contains('"itemReviewed"', $head);
    });

    TestRunner::run('schemaReview degrada a Article sin ninguna nota', function () {
        $site = make_fake_site();
        $seo = new SEO($site);
        $seo->schemaReview(
            fake_review_article(['rating' => null]),
            fake_reviewed_product(['rating' => null])
        );
        $head = $seo->renderHead();
        assert_contains('"@type":"Article"', $head);
        assert_not_contains('"reviewRating"', $head);
    });

    TestRunner::run('schemaArticle nunca emite Review', function () {
        // El tipo Review sale solo por schemaReview(), que garantiza los
        // campos obligatorios.
        $site = make_fake_site();
        $seo = new SEO($site);
        $seo->schemaArticle(fake_review_article());
        $head = $seo->renderHead();
        assert_contains('"@type":"Article"', $head);
        assert_not_contains('"@type":"Review"', $head);
    });

    TestRunner::run('schemaArticle usa NewsArticle para noticias', function () {
        $site = make_fake_site();
        $seo = new SEO($site);
        $seo->schemaArticle(fake_review_article(['article_type' => 'news']));
        $head = $seo->renderHead();
        assert_contains('"@type":"NewsArticle"', $head);
    });

    TestRunner::run('schemaFaq emite FAQPage', function () {
        $site = make_fake_site();
        $seo = new SEO($site);
        $seo->schemaFaq([['question' => '¿Sirve?', 'answer' => 'Si.']]);
        $head = $seo->renderHead();
        assert_contains('"@type":"FAQPage"', $head);
        assert_contains('"@type":"Question"', $head);
        assert_contains('"@type":"Answer"', $head);
    });

    TestRunner::run('schemaFaq vacio no emite nada', function () {
        $site = make_fake_site();
        $seo = new SEO($site);
        $seo->schemaFaq([]);
        $head = $seo->renderHead();
        assert_not_contains('FAQPage', $head);
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
        // El default permisivo no puede pisar un noindex explicito.
        assert_not_contains('max-image-preview', $head);
    });

    // Sin robots explicito va el permisivo: por defecto Google recorta la vista
    // previa de imagen a miniatura y el fragmento a ~160 caracteres, que es el
    // formato que deja una nota afuera de Discover y de Noticias destacadas.
    TestRunner::run('sin robots explicito se emite el permisivo', function () {
        $head = (new SEO(make_fake_site()))->renderHead();
        assert_contains('max-image-preview:large', $head);
        assert_contains('max-snippet:-1', $head);
        assert_not_contains('noindex', $head);
    });

    // Anunciar summary_large_image sin og:image hace que X, LinkedIn y WhatsApp
    // degraden la vista previa a texto pelado.
    TestRunner::run('la tarjeta grande solo se anuncia si hay imagen', function () {
        $sin = (new SEO(make_fake_site()))->renderHead();
        assert_contains('name="twitter:card" content="summary"', $sin);
        assert_not_contains('summary_large_image', $sin);

        $con = (new SEO(make_fake_site()))->ogImage('/uploads/x.png')->renderHead();
        assert_contains('summary_large_image', $con);
    });

    // Google exige que las imagenes referenciadas desde datos estructurados
    // sean rastreables: una ruta relativa suelta dentro de un JSON-LD no se
    // resuelve de forma confiable fuera del documento.
    TestRunner::run('el logo del JSON-LD sale absoluto', function () {
        $head = (new SEO(make_fake_site()))->schemaOrganization()->renderHead();
        assert_not_contains('"logo":"/uploads', $head);
        assert_contains('"logo":"http', $head);
    });

    // Un hub de categoria sin ItemList no declara que es un listado ni de que
    // productos: queda como una pagina suelta con una grilla adentro.
    TestRunner::run('el hub de categoria declara CollectionPage con su ItemList', function () {
        $cat  = ['slug' => 'mfa', 'name' => 'MFA', 'meta_description' => 'Autenticacion multifactor.'];
        $prods = [
            ['slug' => 'duo',  'name' => 'Cisco Duo'],
            ['slug' => 'yubi', 'name' => 'YubiKey 5'],
        ];
        $head = (new SEO(make_fake_site()))->schemaCollection($cat, $prods)->renderHead();

        assert_contains('"@type":"CollectionPage"', $head);
        assert_contains('"@type":"ItemList"', $head);
        assert_contains('"numberOfItems":2', $head);
        assert_contains('"position":1', $head);
        assert_contains('Cisco Duo', $head);
        // Las URLs de los items tienen que ser absolutas para resolverse fuera
        // del documento.
        assert_not_contains('"url":"/producto/', $head);
    });

    // Una categoria todavia sin productos cargados no puede anunciar una lista
    // vacia: un ItemList con cero items es peor que no declararlo.
    TestRunner::run('sin productos no se emite ItemList', function () {
        $head = (new SEO(make_fake_site()))
            ->schemaCollection(['slug' => 'x', 'name' => 'X', 'meta_description' => 'Y'], [])
            ->renderHead();
        assert_contains('"@type":"CollectionPage"', $head);
        assert_not_contains('ItemList', $head);
    });
});
