<?php
/** @var array $user
 *  @var array|null $active_site
 *  @var array $sites
 *  @var string $csrf_token
 */
?>
<header class="admin-topbar">
    <div class="admin-topbar-left">
        <button type="button" class="admin-mobile-toggle" data-sidebar-toggle aria-label="Abrir menú">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <line x1="3" y1="6" x2="21" y2="6"/>
                <line x1="3" y1="12" x2="21" y2="12"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>
        <?php if ($active_site): ?>
            <a href="https://<?= htmlspecialchars($active_site['domain'], ENT_QUOTES, 'UTF-8') ?>"
               target="_blank" rel="noopener"
               class="admin-btn admin-btn-subtle"
               title="Abrir sitio público en una pestaña nueva">
                ↗ <?= htmlspecialchars($active_site['domain'], ENT_QUOTES, 'UTF-8') ?>
            </a>
        <?php endif; ?>
    </div>

    <div class="admin-topbar-right">
        <?php if (count($sites) > 1): ?>
            <form method="post" action="/admin/switch-site" class="admin-site-switcher">
                <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                <label for="active_site">Sitio</label>
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

        <form method="post" action="/admin/logout" class="admin-user-menu">
            <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
            <span class="admin-user-label">
                <?= htmlspecialchars($user['name'] ?? $user['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>
            </span>
            <button type="submit" class="admin-btn admin-btn-subtle" title="Cerrar sesión">Salir</button>
        </form>
    </div>
</header>
