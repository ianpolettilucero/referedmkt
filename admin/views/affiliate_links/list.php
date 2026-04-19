<?php
/** @var \Admin\AdminView $view
 *  @var array  $rows
 *  @var array|null $active_site
 *  @var string $csrf_token
 */
$view->layout('admin');
$domain = $active_site['domain'] ?? '';
?>
<div class="admin-page-header">
    <h1 class="admin-page-title">Afiliados</h1>
    <a class="admin-btn admin-btn-primary" href="/admin/affiliate-links/new">+ Nuevo afiliado</a>
</div>

<?php if (!$rows): ?>
    <div class="admin-card admin-empty">Sin afiliados.</div>
<?php else: ?>
    <table class="admin-table">
        <thead>
            <tr><th>Nombre</th><th>Red</th><th>URL tracking</th><th>Clicks 30d</th><th>Total</th><th>Estado</th><th>Acciones</th></tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $r): ?>
                <?php $trackUrl = 'https://' . $domain . '/go/' . $r['tracking_slug']; ?>
                <tr>
                    <td><strong><?= htmlspecialchars($r['name'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                    <td><?= htmlspecialchars($r['network_name'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <code style="font-size:0.8rem"><?= htmlspecialchars($trackUrl, ENT_QUOTES, 'UTF-8') ?></code>
                    </td>
                    <td><?= (int)($r['clicks_30d'] ?? 0) ?></td>
                    <td><?= (int)($r['clicks_total'] ?? 0) ?></td>
                    <td>
                        <?php if ($r['active']): ?>
                            <span class="admin-badge admin-badge-success">activo</span>
                        <?php else: ?>
                            <span class="admin-badge admin-badge-danger">inactivo</span>
                        <?php endif; ?>
                    </td>
                    <td class="admin-row-actions">
                        <a class="admin-btn admin-btn-subtle" href="/admin/affiliate-links/<?= (int)$r['id'] ?>/edit">Editar</a>
                        <form method="post" action="/admin/affiliate-links/<?= (int)$r['id'] ?>/delete" class="admin-inline-form" onsubmit="return confirm('Eliminar afiliado?')">
                            <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                            <button class="admin-btn admin-btn-subtle" style="color:var(--a-danger)">Borrar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
