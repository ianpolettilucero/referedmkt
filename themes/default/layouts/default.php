<?php
/**
 * @var \Core\Site $site
 * @var \Core\SEO  $seo
 * @var string     $content
 */
?>
<!DOCTYPE html>
<html lang="<?= e($site->defaultLanguage) ?>" data-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="dark light">
    <?php if (!empty($site->primaryColor)): ?>
    <meta name="theme-color" content="<?= e($site->primaryColor) ?>">
    <?php else: ?>
    <meta name="theme-color" content="#0a0e1a">
    <?php endif; ?>
    <?= $seo->renderHead() ?>
    <?php if ($site->faviconUrl): ?>
    <link rel="icon" href="<?= e($site->faviconUrl) ?>">
    <?php endif; ?>
    <?php if ($site->googleAnalyticsId || $site->googleTagManagerId): ?>
    <!-- Performance hints: pre-conectar al CDN de Google Analytics/GTM -->
    <link rel="preconnect" href="https://www.googletagmanager.com" crossorigin>
    <link rel="dns-prefetch" href="https://www.google-analytics.com">
    <?php endif; ?>
    <link rel="stylesheet" href="<?= e(theme_asset('css/site.css')) ?>">
    <?php
    // Preset visual seleccionado desde /admin/settings (theme_preset).
    $preset = (string)\Core\Settings::get($site->id, 'theme_preset', 'indigo-night');
    if ($preset !== '' && $preset !== 'indigo-night' && \Core\ThemePresets::exists($preset)):
    ?>
    <link rel="stylesheet" href="<?= e(theme_asset('css/presets/' . $preset . '.css')) ?>">
    <?php endif; ?>
    <link rel="alternate" type="application/rss+xml" title="<?= e($site->name) ?>" href="/feed.xml">
    <?php
    // CSS personalizado por sitio (override final, despues del preset).
    $customCss = (string)\Core\Settings::get($site->id, 'custom_css', '');
    if ($customCss !== ''):
        // Bloquear cierre de </style> embebido (defensa XSS minima).
        $customCss = str_replace('</style>', '<\\/style>', $customCss);
    ?>
    <style id="refmkt-custom-css"><?= $customCss ?></style>
    <?php endif; ?>
    <script>
        // Anti-FOUC: aplicar tema guardado antes del render.
        (function () {
            try {
                var t = localStorage.getItem('refmkt-theme');
                if (t === 'light' || t === 'dark') {
                    document.documentElement.setAttribute('data-theme', t);
                }
            } catch (e) {}
        })();
    </script>
    <?php if ($site->googleAnalyticsId): ?>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= e($site->googleAnalyticsId) ?>"
            onerror="console.warn('[GA4] gtag.js no cargo para ID ' + <?= json_encode($site->googleAnalyticsId) ?> + '. Verificar en analytics.google.com que el Data Stream este activo y reciba datos, o esperar hasta 24h si el stream es nuevo.');"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', <?= json_encode($site->googleAnalyticsId) ?>);
    </script>
    <?php endif; ?>
    <?php if ($site->googleTagManagerId): ?>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer',<?= json_encode($site->googleTagManagerId) ?>);</script>
    <!-- End Google Tag Manager -->
    <?php endif; ?>
</head>
<body>
    <?php if ($site->googleTagManagerId): ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?= e($site->googleTagManagerId) ?>"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php endif; ?>
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

    <script src="<?= e(theme_asset('js/theme.js')) ?>" defer></script>
</body>
</html>
