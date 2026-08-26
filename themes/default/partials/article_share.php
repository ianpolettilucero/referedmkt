<?php
/**
 * Botones de compartir de un articulo.
 *
 * Se usa dos veces en la misma pagina, asi que vive en un partial: duplicar
 * los cuatro SVG en la vista era pedir que se desincronizaran.
 *
 * @var array  $article
 * @var string $variant  'compacto' arriba del articulo, 'completo' al final
 *
 * El compacto va debajo del titulo, sin caja ni etiqueta, solo iconos: ahi el
 * lector todavia no leyo nada y un bloque grande le corre el texto hacia abajo.
 * El completo va al final, que es donde alguien que termino de leer decide
 * compartir.
 *
 * El compacto es un <div> y no un <section aria-label>: dos landmarks con el
 * mismo nombre accesible en una pagina son ruido para un lector de pantalla.
 * Cada enlace ya trae su propio aria-label.
 */

$variant = $variant ?? 'completo';
$compacto = $variant === 'compacto';

// LinkedIn ignora el texto desde 2022 y usa los OG tags de la pagina.
$shareUrl     = site_url(article_url($article));
$shareTitle   = $article['title'] ?? '';
$shareMsg     = $shareTitle . ' — vía ' . $site->name;
$shareTextEnc = rawurlencode($shareMsg);
$shareUrlEnc  = rawurlencode($shareUrl);
$mailSubject  = rawurlencode($shareTitle);
$mailBody     = rawurlencode($shareMsg . "\n\n" . $shareUrl);

$redes = [
    [
        'clase' => 'article-share-linkedin',
        'nombre' => 'LinkedIn',
        'href' => 'https://www.linkedin.com/sharing/share-offsite/?url=' . $shareUrlEnc,
        'externo' => true,
        'svg' => '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M20.45 20.45h-3.55v-5.57c0-1.33-.02-3.04-1.85-3.04-1.86 0-2.14 1.45-2.14 2.95v5.66H9.36V9h3.4v1.56h.05c.47-.9 1.63-1.85 3.35-1.85 3.58 0 4.24 2.36 4.24 5.42v6.32zM5.34 7.43a2.06 2.06 0 1 1 0-4.13 2.06 2.06 0 0 1 0 4.13zM7.12 20.45H3.56V9h3.56v11.45zM22.23 0H1.77C.79 0 0 .77 0 1.72v20.56C0 23.23.79 24 1.77 24h20.46c.98 0 1.77-.77 1.77-1.72V1.72C24 .77 23.21 0 22.23 0z"/></svg>',
    ],
    [
        'clase' => 'article-share-whatsapp',
        'nombre' => 'WhatsApp',
        'href' => 'https://wa.me/?text=' . $shareTextEnc . '%20' . $shareUrlEnc,
        'externo' => true,
        'svg' => '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M17.5 14.4c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.95 1.17-.18.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.48-.88-.79-1.48-1.77-1.66-2.07-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.08-.8.37-.27.3-1.04 1.02-1.04 2.49 0 1.47 1.07 2.89 1.22 3.09.15.2 2.1 3.21 5.09 4.5.71.31 1.26.49 1.7.63.71.23 1.36.2 1.87.12.57-.08 1.76-.72 2.01-1.41.25-.7.25-1.29.17-1.42-.07-.12-.27-.2-.57-.35zM12.04 21.5h-.01a9.45 9.45 0 0 1-4.82-1.32l-.35-.2-3.58.94.96-3.49-.23-.36a9.45 9.45 0 0 1-1.45-5.03c0-5.22 4.25-9.47 9.48-9.47 2.53 0 4.9.99 6.68 2.77a9.4 9.4 0 0 1 2.77 6.7c0 5.22-4.25 9.46-9.45 9.46zm8.05-17.51A11.43 11.43 0 0 0 12.04.5C5.72.5.56 5.66.56 12a11.4 11.4 0 0 0 1.52 5.71L.5 23.5l5.93-1.56a11.44 11.44 0 0 0 5.6 1.43h.01c6.32 0 11.48-5.16 11.48-11.5a11.4 11.4 0 0 0-3.43-7.88z"/></svg>',
    ],
    [
        'clase' => 'article-share-x',
        'nombre' => 'X',
        'href' => 'https://twitter.com/intent/tweet?text=' . $shareTextEnc . '&url=' . $shareUrlEnc,
        'externo' => true,
        'svg' => '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24h-6.66l-5.214-6.817-5.966 6.817H1.678l7.73-8.835L1.254 2.25h6.837l4.713 6.231 5.44-6.231zm-1.161 17.52h1.833L7.084 4.126H5.117l11.966 15.644z"/></svg>',
    ],
    [
        'clase' => 'article-share-email',
        'nombre' => 'Email',
        'href' => 'mailto:?subject=' . $mailSubject . '&body=' . $mailBody,
        'externo' => false,
        'svg' => '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><polyline points="3 7 12 13 21 7"/></svg>',
    ],
];
?>
<?php if ($compacto): ?>
<div class="article-share article-share--compacto no-print">
    <span class="article-share-label">Compartir</span>
    <div class="article-share-buttons">
<?php else: ?>
<section class="article-share no-print" aria-label="Compartir artículo">
    <span class="article-share-label">Compartir</span>
    <div class="article-share-buttons">
<?php endif; ?>
        <?php foreach ($redes as $r): ?>
            <a class="article-share-btn <?= e($r['clase']) ?>"
               href="<?= e($r['href']) ?>"
               <?= $r['externo'] ? 'target="_blank" rel="noopener noreferrer"' : '' ?>
               aria-label="Compartir <?= $r['nombre'] === 'Email' ? 'por email' : 'en ' . e($r['nombre']) ?>">
                <?= $r['svg'] ?>
                <span><?= e($r['nombre']) ?></span>
            </a>
        <?php endforeach; ?>
    </div>
<?= $compacto ? '</div>' : '</section>' ?>
