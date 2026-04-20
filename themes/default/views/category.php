<?php
/**
 * @var \Core\View $view
 * @var array|null $category
 * @var string     $view_mode    'grouped' | 'flat'
 * @var array      $products     (flat)
 * @var array      $grouped      (grouped) [{category, products, total}, ...]
 * @var int        $total
 * @var int        $page
 * @var int        $per_page
 * @var bool       $all_products
 * @var array      $filters
 * @var array      $sorts
 * @var array      $brands
 * @var array      $categories
 * @var int|null   $selected_cat_id
 */
$view->layout('default');
$title = $all_products ? 'Catálogo de productos' : $category['name'];
$basePath = $all_products ? '/productos' : category_url($category);

$pages = isset($per_page) && $per_page > 0 ? (int)ceil($total / $per_page) : 1;

$buildUrl = function (array $override = []) use ($basePath, $filters) {
    $params = [];
    if (!empty($filters['brand']))       { $params['brand']      = $filters['brand']; }
    if (!empty($filters['min_rating']))  { $params['min_rating'] = $filters['min_rating']; }
    if (!empty($filters['max_price']))   { $params['max_price']  = $filters['max_price']; }
    if (!empty($filters['sort']) && $filters['sort'] !== 'featured') {
        $params['sort'] = $filters['sort'];
    }
    $params = array_merge($params, $override);
    return $basePath . ($params ? '?' . http_build_query($params) : '');
};
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

    <form method="get" action="<?= e($basePath) ?>" class="filters-bar" aria-label="Filtros">
        <?php if ($all_products && $categories): ?>
            <div class="filter-group">
                <label for="f-cat">Categoría</label>
                <select name="cat" id="f-cat" onchange="this.form.submit()">
                    <option value="">Todas</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= e($c['slug']) ?>" <?= ($selected_cat_id && (int)$c['id'] === (int)$selected_cat_id) ? 'selected' : '' ?>>
                            <?= e($c['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>

        <?php if ($brands): ?>
            <div class="filter-group">
                <label for="f-brand">Marca</label>
                <select name="brand" id="f-brand" onchange="this.form.submit()">
                    <option value="">Todas</option>
                    <?php foreach ($brands as $b): ?>
                        <option value="<?= e($b) ?>" <?= ($filters['brand'] ?? '') === $b ? 'selected' : '' ?>>
                            <?= e($b) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>

        <div class="filter-group">
            <label for="f-rating">Rating mínimo</label>
            <select name="min_rating" id="f-rating" onchange="this.form.submit()">
                <option value="">Cualquiera</option>
                <?php foreach ([4.5, 4, 3.5, 3] as $r): ?>
                    <option value="<?= e($r) ?>" <?= ((float)($filters['min_rating'] ?? 0) === (float)$r) ? 'selected' : '' ?>>
                        <?= e($r) ?>+ ★
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filter-group">
            <label for="f-price">Precio máximo</label>
            <select name="max_price" id="f-price" onchange="this.form.submit()">
                <option value="">Cualquiera</option>
                <?php foreach ([20, 50, 100, 250, 500, 1000] as $p): ?>
                    <option value="<?= e($p) ?>" <?= ((float)($filters['max_price'] ?? 0) === (float)$p) ? 'selected' : '' ?>>
                        hasta $<?= e($p) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="filter-group filter-group-sort">
            <label for="f-sort">Ordenar</label>
            <select name="sort" id="f-sort" onchange="this.form.submit()">
                <?php foreach ($sorts as $value => $label): ?>
                    <option value="<?= e($value) ?>" <?= ($filters['sort'] ?? 'featured') === $value ? 'selected' : '' ?>>
                        <?= e($label) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if (!empty($filters['brand']) || !empty($filters['min_rating']) || !empty($filters['max_price']) || (!empty($filters['sort']) && $filters['sort'] !== 'featured')): ?>
            <a class="filter-clear" href="<?= e($basePath) ?>">Limpiar filtros</a>
        <?php endif; ?>
    </form>

    <?php if (($view_mode ?? 'flat') === 'grouped' && !empty($grouped)): ?>
        <?php foreach ($grouped as $group): ?>
            <section class="category-group">
                <div class="category-group-header">
                    <h2 id="cat-<?= e($group['category']['slug'] ?? 'otros') ?>">
                        <?php if (!empty($group['category']['slug'])): ?>
                            <a href="<?= e('/productos/' . $group['category']['slug']) ?>">
                                <?= e($group['category']['name']) ?>
                            </a>
                        <?php else: ?>
                            <?= e($group['category']['name']) ?>
                        <?php endif; ?>
                    </h2>
                    <?php if ((int)$group['total'] > count($group['products'])): ?>
                        <a class="category-group-more" href="<?= e('/productos/' . $group['category']['slug']) ?>">
                            Ver los <?= (int)$group['total'] ?> →
                        </a>
                    <?php endif; ?>
                </div>
                <?php if (!empty($group['category']['description'])): ?>
                    <p class="category-group-desc"><?= e(excerpt($group['category']['description'], 200)) ?></p>
                <?php endif; ?>
                <div class="grid grid-cards">
                    <?php foreach ($group['products'] as $p): ?>
                        <?= $view->partial('product_card', ['product' => $p]) ?>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endforeach; ?>
    <?php elseif (!empty($products)): ?>
        <div class="grid grid-cards">
            <?php foreach ($products as $p): ?>
                <?= $view->partial('product_card', ['product' => $p]) ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="empty-state">No hay productos que coincidan con estos filtros. <a href="<?= e($basePath) ?>">Limpiar filtros</a>.</p>
    <?php endif; ?>

    <?php if (($view_mode ?? 'flat') === 'flat' && $pages > 1): ?>
        <nav class="pagination" aria-label="Paginación">
            <?php for ($i = 1; $i <= $pages; $i++): ?>
                <?php if ($i === $page): ?>
                    <span class="current"><?= e($i) ?></span>
                <?php else: ?>
                    <a href="<?= e($buildUrl(['page' => $i])) ?>"><?= e($i) ?></a>
                <?php endif; ?>
            <?php endfor; ?>
        </nav>
    <?php endif; ?>
</section>
