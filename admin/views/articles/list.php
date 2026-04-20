<?php
/** @var \Admin\AdminView $view
 *  @var array  $rows
 *  @var ?string $type  Filtro activo (null = todos)
 *  @var array  $counts  ['all', 'guide', 'review', 'comparison', 'news']
 *  @var string $csrf_token
 */
$view->layout('admin');
$statusBadge = function ($s) {
    return match ($s) {
        'published' => '<span class="admin-badge admin-badge-success">publicado</span>',
        'archived'  => '<span class="admin-badge admin-badge-danger">archivado</span>',
        default     => '<span class="admin-badge admin-badge-warning">borrador</span>',
    };
};
$typeLabel = function ($t) {
    return match ($t) {
        'guide'      => 'Guía',
        'review'     => 'Reseña',
        'comparison' => 'Comparativa',
        'news'       => 'Noticia',
        default      => $t,
    };
};
$activeTab = $type ?? 'all';
?>
<div class="admin-page-header">
    <h1 class="admin-page-title">
        <?php if ($type === 'review'): ?>Reseñas
        <?php elseif ($type === 'comparison'): ?>Comparativas
        <?php elseif ($type === 'guide'): ?>Guías
        <?php elseif ($type === 'news'): ?>Noticias
        <?php else: ?>Artículos<?php endif; ?>
    </h1>
    <div class="admin-create-buttons">
        <a class="admin-btn" href="/admin/articles/new?type=guide">+ Guía</a>
        <a class="admin-btn" href="/admin/articles/new?type=comparison">+ Comparativa</a>
        <a class="admin-btn" href="/admin/articles/new?type=review">+ Reseña</a>
        <a class="admin-btn" href="/admin/articles/new?type=news">+ Noticia</a>
    </div>
</div>

<nav class="admin-tabs" aria-label="Filtrar por tipo">
    <a class="admin-tab<?= $activeTab === 'all' ? ' is-active' : '' ?>" href="/admin/articles">
        Todos <span class="admin-tab-count"><?= (int)$counts['all'] ?></span>
    </a>
    <a class="admin-tab<?= $activeTab === 'guide' ? ' is-active' : '' ?>" href="/admin/articles?type=guide">
        Guías <span class="admin-tab-count"><?= (int)$counts['guide'] ?></span>
    </a>
    <a class="admin-tab<?= $activeTab === 'comparison' ? ' is-active' : '' ?>" href="/admin/articles?type=comparison">
        Comparativas <span class="admin-tab-count"><?= (int)$counts['comparison'] ?></span>
    </a>
    <a class="admin-tab<?= $activeTab === 'review' ? ' is-active' : '' ?>" href="/admin/articles?type=review">
        Reseñas <span class="admin-tab-count"><?= (int)$counts['review'] ?></span>
    </a>
    <a class="admin-tab<?= $activeTab === 'news' ? ' is-active' : '' ?>" href="/admin/articles?type=news">
        Noticias <span class="admin-tab-count"><?= (int)$counts['news'] ?></span>
    </a>
</nav>

<?php if (!$rows): ?>
    <div class="admin-card admin-empty">
        <?php if ($type): ?>
            Sin <?= htmlspecialchars(strtolower($typeLabel($type)), ENT_QUOTES, 'UTF-8') ?>s todavía.
            <a href="/admin/articles/new?type=<?= htmlspecialchars($type, ENT_QUOTES, 'UTF-8') ?>">Creá la primera</a>.
        <?php else: ?>
            Sin artículos. <a href="/admin/articles/new?type=guide">Empezá con una guía</a>.
        <?php endif; ?>
    </div>
<?php else: ?>
    <table class="admin-table">
        <thead>
            <tr><th>Título</th><th>Tipo</th><th>Categoría</th><th>Autor</th><th>Estado</th><th>Fecha</th><th>Acciones</th></tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td>
                        <strong><?= htmlspecialchars($r['title'], ENT_QUOTES, 'UTF-8') ?></strong><br>
                        <small class="admin-muted"><?= htmlspecialchars($r['slug'], ENT_QUOTES, 'UTF-8') ?></small>
                    </td>
                    <td><?= htmlspecialchars($typeLabel($r['article_type']), ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($r['category_name'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($r['author_name'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= $statusBadge($r['status']) ?></td>
                    <td><?= $r['published_at'] ? htmlspecialchars(date('Y-m-d', strtotime($r['published_at'])), ENT_QUOTES, 'UTF-8') : '—' ?></td>
                    <td class="admin-row-actions">
                        <a class="admin-btn admin-btn-subtle" href="/admin/articles/<?= (int)$r['id'] ?>/edit">Editar</a>
                        <form method="post" action="/admin/articles/<?= (int)$r['id'] ?>/delete" class="admin-inline-form" onsubmit="return confirm('Eliminar articulo?')">
                            <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                            <button class="admin-btn admin-btn-subtle" style="color:var(--a-danger)">Borrar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
