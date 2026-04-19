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
        <?= $content ?>
    </main>

    <footer class="admin-footer">
        <small>referedmkt admin</small>
    </footer>
</body>
</html>
