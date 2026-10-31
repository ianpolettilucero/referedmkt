<?php
use Core\Content;
use Core\SEO;
use Models\Article;

/**
 * Secciones dentro de Noticias (`section: mundo` en el front matter).
 * Siembra su propio contenido para no depender del de 11_content_test.
 */
function seed_news_sections(): void
{
    $mk = function (array $o): array {
        return array_merge([
            'site_id' => 1, 'status' => 'published', 'article_type' => 'news',
            'news_section' => null, 'subtitle' => null, 'excerpt' => null,
            'content' => '', 'content_html' => '', 'category_id' => 1,
            'category_slug' => 'seguridad', 'category_name' => 'Seguridad',
            'author_id' => null, 'related_product_ids' => [], 'rating' => null,
            'verdict' => null, 'pros' => null, 'cons' => null,
            'featured_image' => null, 'updated_at' => '2026-01-01 00:00:00',
        ], $o);
    };

    Content::seed([
        'site' => [
            'id' => 1, 'domain' => 'example.com', 'name' => 'Example', 'slug' => 'example',
            'active' => 1, 'settings' => [],
        ],
        'categories' => [
            'seguridad' => ['id' => 1, 'slug' => 'seguridad', 'name' => 'Seguridad', 'sort_order' => 1, 'parent_id' => null],
        ],
        'authors' => [],
        'affiliate_links' => [],
        'products' => [],
        'articles' => [
            'cve'    => $mk(['id' => 1, 'slug' => 'cve', 'title' => 'Una falla',
                             'published_at' => '2026-10-30 00:00:00']),
            'robo'   => $mk(['id' => 2, 'slug' => 'robo', 'title' => 'Un robo',
                             'news_section' => 'mundo', 'published_at' => '2026-10-31 00:00:00']),
            'guia'   => $mk(['id' => 3, 'slug' => 'guia', 'title' => 'Una guia',
                             'article_type' => 'guide', 'published_at' => '2026-10-01 00:00:00']),
        ],
        'redirects' => [],
    ]);
}

TestRunner::group('Secciones de noticias', function () {

    TestRunner::run('news_sections define mundo con su ruta', function () {
        $s = news_sections();
        assert_true(isset($s['mundo']));
        assert_eq('/noticias/mundo', $s['mundo']['path']);
    });

    TestRunner::run('paginate filtra por seccion de noticias', function () {
        seed_news_sections();
        $todas = Article::paginate(1, ['type' => 'news'], 1, 20);
        assert_eq(2, $todas['total']);
        $mundo = Article::paginate(1, ['type' => 'news', 'news_section' => 'mundo'], 1, 20);
        assert_eq(1, $mundo['total']);
        assert_eq('robo', $mundo['items'][0]['slug']);
    });

    TestRunner::run('una nota del mundo sigue en /noticia/{slug}', function () {
        seed_news_sections();
        $a = Article::findPublished(1, 'robo');
        assert_eq('/noticia/robo', article_url($a));
    });

    TestRunner::run('articleSection usa la seccion y si no la categoria', function () {
        seed_news_sections();
        $site = make_fake_site();

        $seo = new SEO($site);
        $seo->schemaArticle(Article::findPublished(1, 'robo'));
        assert_contains('"articleSection":"Mundo"', $seo->renderHead());

        $seo = new SEO($site);
        $seo->schemaArticle(Article::findPublished(1, 'cve'));
        assert_contains('"articleSection":"Seguridad"', $seo->renderHead());
    });
});
