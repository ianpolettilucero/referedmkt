<?php
use Core\Content;
use Models\Article;
use Models\Category;
use Models\Product;

/**
 * Datos sinteticos inyectados con Content::seed(): estos tests no tocan
 * content/ ni el disco, asi no dependen de lo que este publicado.
 */
function seed_content(): void
{
    $mk = function (array $o): array {
        return array_merge([
            'site_id' => 1, 'status' => 'published', 'article_type' => 'guide',
            'subtitle' => null, 'excerpt' => null, 'content' => '', 'content_html' => '',
            'category_id' => null, 'author_id' => null, 'related_product_ids' => [],
            'rating' => null, 'verdict' => null, 'pros' => null, 'cons' => null,
            'updated_at' => '2026-01-01 00:00:00',
        ], $o);
    };

    Content::seed([
        'site' => [
            'id' => 1, 'domain' => 'example.com', 'name' => 'Example', 'slug' => 'example',
            'active' => 1, 'settings' => ['theme_preset' => 'tech-cyan', 'newsletter_enabled' => false],
        ],
        'categories' => [
            'seguridad' => ['id' => 1, 'slug' => 'seguridad', 'name' => 'Seguridad', 'sort_order' => 1, 'parent_id' => null],
            'redes'     => ['id' => 2, 'slug' => 'redes',     'name' => 'Redes',     'sort_order' => 2, 'parent_id' => 1],
        ],
        'authors' => [
            'ana' => ['id' => 1, 'slug' => 'ana', 'name' => 'Ana', 'avatar_url' => null],
        ],
        'affiliate_links' => [
            'vendor-a' => ['id' => 1, 'tracking_slug' => 'vendor-a', 'name' => 'A',
                           'destination_url' => 'https://a.example', 'active' => 1],
            'vendor-off' => ['id' => 2, 'tracking_slug' => 'vendor-off', 'name' => 'Off',
                             'destination_url' => 'https://off.example', 'active' => 0],
        ],
        'products' => [
            'alfa' => ['id' => 1, 'slug' => 'alfa', 'name' => 'Alfa', 'brand' => 'AcmeCorp',
                       'rating' => 4.8, 'price_from' => 100.0, 'featured' => 1, 'category_id' => 1,
                       'description_short' => 'Corta de alfa', 'description_long' => '',
                       'updated_at' => '2026-03-01 00:00:00'],
            'beta' => ['id' => 2, 'slug' => 'beta', 'name' => 'Beta', 'brand' => 'Zen',
                       'rating' => 3.9, 'price_from' => 20.0, 'featured' => 0, 'category_id' => 1,
                       'description_short' => '', 'description_long' => '',
                       'updated_at' => '2026-05-01 00:00:00'],
            'gama' => ['id' => 3, 'slug' => 'gama', 'name' => 'Gama', 'brand' => null,
                       'rating' => null, 'price_from' => null, 'featured' => 0, 'category_id' => null,
                       'description_short' => '', 'description_long' => '',
                       'updated_at' => '2026-02-01 00:00:00'],
        ],
        'articles' => [
            'vieja'      => $mk(['id' => 1, 'slug' => 'vieja', 'title' => 'Zeta guia vieja',
                                 'published_at' => '2026-01-10 00:00:00', 'category_id' => 1]),
            'nueva'      => $mk(['id' => 2, 'slug' => 'nueva', 'title' => 'Alfa resena nueva',
                                 'published_at' => '2026-04-10 00:00:00', 'article_type' => 'review',
                                 'category_id' => 1, 'author_id' => 1]),
            'borrador'   => $mk(['id' => 3, 'slug' => 'borrador', 'title' => 'Borrador',
                                 'status' => 'draft', 'published_at' => '2026-02-01 00:00:00']),
            'futura'     => $mk(['id' => 4, 'slug' => 'futura', 'title' => 'Programada',
                                 'published_at' => '2099-01-01 00:00:00']),
            'sin-cat'    => $mk(['id' => 5, 'slug' => 'sin-cat', 'title' => 'Media guia',
                                 'published_at' => '2026-03-01 00:00:00']),
        ],
        'redirects' => [
            ['from_path' => '/viejo', 'to_path' => '/nuevo', 'status_code' => 301],
        ],
    ]);
}

