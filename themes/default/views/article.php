<?php
/**
 * @var \Core\View $view
 * @var \Core\Site $site
 * @var array      $article
 * @var string     $content_html
 * @var array      $related_products
 * @var array      $related_articles
 * @var array      $toc_items
 */
$view->layout('default');
$related_articles = $related_articles ?? [];
$toc_items        = $toc_items ?? [];
?>
<article class="article">
    <header class="article-header">
        <h1><?= e($article['title']) ?></h1>
        <?php if (!empty($article['subtitle'])): ?>
            <p class="article-subtitle"><?= e($article['subtitle']) ?></p>
        <?php endif; ?>
        <p class="article-meta">
            <?php if (!empty($article['author_name'])): ?>
                Por <a rel="author" href="<?= e('/autor/' . ($article['author_slug'] ?? '')) ?>"><strong><?= e($article['author_name']) ?></strong></a>
            <?php endif; ?>
            <?php if (!empty($article['published_at'])): ?>
                · <time datetime="<?= e(date('c', strtotime($article['published_at']))) ?>">
                    <?= e(date('d/m/Y', strtotime($article['published_at']))) ?>
                </time>
            <?php endif; ?>
            <?php $minutes = reading_time($article['content'] ?? ''); ?>
            · <span class="article-reading-time" title="Tiempo estimado de lectura"><?= e($minutes) ?> min de lectura</span>
        </p>
        <?php if (!empty($article['featured_image'])): ?>
            <img class="article-hero" src="<?= e($article['featured_image']) ?>" alt="<?= e($article['title']) ?>" loading="lazy">
        <?php endif; ?>
    </header>

    <?php if ($toc_items): ?>
        <details class="article-toc no-print" open>
            <summary>
                <span class="article-toc-icon" aria-hidden="true">☰</span>
                <span>Índice del artículo</span>
                <span class="article-toc-count"><?= count($toc_items) ?> secciones</span>
            </summary>
            <nav aria-label="Tabla de contenidos">
                <ol>
                    <?php foreach ($toc_items as $it): ?>
                        <li class="toc-level-<?= (int)$it['level'] ?>">
                            <a href="#<?= e($it['id']) ?>"><?= e($it['text']) ?></a>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </nav>
        </details>
    <?php endif; ?>

    <div class="article-body">
        <?= $content_html /* HTML generado por Markdown::toHtml (input escapado) */ ?>
    </div>

    <?php if ($related_products): ?>
        <section class="related-products">
            <h2>Productos mencionados</h2>
            <div class="grid grid-cards">
                <?php foreach ($related_products as $p): ?>
                    <?= $view->partial('product_card', ['product' => $p]) ?>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php if ($related_articles): ?>
        <section class="related-articles no-print">
            <h2>Seguí leyendo</h2>
            <div class="grid grid-cards">
                <?php foreach ($related_articles as $ra): ?>
                    <?= $view->partial('article_card', ['article' => $ra]) ?>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>

    <?php
        // Social share: URLs intent. Mensaje sugerido para canales que soportan
        // pre-filled text (X/WhatsApp/email). LinkedIn ignora el texto desde
        // 2022 y usa los OG tags de la pagina.
        $shareUrl   = site_url(article_url($article));
        $shareTitle = $article['title'] ?? '';
        $shareMsg   = $shareTitle . ' — vía ' . $site->name;
        $shareTextEnc = rawurlencode($shareMsg);
        $shareUrlEnc  = rawurlencode($shareUrl);
        $mailSubject  = rawurlencode($shareTitle);
        $mailBody     = rawurlencode($shareMsg . "\n\n" . $shareUrl);
    ?>
    <section class="article-share no-print" aria-label="Compartir artículo">
        <span class="article-share-label">Compartir</span>
        <div class="article-share-buttons">
            <a class="article-share-btn article-share-linkedin"
               href="https://www.linkedin.com/sharing/share-offsite/?url=<?= e($shareUrlEnc) ?>"
               target="_blank" rel="noopener noreferrer"
               aria-label="Compartir en LinkedIn">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M20.45 20.45h-3.55v-5.57c0-1.33-.02-3.04-1.85-3.04-1.86 0-2.14 1.45-2.14 2.95v5.66H9.36V9h3.4v1.56h.05c.47-.9 1.63-1.85 3.35-1.85 3.58 0 4.24 2.36 4.24 5.42v6.32zM5.34 7.43a2.06 2.06 0 1 1 0-4.13 2.06 2.06 0 0 1 0 4.13zM7.12 20.45H3.56V9h3.56v11.45zM22.23 0H1.77C.79 0 0 .77 0 1.72v20.56C0 23.23.79 24 1.77 24h20.46c.98 0 1.77-.77 1.77-1.72V1.72C24 .77 23.21 0 22.23 0z"/></svg>
                <span>LinkedIn</span>
            </a>
            <a class="article-share-btn article-share-whatsapp"
               href="https://wa.me/?text=<?= e($shareTextEnc) ?>%20<?= e($shareUrlEnc) ?>"
               target="_blank" rel="noopener noreferrer"
               aria-label="Compartir en WhatsApp">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M17.5 14.4c-.3-.15-1.76-.87-2.03-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.95 1.17-.18.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.48-.88-.79-1.48-1.77-1.66-2.07-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.08-.15-.67-1.62-.92-2.22-.24-.58-.49-.5-.67-.51h-.57c-.2 0-.52.08-.8.37-.27.3-1.04 1.02-1.04 2.49 0 1.47 1.07 2.89 1.22 3.09.15.2 2.1 3.21 5.09 4.5.71.31 1.26.49 1.7.63.71.23 1.36.2 1.87.12.57-.08 1.76-.72 2.01-1.41.25-.7.25-1.29.17-1.42-.07-.12-.27-.2-.57-.35zM12.04 21.5h-.01a9.45 9.45 0 0 1-4.82-1.32l-.35-.2-3.58.94.96-3.49-.23-.36a9.45 9.45 0 0 1-1.45-5.03c0-5.22 4.25-9.47 9.48-9.47 2.53 0 4.9.99 6.68 2.77a9.4 9.4 0 0 1 2.77 6.7c0 5.22-4.25 9.46-9.45 9.46zm8.05-17.51A11.43 11.43 0 0 0 12.04.5C5.72.5.56 5.66.56 12a11.4 11.4 0 0 0 1.52 5.71L.5 23.5l5.93-1.56a11.44 11.44 0 0 0 5.6 1.43h.01c6.32 0 11.48-5.16 11.48-11.5a11.4 11.4 0 0 0-3.43-7.88z"/></svg>
                <span>WhatsApp</span>
            </a>
            <a class="article-share-btn article-share-x"
               href="https://twitter.com/intent/tweet?text=<?= e($shareTextEnc) ?>&url=<?= e($shareUrlEnc) ?>"
               target="_blank" rel="noopener noreferrer"
               aria-label="Compartir en X">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24h-6.66l-5.214-6.817-5.966 6.817H1.678l7.73-8.835L1.254 2.25h6.837l4.713 6.231 5.44-6.231zm-1.161 17.52h1.833L7.084 4.126H5.117l11.966 15.644z"/></svg>
                <span>X</span>
            </a>
            <a class="article-share-btn article-share-email"
               href="mailto:?subject=<?= e($mailSubject) ?>&body=<?= e($mailBody) ?>"
               aria-label="Compartir por email">
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2"/><polyline points="3 7 12 13 21 7"/></svg>
                <span>Email</span>
            </a>
        </div>
    </section>

    <?= $view->partial('newsletter_signup') ?>
</article>
