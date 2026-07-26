<?php /** @var array $product */ ?>
<article class="product-card">
    <div class="product-card-body">
        <?php /* Fila superior: logo a la izquierda, nota a la derecha. El logo
                 vive en una caja de alto fijo para que los titulos de todas las
                 cards de una fila queden alineados aunque falte el logo. */ ?>
        <div class="product-card-head">
            <span class="product-card-logo-box">
                <?php if (!empty($product['logo_url'])): ?>
                    <img class="product-card-logo" src="<?= e($product['logo_url']) ?>"
                         alt="<?= e($product['name']) ?>" loading="lazy">
                <?php endif; ?>
            </span>
            <?php if (!empty($product['rating'])): ?>
                <span class="product-card-rating" aria-label="Nota <?= e(number_format((float)$product['rating'], 1)) ?> sobre 5">
                    ★ <?= e(number_format((float)$product['rating'], 1)) ?>
                </span>
            <?php endif; ?>
        </div>

        <h3 class="product-card-title">
            <a class="product-card-link" href="<?= e(product_url($product)) ?>">
                <?= e($product['name']) ?>
            </a>
        </h3>

        <?php if (!empty($product['brand'])): ?>
            <p class="product-card-brand"><?= e($product['brand']) ?></p>
        <?php endif; ?>

        <?php if (!empty($product['description_short'])): ?>
            <p class="product-card-desc"><?= e(excerpt($product['description_short'], 130)) ?></p>
        <?php endif; ?>

        <div class="product-card-foot">
            <span class="product-card-price">
                <?= e(format_price(
                    isset($product['price_from']) ? (float)$product['price_from'] : null,
                    $product['price_currency'] ?? null,
                    $product['pricing_model'] ?? 'custom'
                )) ?>
            </span>
            <?php if (!empty($product['category_name']) && !empty($product['category_slug'])): ?>
                <a class="product-card-cat-chip" href="<?= e('/productos/' . $product['category_slug']) ?>"
                   title="Ver toda la categoría">
                    <?= e($product['category_name']) ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</article>
