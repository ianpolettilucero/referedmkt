<?php
/** @var \Admin\AdminView $view
 *  @var array  $rows
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
?>
<div class="admin-page-header">
    <h1 class="admin-page-title">Artículos</h1>
    <a class="admin-btn admin-btn-primary" href="/admin/articles/new">+ Nuevo artículo</a>
</div>

<?php if (!$rows): ?>
    <div class="admin-card admin-empty">Sin artículos.</div>
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
                    <td><?= htmlspecialchars($r['article_type'], ENT_QUOTES, 'UTF-8') ?></td>
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
