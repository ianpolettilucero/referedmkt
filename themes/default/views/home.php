<?php
/**
 * @var \Core\View $view
 * @var \Core\Site $site
 * @var array      $featured_products
 * @var array      $recent_articles
 * @var array      $top_categories
 */
$view->layout('default');
?>
<section class="hero">
    <h1><?= e($site->name) ?></h1>
    <?php if ($site->metaDescriptionTemplate): ?>
        <p class="hero-sub"><?= e($site->metaDescriptionTemplate) ?></p>
    <?php endif; ?>
    <p><a class="btn btn-primary" href="/productos">Ver catálogo</a></p>
</section>

<?php if ($top_categories): ?>
<section class="section">
    <h2>Categorías</h2>
    <ul class="category-list">
        <?php foreach ($top_categories as $cat): ?>
            <li><a href="<?= e(category_url($cat)) ?>"><?= e($cat['name']) ?></a></li>
        <?php endforeach; ?>
    </ul>
</section>
<?php endif; ?>

<?php if ($featured_products): ?>
<section class="section">
    <h2>Productos destacados</h2>
    <div class="grid grid-cards">
        <?php foreach ($featured_products as $p): ?>
            <?= $view->partial('product_card', ['product' => $p]) ?>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($recent_articles): ?>
<section class="section">
    <h2>Últimos artículos</h2>
    <div class="grid grid-cards">
        <?php foreach ($recent_articles as $a): ?>
            <?= $view->partial('article_card', ['article' => $a]) ?>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>
