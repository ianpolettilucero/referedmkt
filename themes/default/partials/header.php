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
            <a href="/buscar" aria-label="Buscar">Buscar</a>
            <button type="button" class="theme-toggle" data-theme-toggle aria-label="Cambiar tema">
                <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                </svg>
                <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="4"/>
                    <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>
                </svg>
            </button>
        </nav>
    </div>
</header>
