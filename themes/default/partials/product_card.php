<?php /** @var array $product */ ?>
<article class="product-card">
    <a class="product-card-link" href="<?= e(product_url($product)) ?>">
        <?php if (!empty($product['logo_url'])): ?>
            <img class="product-card-logo" src="<?= e($product['logo_url']) ?>" alt="<?= e($product['name']) ?>" loading="lazy">
        <?php endif; ?>
        <h3 class="product-card-title"><?= e($product['name']) ?></h3>
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
    </a>
</article>
