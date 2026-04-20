<?php
/**
 * @var \Core\View $view
 * @var array  $articles
 * @var int    $total
 * @var int    $page
 * @var int    $per_page
 * @var string $type
 * @var string $label
 * @var array  $filters
 * @var array  $sorts
 * @var array  $categories
 */
$view->layout('default');
$pages = (int)ceil($total / max(1, $per_page));
$basePath = match ($type) {
    'review'     => '/resenas',
    'comparison' => '/comparativas',
    'news'       => '/noticias',
    default      => '/guias',
};
$buildUrl = function (array $override = []) use ($basePath, $filters) {
    $params = [];
    if (!empty($filters['sort']) && $filters['sort'] !== 'recent') { $params['sort'] = $filters['sort']; }
    $params = array_merge($params, $override);
    return $basePath . ($params ? '?' . http_build_query($params) : '');
};
?>
<section class="article-list-page">
    <header>
        <h1><?= e($label) ?></h1>
        <p class="muted"><?= e($total) ?> publicados</p>
    </header>

    <form method="get" action="<?= e($basePath) ?>" class="filters-bar" aria-label="Filtros">
        <?php if ($categories): ?>
            <div class="filter-group">
                <label for="f-cat">Categoría</label>
                <select name="cat" id="f-cat" onchange="this.form.submit()">
                    <option value="">Todas</option>
                    <?php foreach ($categories as $c): ?>
                        <option value="<?= e($c['slug']) ?>" <?= ((int)($filters['category_id'] ?? 0) === (int)$c['id']) ? 'selected' : '' ?>>
                            <?= e($c['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php endif; ?>

        <div class="filter-group filter-group-sort">
            <label for="f-sort">Ordenar</label>
            <select name="sort" id="f-sort" onchange="this.form.submit()">
                <?php foreach ($sorts as $value => $labelSort): ?>
                    <option value="<?= e($value) ?>" <?= ($filters['sort'] ?? 'recent') === $value ? 'selected' : '' ?>>
                        <?= e($labelSort) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <?php if (!empty($filters['category_id']) || (!empty($filters['sort']) && $filters['sort'] !== 'recent')): ?>
            <a class="filter-clear" href="<?= e($basePath) ?>">Limpiar filtros</a>
        <?php endif; ?>
    </form>

    <?php if ($articles): ?>
        <div class="grid grid-cards">
            <?php foreach ($articles as $a): ?>
                <?= $view->partial('article_card', ['article' => $a]) ?>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p class="empty-state">Sin publicaciones que coincidan. <a href="<?= e($basePath) ?>">Limpiar filtros</a>.</p>
    <?php endif; ?>

    <?php if ($pages > 1): ?>
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
