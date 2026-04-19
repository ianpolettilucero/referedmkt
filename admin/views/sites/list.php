<?php
/** @var \Admin\AdminView $view
 *  @var array $sites_list
 *  @var string $csrf_token
 */
$view->layout('admin');
?>
<div class="admin-page-header">
    <h1 class="admin-page-title">Sitios</h1>
    <a class="admin-btn admin-btn-primary" href="/admin/sites/new">+ Nuevo sitio</a>
</div>

<?php if (!$sites_list): ?>
    <div class="admin-card admin-empty">Sin sitios todavía.</div>
<?php else: ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Dominio</th>
                <th>Slug</th>
                <th>Tema</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($sites_list as $s): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($s['name'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                    <td><a href="https://<?= htmlspecialchars($s['domain'], ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener"><?= htmlspecialchars($s['domain'], ENT_QUOTES, 'UTF-8') ?></a></td>
                    <td><?= htmlspecialchars($s['slug'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($s['theme_name'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <?php if ($s['active']): ?>
                            <span class="admin-badge admin-badge-success">activo</span>
                        <?php else: ?>
                            <span class="admin-badge admin-badge-danger">inactivo</span>
                        <?php endif; ?>
                    </td>
                    <td class="admin-row-actions">
                        <a class="admin-btn admin-btn-subtle" href="/admin/sites/<?= (int)$s['id'] ?>/edit">Editar</a>
                        <form method="post" action="/admin/sites/<?= (int)$s['id'] ?>/delete" class="admin-inline-form"
                              onsubmit="return confirm('Eliminar este sitio? Se borrara todo su contenido (productos, articulos, etc.).')">
                            <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                            <button type="submit" class="admin-btn admin-btn-subtle" style="color:var(--a-danger)">Borrar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
