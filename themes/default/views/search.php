<?php
/**
 * @var \Core\View $view
 * @var \Core\Site $site
 * @var string     $q
 * @var array      $articles
 * @var array      $products
 * @var int        $total
 */
$view->layout('default');
?>
<section class="search-page">
    <h1>Búsqueda</h1>
    <form method="get" action="/buscar" class="search-form" role="search" autocomplete="off">
        <input type="search" name="q"
               value="<?= e($q) ?>"
               placeholder="Buscar productos, guías, reseñas…"
               aria-label="Término de búsqueda"
               maxlength="100"
               minlength="2"
               required
               autofocus>
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>

    <?php if ($q === ''): ?>
        <p class="search-status muted">Ingresá un término para buscar (mínimo 2 caracteres).</p>
    <?php elseif (mb_strlen($q) < 2): ?>
        <p class="search-status muted">Tu búsqueda es muy corta. Probá con al menos 2 caracteres.</p>
    <?php elseif ($total === 0): ?>
        <div class="search-empty">
            <h2>Sin resultados para «<?= e($q) ?>»</h2>
            <p class="muted">
                Probá con otra palabra, sinónimos o frases más cortas.
                También podés explorar nuestras secciones:
            </p>
            <ul class="search-empty-links">
                <li><a href="/guias">Guías</a></li>
                <li><a href="/resenas">Reseñas</a></li>
                <li><a href="/comparativas">Comparativas</a></li>
                <li><a href="/productos">Catálogo de productos</a></li>
            </ul>
        </div>
    <?php else: ?>
        <p class="search-status muted">
            <strong><?= (int)$total ?></strong> resultado<?= $total === 1 ? '' : 's' ?> para
            <strong>«<?= e($q) ?>»</strong>.
        </p>

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
    <?php endif; ?>
</section>