TestRunner::group('Content', function () {

    TestRunner::run('publishedArticles excluye borradores', function () {
        seed_content();
        $slugs = array_column(Content::publishedArticles(), 'slug');
        assert_false(in_array('borrador', $slugs, true), 'un borrador no debe publicarse');
    });

    TestRunner::run('publishedArticles excluye programadas a futuro', function () {
        seed_content();
        $slugs = array_column(Content::publishedArticles(), 'slug');
        assert_false(in_array('futura', $slugs, true), 'una nota con fecha futura no debe salir todavia');
    });

    TestRunner::run('publishedArticles ordena por fecha descendente', function () {
        seed_content();
        assert_eq(['nueva', 'sin-cat', 'vieja'], array_column(Content::publishedArticles(), 'slug'));
    });

    TestRunner::run('byId encuentra por id entero', function () {
        seed_content();
        assert_eq('beta', Content::byId(Content::products(), 2)['slug']);
        assert_eq(null, Content::byId(Content::products(), 999));
        assert_eq(null, Content::byId(Content::products(), 0));
    });
});

TestRunner::group('Article', function () {

    TestRunner::run('findPublished devuelve solo publicados', function () {
        seed_content();
        assert_eq('nueva', Article::findPublished(1, 'nueva')['slug']);
        assert_eq(null, Article::findPublished(1, 'borrador'));
        assert_eq(null, Article::findPublished(1, 'futura'));
        assert_eq(null, Article::findPublished(1, 'no-existe'));
    });

    TestRunner::run('recent respeta limite y filtro por tipo', function () {
        seed_content();
        assert_eq(2, count(Article::recent(1, 2)));
        $reviews = Article::recent(1, 10, 'review');
        assert_eq(1, count($reviews));
        assert_eq('nueva', $reviews[0]['slug']);
    });

    TestRunner::run('related excluye el articulo actual', function () {
        seed_content();
        $slugs = array_column(Article::related(1, 2, 1, 'review', 5), 'slug');
        assert_false(in_array('nueva', $slugs, true), 'no debe auto-linkearse');
    });

    TestRunner::run('related prioriza misma categoria sobre recencia', function () {
        seed_content();
        // Excluyendo 'nueva', quedan 'vieja' (categoria 1, enero) y 'sin-cat'
        // (sin categoria, marzo). El tipo pedido no matchea ninguna, asi que
        // solo pesa la categoria: gana 'vieja' aunque sea mas vieja.
        $rel = Article::related(1, 2, 1, 'news', 5);
        assert_eq('vieja', $rel[0]['slug']);
    });

    TestRunner::run('related usa el tipo como segundo criterio', function () {
        seed_content();
        // Sin categoria en juego, desempata el tipo: 'nueva' es la unica review.
        $rel = Article::related(1, 1, null, 'review', 5);
        assert_eq('nueva', $rel[0]['slug']);
    });

    TestRunner::run('related desempata por recencia', function () {
        seed_content();
        // Misma categoria y mismo tipo para ambas -> gana la mas reciente.
        $rel = Article::related(1, 2, 1, 'guide', 5);
        assert_eq('vieja', $rel[0]['slug']);   // unica en categoria 1
        $rel2 = Article::related(1, 2, null, 'guide', 5);
        assert_eq('sin-cat', $rel2[0]['slug']); // marzo gana a enero
    });

    TestRunner::run('paginate filtra por tipo y pagina', function () {
        seed_content();
        $p = Article::paginate(1, ['type' => 'guide'], 1, 1);
        assert_eq(2, $p['total']);
        assert_eq(1, count($p['items']));
        $p2 = Article::paginate(1, ['type' => 'guide'], 2, 1);
        assert_eq('vieja', $p2['items'][0]['slug']);
    });

    TestRunner::run('paginate ordena az y oldest', function () {
        seed_content();
        $az = Article::paginate(1, ['sort' => 'az'], 1, 10);
        assert_eq('Alfa resena nueva', $az['items'][0]['title']);
        $old = Article::paginate(1, ['sort' => 'oldest'], 1, 10);
        assert_eq('vieja', $old['items'][0]['slug']);
    });

    TestRunner::run('byAuthor filtra por autor', function () {
        seed_content();
        $rows = Article::byAuthor(1, 1);
        assert_eq(1, count($rows));
        assert_eq('nueva', $rows[0]['slug']);
        assert_eq([], Article::byAuthor(1, 99));
    });
});

