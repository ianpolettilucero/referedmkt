<?php
/** @var \Admin\AdminView $view
 *  @var array  $rows
 *  @var string $csrf_token
 */
$view->layout('admin');
?>
<div class="admin-page-header">
    <h1 class="admin-page-title">Redirects</h1>
    <a class="admin-btn admin-btn-primary" href="/admin/redirects/new">+ Nuevo redirect</a>
</div>

<?php if (!$rows): ?>
    <div class="admin-card admin-empty">Sin redirects.</div>
<?php else: ?>
    <table class="admin-table">
        <thead><tr><th>Desde</th><th>Hacia</th><th>Código</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td><code><?= htmlspecialchars($r['from_path'], ENT_QUOTES, 'UTF-8') ?></code></td>
                    <td><code><?= htmlspecialchars($r['to_path'], ENT_QUOTES, 'UTF-8') ?></code></td>
                    <td><?= (int)$r['status_code'] ?></td>
                    <td><?= $r['active']
                        ? '<span class="admin-badge admin-badge-success">activo</span>'
                        : '<span class="admin-badge admin-badge-danger">inactivo</span>' ?></td>
                    <td class="admin-row-actions">
                        <a class="admin-btn admin-btn-subtle" href="/admin/redirects/<?= (int)$r['id'] ?>/edit">Editar</a>
                        <form method="post" action="/admin/redirects/<?= (int)$r['id'] ?>/delete" class="admin-inline-form" onsubmit="return confirm('Eliminar redirect?')">
                            <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                            <button class="admin-btn admin-btn-subtle" style="color:var(--a-danger)">Borrar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
