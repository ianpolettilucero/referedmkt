<?php
/**
 * @var \Core\Site $site
 * @var \Core\SEO  $seo
 * @var string     $content
 */
?>
<!DOCTYPE html>
<html lang="<?= e($site->defaultLanguage) ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= $seo->renderHead() ?>
    <?php if ($site->faviconUrl): ?>
    <link rel="icon" href="<?= e($site->faviconUrl) ?>">
    <?php endif; ?>
    <link rel="stylesheet" href="<?= e(theme_asset('css/site.css')) ?>">
    <link rel="alternate" type="application/rss+xml" title="<?= e($site->name) ?>" href="/feed.xml">
    <?php if ($site->googleAnalyticsId): ?>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= e($site->googleAnalyticsId) ?>"></script>
    <script>
      window.dataLayer=window.dataLayer||[];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', <?= json_encode($site->googleAnalyticsId) ?>, {anonymize_ip:true});
    </script>
    <?php endif; ?>
</head>
<body>
    <?= $view->partial('header') ?>

    <?php if ($seo->getBreadcrumb()): ?>
    <nav class="breadcrumb container" aria-label="breadcrumb">
        <ol>
            <?php foreach ($seo->getBreadcrumb() as $i => $crumb): ?>
                <?php [$name, $path] = $crumb; ?>
                <?php if ($i < count($seo->getBreadcrumb()) - 1): ?>
                    <li><a href="<?= e($path) ?>"><?= e($name) ?></a></li>
                <?php else: ?>
                    <li aria-current="page"><?= e($name) ?></li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ol>
    </nav>
    <?php endif; ?>

    <main class="container">
        <?= $content ?>
    </main>

    <?= $view->partial('affiliate_disclosure') ?>
    <?= $view->partial('footer') ?>
</body>
</html>