TestRunner::group('Product', function () {

    TestRunner::run('featured solo destacados', function () {
        seed_content();
        $f = Product::featured(1, 10);
        assert_eq(1, count($f));
        assert_eq('alfa', $f[0]['slug']);
    });

    TestRunner::run('catalog filtra por marca y rating', function () {
        seed_content();
        assert_eq(1, Product::catalog(1, ['brand' => 'Zen'], 1, 10)['total']);
        assert_eq(1, Product::catalog(1, ['min_rating' => 4.0], 1, 10)['total']);
    });

    TestRunner::run('catalog max_price conserva los que no declaran precio', function () {
        seed_content();
        // 'gama' no tiene precio: no sabemos si entra, no lo descartamos.
        $slugs = array_column(Product::catalog(1, ['max_price' => 50], 1, 10)['items'], 'slug');
        assert_true(in_array('beta', $slugs, true));
        assert_true(in_array('gama', $slugs, true));
        assert_false(in_array('alfa', $slugs, true));
    });

    TestRunner::run('catalog ordena por precio con nulls al final', function () {
        seed_content();
        $slugs = array_column(Product::catalog(1, ['sort' => 'price-asc'], 1, 10)['items'], 'slug');
        assert_eq(['beta', 'alfa', 'gama'], $slugs);
    });

    TestRunner::run('catalog ordena por rating', function () {
        seed_content();
        $slugs = array_column(Product::catalog(1, ['sort' => 'rating'], 1, 10)['items'], 'slug');
        assert_eq(['alfa', 'beta', 'gama'], $slugs);
    });

    TestRunner::run('brands unicas y ordenadas', function () {
        seed_content();
        assert_eq(['AcmeCorp', 'Zen'], Product::brands(1));
    });

    TestRunner::run('byIds acepta slugs e ids y respeta el orden', function () {
        seed_content();
        assert_eq(['beta', 'alfa'], array_column(Product::byIds(1, ['beta', 'alfa']), 'slug'));
        assert_eq(['beta', 'alfa'], array_column(Product::byIds(1, [2, 1]), 'slug'));
        assert_eq([], Product::byIds(1, ['no-existe', 999]));
    });

    TestRunner::run('groupedByCategory agrupa y manda huerfanos al final', function () {
        seed_content();
        $g = Product::groupedByCategory(1, 10);
        assert_eq('Seguridad', $g[0]['category']['name']);
        assert_eq(2, $g[0]['total']);
        assert_eq('Otros productos', $g[count($g) - 1]['category']['name']);
    });
});

TestRunner::group('Category', function () {

    TestRunner::run('topLevel excluye las que tienen parent', function () {
        seed_content();
        assert_eq(['seguridad'], array_column(Category::topLevel(1), 'slug'));
    });

    TestRunner::run('all las devuelve por sort_order', function () {
        seed_content();
        assert_eq(['seguridad', 'redes'], array_column(Category::all(1), 'slug'));
    });

    TestRunner::run('findBySlug', function () {
        seed_content();
        assert_eq('Redes', Category::findBySlug(1, 'redes')['name']);
        assert_eq(null, Category::findBySlug(1, 'no-existe'));
    });
});

TestRunner::group('Settings', function () {

    TestRunner::run('lee del bloque settings del sitio', function () {
        seed_content();
        assert_eq('tech-cyan', \Core\Settings::get(1, 'theme_preset'));
        assert_eq('default-si-falta', \Core\Settings::get(1, 'no_existe', 'default-si-falta'));
    });

    TestRunner::run('bool se expone como 1/0 y getBool lo interpreta', function () {
        seed_content();
        assert_eq('0', \Core\Settings::get(1, 'newsletter_enabled'));
        assert_false(\Core\Settings::getBool(1, 'newsletter_enabled', true));
        assert_true(\Core\Settings::getBool(1, 'no_existe', true));
    });
});

TestRunner::group('AffiliateLink', function () {

    TestRunner::run('findActiveBySlug ignora los inactivos', function () {
        seed_content();
        assert_eq('vendor-a', \Models\AffiliateLink::findActiveBySlug(1, 'vendor-a')['tracking_slug']);
        assert_eq(null, \Models\AffiliateLink::findActiveBySlug(1, 'vendor-off'));
        assert_eq(null, \Models\AffiliateLink::findActiveBySlug(1, 'no-existe'));
    });
});
