<?php
/**
 * Footer. Ademas de lo legal cumple una funcion de arquitectura: da a TODAS las
 * paginas un enlace directo a las secciones y a cada categoria, con lo que
 * ninguna queda a mas de dos clicks desde cualquier punto del sitio.
 *
 * @var \Core\Site $site
 * @var \Core\View $view
 */
$categorias = \Models\Category::all($site->id);
?>
<footer class="site-footer">
    <div class="container">
        <nav class="footer-cols" aria-label="Pie de página">
            <div class="footer-col">
                <h2 class="footer-title">Contenido</h2>
                <ul>
                    <?php foreach (active_sections() as $sec): ?>
                        <li><a href="<?= e($sec['path']) ?>"><?= e($sec['label']) ?></a></li>
                    <?php endforeach; ?>
                    <li><a href="/productos">Catálogo de productos</a></li>
                    <li><a href="/comparar">Comparador</a></li>
                </ul>
            </div>

            <?php if ($categorias): ?>
                <div class="footer-col footer-col-wide">
                    <h2 class="footer-title">Categorías</h2>
                    <ul class="footer-cats">
                        <?php foreach ($categorias as $c): ?>
                            <li><a href="<?= e(category_url($c)) ?>"><?= e($c['name']) ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="footer-col">
                <h2 class="footer-title">El sitio</h2>
                <ul>
                    <li><a href="/buscar">Buscar</a></li>
                    <li><a href="/feed.xml">RSS</a></li>
                    <li><a href="/sitemap.xml">Sitemap</a></li>
                </ul>
            </div>
        </nav>

        <p class="footer-legal">
            &copy; <?= date('Y') ?> <?= e($site->name) ?>. Análisis independiente de
            herramientas de ciberseguridad.
        </p>
    </div>
</footer>
