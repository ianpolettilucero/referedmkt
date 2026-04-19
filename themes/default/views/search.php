<?php
/**
 * @var \Core\View $view
 * @var string     $q
 * @var array      $articles
 * @var array      $products
 * @var int        $total
 */
$view->layout('default');
?>
<section class="search-page">
    <h1>Búsqueda</h1>
    <form method="get" action="/buscar" class="search-form" role="search">
        <input type="search" name="q" value="<?= e($q) ?>" placeholder="Buscar productos, guías, reseñas…" aria-label="Término de búsqueda" autofocus style="padding:0.6rem;width:100%;max-width:500px;border:1px solid #e5e7eb;border-radius:6px">
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>

    <?php if ($q === ''): ?>
        <p class="muted">Ingresá un término para buscar.</p>
    <?php elseif (mb_strlen($q) < 2): ?>
        <p class="muted">Tu búsqueda es muy corta. Probá con al menos 2 caracteres.</p>
    <?php elseif ($total === 0): ?>
        <p class="muted">Sin resultados para <strong><?= e($q) ?></strong>.</p>
    <?php else: ?>
        <p class="muted"><?= e($total) ?> resultado(s) para <strong><?= e($q) ?></strong>.</p>

        <?php if ($products): ?>
            <section class="section">
                <h2>Productos</h2>
                <div class="grid grid-cards">
                    <?php foreach ($products as $p): ?>
                        <?= $view->partial('product_card', ['product' => $p]) ?>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>

        <?php if ($articles): ?>
            <section class="section">
                <h2>Artículos</h2>
                <div class="grid grid-cards">
                    <?php foreach ($articles as $a): ?>
                        <?= $view->partial('article_card', ['article' => $a]) ?>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    <?php endif; ?>
</section>
