<?php
/** @var array $user @var array|null $active_site */
function admin_nav_active(string $href): string {
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
    if ($href === '/admin' || $href === '/admin/dashboard') {
        return in_array($path, ['/admin', '/admin/dashboard'], true) ? ' is-active' : '';
    }
    return str_starts_with($path, $href) ? ' is-active' : '';
}
// Iconos SVG inline. Todos: stroke-width 2, 24x24, current color.
$icon = [
    'dashboard'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>',
    'articles'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="13"/><line x1="8" y1="17" x2="13" y2="17"/></svg>',
    'products'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.91 8.84L8.56 2.23a1.93 1.93 0 0 0-1.81 0L3.1 4.13a2.12 2.12 0 0 0-.05 3.69l12.22 6.93a2 2 0 0 0 1.94 0L21 12.51a2.12 2.12 0 0 0-.09-3.67z"/><path d="M3.09 8.84v12.3a2 2 0 0 0 1 1.74L12 23"/><path d="M21 8.85v12.3a2 2 0 0 1-1 1.74L12 23"/></svg>',
    'categories'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>',
    'authors'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
    'affiliates'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>',
    'uploads'     => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>',
    'redirects'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 10 20 15 15 20"/><path d="M4 4v7a4 4 0 0 0 4 4h12"/></svg>',
    'analytics'   => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>',
    'settings'    => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>',
    'sites'       => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>',
];
?>
<aside class="admin-sidebar" aria-label="Navegación principal">
    <a class="admin-sidebar-brand" href="/admin/dashboard">referedmkt</a>

    <div class="admin-nav-group">
        <a class="admin-nav-link<?= admin_nav_active('/admin/dashboard') ?>" href="/admin/dashboard">
            <?= $icon['dashboard'] ?><span>Dashboard</span>
        </a>
    </div>

    <div class="admin-nav-group">
        <div class="admin-nav-group-label">Contenido</div>
        <a class="admin-nav-link<?= admin_nav_active('/admin/articles') ?>" href="/admin/articles">
            <?= $icon['articles'] ?><span>Artículos</span>
        </a>
        <a class="admin-nav-link<?= admin_nav_active('/admin/products') ?>" href="/admin/products">
            <?= $icon['products'] ?><span>Productos</span>
        </a>
        <a class="admin-nav-link<?= admin_nav_active('/admin/categories') ?>" href="/admin/categories">
            <?= $icon['categories'] ?><span>Categorías</span>
        </a>
        <a class="admin-nav-link<?= admin_nav_active('/admin/authors') ?>" href="/admin/authors">
            <?= $icon['authors'] ?><span>Autores</span>
        </a>
    </div>

    <div class="admin-nav-group">
        <div class="admin-nav-group-label">Monetización</div>
        <a class="admin-nav-link<?= admin_nav_active('/admin/affiliate-links') ?>" href="/admin/affiliate-links">
            <?= $icon['affiliates'] ?><span>Afiliados</span>
        </a>
        <a class="admin-nav-link<?= admin_nav_active('/admin/analytics') ?>" href="/admin/analytics">
            <?= $icon['analytics'] ?><span>Analytics</span>
        </a>
    </div>

    <div class="admin-nav-group">
        <div class="admin-nav-group-label">Medios</div>
        <a class="admin-nav-link<?= admin_nav_active('/admin/uploads') ?>" href="/admin/uploads">
            <?= $icon['uploads'] ?><span>Imágenes</span>
        </a>
    </div>

    <div class="admin-nav-group">
        <div class="admin-nav-group-label">Configuración</div>
        <a class="admin-nav-link<?= admin_nav_active('/admin/settings') ?>" href="/admin/settings">
            <?= $icon['settings'] ?><span>Settings del sitio</span>
        </a>
        <a class="admin-nav-link<?= admin_nav_active('/admin/sites') ?>" href="/admin/sites">
            <?= $icon['sites'] ?><span>Sitios</span>
        </a>
        <a class="admin-nav-link<?= admin_nav_active('/admin/redirects') ?>" href="/admin/redirects">
            <?= $icon['redirects'] ?><span>Redirects</span>
        </a>
        <a class="admin-nav-link<?= admin_nav_active('/admin/link-health') ?>" href="/admin/link-health">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
            <span>Health de links</span>
        </a>
        <a class="admin-nav-link<?= admin_nav_active('/admin/index-health') ?>" href="/admin/index-health">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <span>Health de indexación</span>
        </a>
        <a class="admin-nav-link<?= admin_nav_active('/admin/security') ?>" href="/admin/security">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            <span>Seguridad</span>
        </a>
    </div>

    <div class="admin-sidebar-footer">
        <?php if ($active_site): ?>
            Sitio activo:<br>
            <strong style="color:var(--a-text)"><?= htmlspecialchars($active_site['name'], ENT_QUOTES, 'UTF-8') ?></strong>
        <?php else: ?>
            Sin sitio seleccionado
        <?php endif; ?>
    </div>
</aside>
