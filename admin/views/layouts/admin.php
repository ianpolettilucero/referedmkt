<?php
/**
 * @var \Admin\AdminView $view
 * @var array            $user
 * @var array|null       $active_site
 * @var array            $sites
 * @var string           $csrf_token
 * @var array            $flashes
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
    <title><?= htmlspecialchars(($page_title ?? 'Admin') . ' · referedmkt', ENT_QUOTES, 'UTF-8') ?></title>
    <link rel="stylesheet" href="/admin-assets/admin.css">
</head>
<body class="admin-body">
    <?= $view->partial('nav', compact('user', 'active_site', 'sites', 'csrf_token')) ?>

    <main class="admin-main">
        <?= $view->partial('flash', ['flashes' => $flashes]) ?>
        <?php if (!empty($pending_migrations) && (int)$pending_migrations > 0): ?>
            <div class="admin-flash admin-flash-info" style="margin-bottom:1rem;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:0.5rem">
                <span>
                    Hay <strong><?= (int)$pending_migrations ?></strong> migracion(es) pendiente(s).
                    Aplicalas para mantener el schema al dia.
                </span>
                <form method="post" action="/admin/maintenance/migrate" style="margin:0">
                    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                    <button type="submit" class="admin-btn admin-btn-primary">Aplicar migraciones</button>
                </form>
            </div>
        <?php endif; ?>
        <?= $content ?>
    </main>

    <footer class="admin-footer">
        <small>referedmkt admin</small>
    </footer>
</body>
</html>
