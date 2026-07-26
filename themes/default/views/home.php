<?php
/**
 * @var \Core\View $view
 * @var \Core\Site $site
 * @var array      $featured_products
 * @var array      $recent_articles
 * @var array      $top_categories
 */
$view->layout('default');

// Numeros reales para la barra de confianza. Nada inventado: sale del contenido.
$nArticles = count(\Core\Content::publishedArticles());
$nProducts = count(\Core\Content::products());
$lastUpdate = null;
foreach (\Core\Content::publishedArticles() as $a) {
    $ts = !empty($a['updated_at']) ? strtotime($a['updated_at']) : null;
    if ($ts && ($lastUpdate === null || $ts > $lastUpdate)) { $lastUpdate = $ts; }
}
?>
<section class="hero">
    <p class="hero-eyebrow">Análisis independiente</p>
    <h1><?= e($site->tagline ?: $site->name) ?></h1>
    <?php if ($site->metaDescriptionTemplate): ?>
        <p class="hero-sub"><?= e($site->metaDescriptionTemplate) ?></p>
    <?php endif; ?>
    <p class="hero-actions">
        <a class="btn btn-primary" href="/guias">Ver guías</a>
        <a class="btn btn-ghost" href="/productos">Explorar catálogo</a>
    </p>
    <ul class="hero-stats">
        <li><strong><?= (int)$nArticles ?></strong> artículos publicados</li>
        <li><strong><?= (int)$nProducts ?></strong> productos analizados</li>
        <?php if ($lastUpdate): ?>
            <li>Actualizado el <strong><?= e(date('d/m/Y', $lastUpdate)) ?></strong></li>
        <?php endif; ?>
    </ul>
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
