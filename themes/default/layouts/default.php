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
    <?php if ($site->googleAnalyticsId || $site->googleTagManagerId || $site->googleAdsId): ?>
    <!-- Performance hints: pre-conectar al CDN de Google Analytics/GTM/Ads -->
    <link rel="preconnect" href="https://www.googletagmanager.com" crossorigin>
    <link rel="dns-prefetch" href="https://www.google-analytics.com">
    <?php endif; ?>
    <?php if ($site->microsoftClarityId): ?>
    <link rel="dns-prefetch" href="https://www.clarity.ms">
    <?php endif; ?>
    <?php if ($site->metaPixelId): ?>
    <link rel="dns-prefetch" href="https://connect.facebook.net">
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
    <?php
    // gtag.js: cargar UNA sola vez si hay GA4 o Google Ads, despues config
    // multiples IDs. Cargar dos <script src=gtag.js> distintos rompe el dataLayer.
    $gtagIds = array_values(array_filter([$site->googleAnalyticsId, $site->googleAdsId]));
    if (!empty($gtagIds)):
        $primaryGtagId = $gtagIds[0];
    ?>
    <!-- Google tag (gtag.js) — GA4 + Google Ads -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?= e($primaryGtagId) ?>"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      <?php foreach ($gtagIds as $tid): ?>
      gtag('config', <?= json_encode($tid) ?>);
      <?php endforeach; ?>
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
    <?php if ($site->microsoftClarityId): ?>
    <!-- Microsoft Clarity -->
    <script type="text/javascript">
      (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
      })(window, document, "clarity", "script", <?= json_encode($site->microsoftClarityId) ?>);
    </script>
    <?php endif; ?>
    <?php if ($site->metaPixelId): ?>
    <!-- Meta Pixel (Facebook/Instagram) -->
    <script>
      !function(f,b,e,v,n,t,s)
      {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};
      if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
      n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t,s)}(window, document,'script',
      'https://connect.facebook.net/en_US/fbevents.js');
      fbq('init', <?= json_encode($site->metaPixelId) ?>);
      fbq('track', 'PageView');
    </script>
    <noscript><img height="1" width="1" style="display:none"
      src="https://www.facebook.com/tr?id=<?= e($site->metaPixelId) ?>&ev=PageView&noscript=1" alt=""></noscript>
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
