<?php
/**
 * @var \Core\View $view
 * @var array      $product
 * @var string     $description_html
 */
$view->layout('default');
$ctaUrl = !empty($product['affiliate_slug'])
    ? affiliate_url($product['affiliate_slug'], null, (int)$product['id'])
    : null;
?>
<article class="product">
    <header class="product-header">
        <?php if (!empty($product['logo_url'])): ?>
            <img class="product-logo" src="<?= e($product['logo_url']) ?>" alt="<?= e($product['name']) ?>" loading="lazy">
        <?php endif; ?>
        <div>
            <?php if (!empty($product['brand'])): ?>
                <p class="product-brand"><?= e($product['brand']) ?></p>
            <?php endif; ?>
            <h1><?= e($product['name']) ?></h1>
            <?php if (!empty($product['rating'])): ?>
                <p class="product-rating">★ <?= e(number_format((float)$product['rating'], 1)) ?>/5</p>
            <?php endif; ?>
            <p class="product-price">
                <?= e(format_price(
                    isset($product['price_from']) ? (float)$product['price_from'] : null,
                    $product['price_currency'] ?? null,
                    $product['pricing_model'] ?? 'custom'
                )) ?>
            </p>
            <?php if ($ctaUrl): ?>
                <p>
                    <a class="btn btn-primary" href="<?= e($ctaUrl) ?>" rel="sponsored nofollow noopener" target="_blank">
                        Ir al sitio oficial
                    </a>
                </p>
            <?php endif; ?>
        </div>
    </header>

    <?php if (!empty($product['description_short'])): ?>
        <p class="product-summary"><?= e($product['description_short']) ?></p>
    <?php endif; ?>

    <?php if (!empty($product['features']) && is_array($product['features'])): ?>
        <section>
            <h2>Features</h2>
            <ul>
                <?php foreach ($product['features'] as $f): ?>
                    <li><?= e($f) ?></li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>

    <div class="pros-cons">
        <?php if (!empty($product['pros']) && is_array($product['pros'])): ?>
            <section>
                <h2>Pros</h2>
                <ul>
                    <?php foreach ($product['pros'] as $p): ?>
                        <li><?= e($p) ?></li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?>
        <?php if (!empty($product['cons']) && is_array($product['cons'])): ?>
            <section>
                <h2>Contras</h2>
                <ul>
                    <?php foreach ($product['cons'] as $c): ?>
                        <li><?= e($c) ?></li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?>
    </div>

    <?php if ($description_html !== ''): ?>
        <section class="product-long-description">
            <?= $description_html /* HTML generado por Markdown::toHtml (input escapado) */ ?>
        </section>
    <?php endif; ?>

    <?php if (!empty($product['specs']) && is_array($product['specs'])): ?>
        <section>
            <h2>Especificaciones</h2>
            <table class="specs">
                <tbody>
                    <?php foreach ($product['specs'] as $k => $v): ?>
                        <tr>
                            <th scope="row"><?= e($k) ?></th>
                            <td><?= e(is_scalar($v) ? $v : json_encode($v)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    <?php endif; ?>

    <?php if ($ctaUrl): ?>
        <section class="cta-band">
            <p>¿Listo para probarlo?</p>
            <a class="btn btn-primary" href="<?= e($ctaUrl) ?>" rel="sponsored nofollow noopener" target="_blank">
                Ir al sitio oficial
            </a>
        </section>
    <?php endif; ?>
</article>
