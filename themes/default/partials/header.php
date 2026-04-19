<?php /** @var \Core\Site $site */ ?>
<header class="site-header">
    <div class="container site-header-inner">
        <a class="site-brand" href="/">
            <?php if ($site->logoUrl): ?>
                <img src="<?= e($site->logoUrl) ?>" alt="<?= e($site->name) ?>" height="32">
            <?php else: ?>
                <span><?= e($site->name) ?></span>
            <?php endif; ?>
        </a>
        <nav class="site-nav" aria-label="Principal">
            <a href="/productos">Productos</a>
            <a href="/guias">Guías</a>
            <a href="/comparativas">Comparativas</a>
            <a href="/resenas">Reseñas</a>
        </nav>
    </div>
</header>
