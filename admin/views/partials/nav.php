<?php
/**
 * @var array      $user
 * @var array|null $active_site
 * @var array      $sites
 * @var string     $csrf_token
 */
function admin_nav_active(string $href): string {
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
    if ($href === '/admin' || $href === '/admin/dashboard') {
        return in_array($path, ['/admin', '/admin/dashboard'], true) ? ' is-active' : '';
    }
    return str_starts_with($path, $href) ? ' is-active' : '';
}
?>
<header class="admin-nav">
    <div class="admin-nav-inner">
        <a class="admin-brand" href="/admin">referedmkt</a>

        <nav class="admin-nav-links">
            <a class="admin-nav-link<?= admin_nav_active('/admin/dashboard') ?>" href="/admin/dashboard">Dashboard</a>
            <a class="admin-nav-link<?= admin_nav_active('/admin/sites') ?>" href="/admin/sites">Sitios</a>
            <a class="admin-nav-link<?= admin_nav_active('/admin/categories') ?>" href="/admin/categories">Categorías</a>
            <a class="admin-nav-link<?= admin_nav_active('/admin/products') ?>" href="/admin/products">Productos</a>
            <a class="admin-nav-link<?= admin_nav_active('/admin/articles') ?>" href="/admin/articles">Artículos</a>
            <a class="admin-nav-link<?= admin_nav_active('/admin/authors') ?>" href="/admin/authors">Autores</a>
            <a class="admin-nav-link<?= admin_nav_active('/admin/affiliate-links') ?>" href="/admin/affiliate-links">Afiliados</a>
            <a class="admin-nav-link<?= admin_nav_active('/admin/redirects') ?>" href="/admin/redirects">Redirects</a>
            <a class="admin-nav-link<?= admin_nav_active('/admin/analytics') ?>" href="/admin/analytics">Analytics</a>
        </nav>

        <div class="admin-nav-right">
            <?php if ($sites): ?>
                <form method="post" action="/admin/switch-site" class="admin-site-switcher">
                    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                    <label for="active_site">Sitio:</label>
                    <select name="site_id" id="active_site" onchange="this.form.submit()">
                        <?php foreach ($sites as $s): ?>
                            <option value="<?= (int)$s['id'] ?>"
                                <?= ($active_site && (int)$active_site['id'] === (int)$s['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s['name'], ENT_QUOTES, 'UTF-8') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            <?php endif; ?>

            <form method="post" action="/admin/logout" class="admin-logout">
                <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                <span class="admin-user-label"><?= htmlspecialchars($user['name'] ?? $user['email'] ?? '', ENT_QUOTES, 'UTF-8') ?></span>
                <button type="submit" class="admin-btn admin-btn-subtle">Salir</button>
            </form>
        </div>
    </div>
</header>
