<?php /** @var \Core\Site $site */ ?>
<header class="site-header">
    <div class="container site-header-inner">
        <a class="site-brand<?= $site->logoUrl ? ' site-brand--with-logo' : '' ?>" href="/">
            <?php if ($site->logoUrl): ?>
                <img class="site-brand-logo" src="<?= e($site->logoUrl) ?>" alt="<?= e($site->name) ?>">
            <?php endif; ?>
            <span class="site-brand-name"><?= e($site->name) ?></span>
        </a>
        <nav class="site-nav" aria-label="Principal">
            <a href="/productos">Productos</a>
            <?php /* Solo las secciones con articulos publicados: enlazar un
                     listado vacio es contenido delgado y confunde al crawler. */ ?>
            <?php foreach (active_sections() as $sec): ?>
                <a href="<?= e($sec['path']) ?>"><?= e($sec['label']) ?></a>
            <?php endforeach; ?>
            <a href="/buscar" aria-label="Buscar">Buscar</a>
        </nav>
        <button type="button" class="theme-toggle" data-theme-toggle aria-label="Cambiar tema">
            <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
            </svg>
            <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="4"/>
                <path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/>
            </svg>
        </button>
    </div>
</header>
