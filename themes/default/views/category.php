<?php
/**
 * @var \Core\View $view
 * @var array|null $category
 * @var array      $products
 * @var int        $total
 * @var int        $page
 * @var int        $per_page
 * @var bool       $all_products
 */
$view->layout('default');
$title = $all_products ? 'Catálogo de productos' : $category['name'];
$pages = (int)ceil($total / max(1, $per_page));
$basePath = $all_products ? '/productos' : category_url($category);
?>
<section class="category-page">
    <header>
        <h1><?= e($title) ?></h1>
        <?php if (!$all_products && !empty($category['description'])): ?>
            <div class="category-description">
                <?= \Core\Markdown::toHtml($category['description']) ?>
            </div>
        <?php endif; ?>
        <p class="muted"><?= e($total) ?> productos</p>
    </header>

    <?php if ($products): ?>
        <div class="grid grid-cards">
            <?php foreach ($products as $p): ?>
                <?= $view->partial('product_card', ['product' => $p]) ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No hay productos en esta categoría todavía.</p>
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
