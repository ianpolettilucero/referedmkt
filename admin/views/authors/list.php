<?php
/** @var \Admin\AdminView $view
 *  @var array  $rows
 *  @var string $csrf_token
 */
$view->layout('admin');
?>
<div class="admin-page-header">
    <h1 class="admin-page-title">Autores</h1>
    <a class="admin-btn admin-btn-primary" href="/admin/authors/new">+ Nuevo autor</a>
</div>

<?php if (!$rows): ?>
    <div class="admin-card admin-empty">Sin autores.</div>
<?php else: ?>
    <table class="admin-table">
        <thead><tr><th>Nombre</th><th>Slug</th><th>Expertise</th><th>Acciones</th></tr></thead>
        <tbody>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($r['name'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                    <td><code><?= htmlspecialchars($r['slug'], ENT_QUOTES, 'UTF-8') ?></code></td>
                    <td><?= htmlspecialchars($r['expertise'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td class="admin-row-actions">
                        <a class="admin-btn admin-btn-subtle" href="/admin/authors/<?= (int)$r['id'] ?>/edit">Editar</a>
                        <form method="post" action="/admin/authors/<?= (int)$r['id'] ?>/delete" class="admin-inline-form" onsubmit="return confirm('Eliminar autor?')">
                            <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                            <button class="admin-btn admin-btn-subtle" style="color:var(--a-danger)">Borrar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
