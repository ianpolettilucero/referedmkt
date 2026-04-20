<?php /** @var array $product */ ?>
<article class="product-card">
    <?php if (!empty($product['category_name']) && !empty($product['category_slug'])): ?>
        <a class="product-card-cat-chip" href="<?= e('/productos/' . $product['category_slug']) ?>" title="Ver toda la categoría">
            <?= e($product['category_name']) ?>
        </a>
    <?php endif; ?>

    <div class="product-card-body">
        <?php if (!empty($product['logo_url'])): ?>
            <img class="product-card-logo" src="<?= e($product['logo_url']) ?>" alt="<?= e($product['name']) ?>" loading="lazy">
        <?php endif; ?>
        <h3 class="product-card-title">
            <a class="product-card-link" href="<?= e(product_url($product)) ?>">
                <?= e($product['name']) ?>
            </a>
        </h3>
        <?php if (!empty($product['brand'])): ?>
            <p class="product-card-brand"><?= e($product['brand']) ?></p>
        <?php endif; ?>
        <?php if (!empty($product['rating'])): ?>
            <p class="product-card-rating">★ <?= e(number_format((float)$product['rating'], 1)) ?>/5</p>
        <?php endif; ?>
        <?php if (!empty($product['description_short'])): ?>
            <p class="product-card-desc"><?= e(excerpt($product['description_short'], 140)) ?></p>
        <?php endif; ?>
        <p class="product-card-price">
            <?= e(format_price(
                isset($product['price_from']) ? (float)$product['price_from'] : null,
                $product['price_currency'] ?? null,
                $product['pricing_model'] ?? 'custom'
            )) ?>
        </p>
    </div>
</article>
