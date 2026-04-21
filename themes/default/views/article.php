<?php
/**
 * @var \Core\View $view
 * @var array      $article
 * @var string     $content_html
 * @var array      $related_products
 */
$view->layout('default');
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
            <?php if (!empty($article['published_at'])): ?>
                · <time datetime="<?= e(date('c', strtotime($article['published_at']))) ?>">
                    <?= e(date('d/m/Y', strtotime($article['published_at']))) ?>
                </time>
            <?php endif; ?>
            <?php $minutes = reading_time($article['content'] ?? ''); ?>
            · <span class="article-reading-time" title="Tiempo estimado de lectura"><?= e($minutes) ?> min de lectura</span>
        </p>
        <?php if (!empty($article['featured_image'])): ?>
            <img class="article-hero" src="<?= e($article['featured_image']) ?>" alt="<?= e($article['title']) ?>" loading="lazy">
        <?php endif; ?>
    </header>

    <div class="article-body">
        <?= $content_html /* HTML generado por Markdown::toHtml (input escapado) */ ?>
    </div>

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

    <?= $view->partial('newsletter_signup') ?>
</article>
