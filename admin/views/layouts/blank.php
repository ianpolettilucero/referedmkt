<?php
/**
 * @var string      $content
 * @var string|null $page_title
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
<body class="admin-body admin-body-blank">
    <main class="admin-blank-main">
        <?= $content ?>
    </main>
</body>
</html>
