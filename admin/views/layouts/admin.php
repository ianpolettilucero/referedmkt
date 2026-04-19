<?php
/**
 * @var \Admin\AdminView $view
 * @var array            $user
 * @var array|null       $active_site
 * @var array            $sites
 * @var string           $csrf_token
 * @var array            $flashes
 * @var int              $pending_migrations
 * @var string           $content
 * @var string|null      $page_title
 */
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="color-scheme" content="dark">
    <meta name="theme-color" content="#0b0f1a">
    <title><?= htmlspecialchars(($page_title ?? 'Admin') . ' · referedmkt', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/admin-assets/admin.css">
</head>
<body class="admin-body">
    <div class="admin-shell" id="adminShell">
        <?= $view->partial('sidebar', compact('user', 'active_site')) ?>
        <?= $view->partial('topbar', compact('user', 'active_site', 'sites', 'csrf_token')) ?>

        <main class="admin-main">
            <?= $view->partial('flash', ['flashes' => $flashes]) ?>

            <?php if (!empty($pending_migrations) && (int)$pending_migrations > 0): ?>
                <div class="admin-flash admin-flash-info" style="margin-bottom:1rem">
                    <span>
                        Hay <strong><?= (int)$pending_migrations ?></strong> migración(es) pendiente(s).
                    </span>
                    <form method="post" action="/admin/maintenance/migrate" style="margin:0">
                        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                        <button type="submit" class="admin-btn admin-btn-primary">Aplicar migraciones</button>
                    </form>
                </div>
            <?php endif; ?>

            <?= $content ?>
        </main>
    </div>

    <script>
        // Sidebar toggle en mobile.
        (function () {
            var shell = document.getElementById('adminShell');
            document.addEventListener('click', function (e) {
                if (e.target.closest('[data-sidebar-toggle]')) {
                    shell.setAttribute('data-sidebar-open',
                        shell.getAttribute('data-sidebar-open') === 'true' ? 'false' : 'true');
                } else if (shell.getAttribute('data-sidebar-open') === 'true'
                           && !e.target.closest('.admin-sidebar')) {
                    shell.setAttribute('data-sidebar-open', 'false');
                }
            });
        })();
    </script>
</body>
</html>
