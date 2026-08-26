<?php
/**
 * @var \Core\View $view
 * @var \Core\Site $site
 * @var array      $article
 * @var string     $content_html
 * @var string     $verdict_html
 * @var array      $related_products
 * @var array      $related_articles
 * @var array      $toc_items
 */
$view->layout('default');
$related_articles = $related_articles ?? [];
$toc_items        = $toc_items ?? [];
$verdict_html     = $verdict_html ?? '';

// "Actualizado el X": solo si la ultima edicion es al menos un dia posterior a
// la publicacion. Contenido de ciberseguridad envejece rapido y la fecha de
// revision es señal de frescura para el lector (y coincide con dateModified
// del JSON-LD). Un typo corregido el mismo dia no cuenta como actualizacion.
$updatedTs   = !empty($article['updated_at'])   ? strtotime($article['updated_at'])   : null;
$publishedTs = !empty($article['published_at']) ? strtotime($article['published_at']) : null;
$showUpdated = $updatedTs && $publishedTs && ($updatedTs - $publishedTs) >= 86400;

$articlePros = is_array($article['pros'] ?? null) ? $article['pros'] : [];
$articleCons = is_array($article['cons'] ?? null) ? $article['cons'] : [];
$articleRating = ($article['rating'] ?? null) !== null && $article['rating'] !== ''
    ? (float)$article['rating']
    : null;
$hasVerdict = $verdict_html !== '' || $articlePros || $articleCons || $articleRating !== null;
?>
<article class="article">
    <header class="article-header">
        <h1><?= e($article['title']) ?></h1>
        <?php if (!empty($article['subtitle'])): ?>
            <p class="article-subtitle"><?= e($article['subtitle']) ?></p>
        <?php endif; ?>
        <p class="article-meta">
            <?php if (!empty($article['author_name'])): ?>
                Por <a rel="author" href="<?= e('/autor/' . ($article['author_slug'] ?? '')) ?>"><strong><?= e($article['author_name']) ?></strong></a>
            <?php endif; ?>
            <?php if ($publishedTs): ?>
                · <time datetime="<?= e(date('c', $publishedTs)) ?>">
                    <?= e(date('d/m/Y', $publishedTs)) ?>
                </time>
            <?php endif; ?>
            <?php if ($showUpdated): ?>
                · <span class="article-updated">Actualizado el
                    <time datetime="<?= e(date('c', $updatedTs)) ?>"><?= e(date('d/m/Y', $updatedTs)) ?></time>
                </span>
            <?php endif; ?>
            <?php $minutes = reading_time($article['content'] ?? ''); ?>
            · <span class="article-reading-time" title="Tiempo estimado de lectura"><?= e($minutes) ?> min de lectura</span>
        </p>
        <?= $view->partial('article_share', ['article' => $article, 'variant' => 'compacto']) ?>
        <?php if (!empty($article['featured_image'])): ?>
            <img class="article-hero" src="<?= e($article['featured_image']) ?>" alt="<?= e($article['title']) ?>" loading="lazy">
        <?php endif; ?>
    </header>

    <?php if ($toc_items): ?>
        <details class="article-toc no-print">
            <summary>
                <span class="article-toc-icon" aria-hidden="true">☰</span>
                <span>Índice del artículo</span>
                <span class="article-toc-count"><?= count($toc_items) ?> secciones</span>
            </summary>
            <nav aria-label="Tabla de contenidos">
                <ol>
                    <?php foreach ($toc_items as $it): ?>
                        <li class="toc-level-<?= (int)$it['level'] ?>">
                            <a href="#<?= e($it['id']) ?>"><?= e($it['text']) ?></a>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </nav>
        </details>
    <?php endif; ?>

    <div class="article-main">
    <div class="article-body">
        <?= $content_html /* HTML generado por Markdown::toHtml (input escapado) */ ?>
    </div>

    <?php if ($hasVerdict): ?>
        <section class="article-verdict" aria-labelledby="veredicto">
            <div class="article-verdict-head">
                <h2 id="veredicto">Veredicto</h2>
                <?php if ($articleRating !== null): ?>
                    <span class="article-verdict-score" aria-label="Nota <?= e(number_format($articleRating, 1)) ?> sobre 5">
                        <strong><?= e(number_format($articleRating, 1)) ?></strong><span>/5</span>
                    </span>
                <?php endif; ?>
            </div>

            <?php if ($verdict_html !== ''): ?>
                <div class="article-verdict-body"><?= $verdict_html ?></div>
            <?php endif; ?>

            <?php if ($articlePros || $articleCons): ?>
                <div class="pros-cons">
                    <?php if ($articlePros): ?>
                        <section>
                            <h3>A favor</h3>
                            <ul>
                                <?php foreach ($articlePros as $p): ?>
                                    <li><?= e((string)$p) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </section>
                    <?php endif; ?>
                    <?php if ($articleCons): ?>
                        <section>
                            <h3>En contra</h3>
                            <ul>
                                <?php foreach ($articleCons as $c): ?>
                                    <li><?= e((string)$c) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </section>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </section>
    <?php endif; ?>

    <?php if ($related_products): ?>
        <section class="related-products">
            <h2>Productos mencionados</h2>
            <div class="grid grid-cards">
                <?php foreach ($related_products as $p): ?>
                    <?= $view->partial('product_card', ['product' => $p]) ?>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($related_articles): ?>
        <section class="related-articles no-print">
            <h2>Seguí leyendo</h2>
            <div class="grid grid-cards">
                <?php foreach ($related_articles as $ra): ?>
                    <?= $view->partial('article_card', ['article' => $ra]) ?>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <?= $view->partial('article_share', ['article' => $article]) ?>

    <?= $view->partial('newsletter_signup') ?>
    </div><!-- /.article-main -->
</article>
