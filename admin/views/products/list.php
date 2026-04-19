<?php
/** @var \Admin\AdminView $view
 *  @var array  $rows
 *  @var string $csrf_token
 */
$view->layout('admin');
?>
<div class="admin-page-header">
    <h1 class="admin-page-title">Productos</h1>
    <a class="admin-btn admin-btn-primary" href="/admin/products/new">+ Nuevo producto</a>
</div>

<?php if (!$rows): ?>
    <div class="admin-card admin-empty">Sin productos.</div>
<?php else: ?>
    <table class="admin-table">
        <thead>
            <tr><th>Nombre</th><th>Marca</th><th>Categoría</th><th>Rating</th><th>Precio</th><th>Destacado</th><th>Acciones</th></tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $r): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($r['name'], ENT_QUOTES, 'UTF-8') ?></strong><br>
                        <small class="admin-muted"><?= htmlspecialchars($r['slug'], ENT_QUOTES, 'UTF-8') ?></small>
                    </td>
                    <td><?= htmlspecialchars($r['brand'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($r['category_name'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= $r['rating'] !== null ? htmlspecialchars(number_format((float)$r['rating'], 1), ENT_QUOTES, 'UTF-8') : '—' ?></td>
                    <td><?= $r['price_from'] !== null ? htmlspecialchars(($r['price_currency'] ?? '') . ' ' . number_format((float)$r['price_from'], 2), ENT_QUOTES, 'UTF-8') : '—' ?></td>
                    <td><?= $r['featured'] ? '<span class="admin-badge admin-badge-success">sí</span>' : '' ?></td>
                    <td class="admin-row-actions">
                        <a class="admin-btn admin-btn-subtle" href="/admin/products/<?= (int)$r['id'] ?>/edit">Editar</a>
                        <form method="post" action="/admin/products/<?= (int)$r['id'] ?>/delete" class="admin-inline-form" onsubmit="return confirm('Eliminar producto?')">
                            <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                            <button class="admin-btn admin-btn-subtle" style="color:var(--a-danger)">Borrar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
