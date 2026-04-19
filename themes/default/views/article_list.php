<?php
/**
 * @var \Core\View $view
 * @var array  $articles
 * @var int    $total
 * @var int    $page
 * @var int    $per_page
 * @var string $type
 * @var string $label
 */
$view->layout('default');
$pages = (int)ceil($total / max(1, $per_page));
$basePath = match ($type) {
    'review'     => '/resenas',
    'comparison' => '/comparativas',
    'news'       => '/noticias',
    default      => '/guias',
};
?>
<section class="article-list-page">
    <header>
        <h1><?= e($label) ?></h1>
        <p class="muted"><?= e($total) ?> publicados</p>
    </header>

    <?php if ($articles): ?>
        <div class="grid grid-cards">
            <?php foreach ($articles as $a): ?>
                <?= $view->partial('article_card', ['article' => $a]) ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Sin publicaciones todavía.</p>
    <?php endif; ?>

    <?php if ($pages > 1): ?>
        <nav class="pagination" aria-label="Paginación">
            <?php for ($i = 1; $i <= $pages; $i++): ?>
                <?php if ($i === $page): ?>
                    <span class="current"><?= e($i) ?></span>
                <?php else: ?>
                    <a href="<?= e($basePath . '?page=' . $i) ?>"><?= e($i) ?></a>
                <?php endif; ?>
            <?php endfor; ?>
        </nav>
    <?php endif; ?>
</section>
