<?php
/**
 * @var \Core\View $view
 * @var array      $author
 * @var array      $articles
 */
$view->layout('default');
$social = is_array($author['social_links'] ?? null) ? $author['social_links'] : [];
?>
<section class="author-page">
    <header class="author-header" style="display:flex;gap:1.5rem;align-items:flex-start;flex-wrap:wrap">
        <?php if (!empty($author['avatar_url'])): ?>
            <img src="<?= e($author['avatar_url']) ?>" alt="<?= e($author['name']) ?>"
                 style="width:120px;height:120px;border-radius:50%;object-fit:cover" loading="lazy">
        <?php endif; ?>
        <div>
            <h1 style="margin-top:0"><?= e($author['name']) ?></h1>
            <?php if (!empty($author['expertise'])): ?>
                <p class="muted"><?= e($author['expertise']) ?></p>
            <?php endif; ?>
            <?php if (!empty($author['bio'])): ?>
                <div class="author-bio">
                    <?= \Core\Markdown::toHtml($author['bio']) ?>
                </div>
            <?php endif; ?>
            <?php if ($social): ?>
                <p>
                    <?php
                    // social_links es una lista, no un mapa: iterar por clave
                    // sacaba el indice numerico como texto del enlace ("0", "1").
                    // La etiqueta se deriva del host. rel="me" en vez de
                    // "nofollow": el enlace a la identidad externa del autor es
                    // justo la senal que queremos que los buscadores sigan.
                    $redes = [
                        'linkedin.com' => 'LinkedIn', 'github.com' => 'GitHub',
                        'x.com' => 'X', 'twitter.com' => 'X',
                        'mastodon.social' => 'Mastodon', 'youtube.com' => 'YouTube',
                    ];
                    foreach ((array)$social as $url):
                        if (!is_string($url) || $url === '') { continue; }
                        $host = preg_replace('/^www\./', '', (string)parse_url($url, PHP_URL_HOST));
                        $label = $redes[$host] ?? ($host !== '' ? $host : $url);
                    ?>
                        <a href="<?= e($url) ?>" rel="me noopener" target="_blank"><?= e($label) ?></a>&nbsp;
                    <?php endforeach; ?>
                </p>
            <?php endif; ?>
        </div>
    </header>

    <section class="section">
        <h2>Artículos publicados (<?= count($articles) ?>)</h2>
        <?php if (!$articles): ?>
            <p class="muted">Sin artículos publicados todavía.</p>
        <?php else: ?>
            <div class="grid grid-cards">
                <?php foreach ($articles as $a): ?>
                    <?= $view->partial('article_card', ['article' => $a]) ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</section>
