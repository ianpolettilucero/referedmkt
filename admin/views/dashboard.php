<?php
/**
 * @var \Admin\AdminView $view
 * @var array|null $active_site
 * @var array      $stats
 */
$view->layout('admin');
?>
<div class="admin-page-header">
    <h1 class="admin-page-title">Dashboard</h1>
    <?php if ($active_site): ?>
        <span class="admin-muted">Sitio activo: <strong><?= htmlspecialchars($active_site['name'], ENT_QUOTES, 'UTF-8') ?></strong> (<?= htmlspecialchars($active_site['domain'], ENT_QUOTES, 'UTF-8') ?>)</span>
    <?php endif; ?>
</div>

<?php if (!$active_site): ?>
    <div class="admin-card">
        <p>No hay sitio seleccionado. <a href="/admin/sites">Crear uno</a> o elegir uno existente en el selector de arriba.</p>
    </div>
<?php else: ?>
    <?php if (!empty($stats['broken_links'])): ?>
        <div class="admin-flash admin-flash-error" style="margin-bottom:1rem">
            <span>
                ⚠ Hay <strong><?= (int)$stats['broken_links'] ?></strong> link(s) roto(s) en tus artículos.
            </span>
            <a class="admin-btn admin-btn-primary" href="/admin/link-health">Revisar</a>
        </div>
    <?php endif; ?>

    <div class="admin-stats">
        <div class="admin-stat">
            <div class="admin-stat-value"><?= (int)$stats['products'] ?></div>
            <div class="admin-stat-label">Productos</div>
        </div>
        <div class="admin-stat">
            <div class="admin-stat-value"><?= (int)$stats['articles'] ?></div>
            <div class="admin-stat-label">Artículos publicados</div>
        </div>
        <div class="admin-stat">
            <div class="admin-stat-value"><?= (int)$stats['affiliate_links'] ?></div>
            <div class="admin-stat-label">Afiliados activos</div>
        </div>
        <div class="admin-stat">
            <div class="admin-stat-value"><?= (int)$stats['clicks_30d'] ?></div>
            <div class="admin-stat-label">Clicks últimos 30d</div>
        </div>
    </div>

    <div class="admin-card" style="margin-top:1rem;">
        <h2 style="margin:0 0 0.5rem;">Accesos rápidos</h2>
        <p>
            <a class="admin-btn" href="/admin/articles/new">+ Nuevo artículo</a>
            <a class="admin-btn" href="/admin/products/new">+ Nuevo producto</a>
            <a class="admin-btn" href="/admin/affiliate-links/new">+ Nuevo afiliado</a>
            <a class="admin-btn" href="/admin/analytics">Ver analytics</a>
        </p>
    </div>

    <div class="admin-card" style="margin-top:1rem;">
        <h2 style="margin:0 0 0.5rem;">Mantenimiento</h2>
        <p class="admin-muted" style="margin-top:0">Backups on-demand. Descargá un dump <code>.sql.gz</code> antes de cambios importantes.</p>
        <p>
            <a class="admin-btn" href="/admin/maintenance/backup">Descargar backup de DB</a>
        </p>
    </div>
<?php endif; ?>
