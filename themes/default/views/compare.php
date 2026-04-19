<?php
/**
 * @var \Core\View $view
 * @var array      $products
 * @var array      $spec_keys
 */
$view->layout('default');
?>
<section class="compare-page">
    <h1>Comparador</h1>

    <?php if (!$products): ?>
        <p class="muted">No hay productos para comparar. Agregá <code>?ids=1,2,3</code> a la URL o seleccioná productos desde el catálogo.</p>
        <p><a class="btn btn-primary" href="/productos">Ir al catálogo</a></p>
    <?php else: ?>
        <div style="overflow-x:auto">
            <table class="compare-table" style="border-collapse:collapse;width:100%;min-width:640px">
                <thead>
                    <tr>
                        <th style="text-align:left;padding:0.6rem;border-bottom:1px solid #e5e7eb"></th>
                        <?php foreach ($products as $p): ?>
                            <th style="padding:0.6rem;border-bottom:1px solid #e5e7eb;vertical-align:top;min-width:180px">
                                <?php if (!empty($p['logo_url'])): ?>
                                    <img src="<?= e($p['logo_url']) ?>" alt="<?= e($p['name']) ?>" style="max-height:40px;display:block;margin-bottom:0.3rem" loading="lazy">
                                <?php endif; ?>
                                <a href="<?= e(product_url($p)) ?>"><strong><?= e($p['name']) ?></strong></a>
                                <?php if (!empty($p['brand'])): ?>
                                    <div class="muted"><?= e($p['brand']) ?></div>
                                <?php endif; ?>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row" style="text-align:left;padding:0.6rem;border-bottom:1px solid #e5e7eb">Rating</th>
                        <?php foreach ($products as $p): ?>
                            <td style="padding:0.6rem;border-bottom:1px solid #e5e7eb">
                                <?= $p['rating'] !== null ? '★ ' . e(number_format((float)$p['rating'], 1)) . '/5' : '—' ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <th scope="row" style="text-align:left;padding:0.6rem;border-bottom:1px solid #e5e7eb">Precio</th>
                        <?php foreach ($products as $p): ?>
                            <td style="padding:0.6rem;border-bottom:1px solid #e5e7eb">
                                <?= e(format_price(
                                    isset($p['price_from']) ? (float)$p['price_from'] : null,
                                    $p['price_currency'] ?? null,
                                    $p['pricing_model'] ?? 'custom'
                                )) ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <th scope="row" style="text-align:left;padding:0.6rem;border-bottom:1px solid #e5e7eb">Resumen</th>
                        <?php foreach ($products as $p): ?>
                            <td style="padding:0.6rem;border-bottom:1px solid #e5e7eb;font-size:0.9rem">
                                <?= e(excerpt($p['description_short'] ?? '', 200)) ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <th scope="row" style="text-align:left;padding:0.6rem;border-bottom:1px solid #e5e7eb;vertical-align:top">Features</th>
                        <?php foreach ($products as $p): ?>
                            <td style="padding:0.6rem;border-bottom:1px solid #e5e7eb;vertical-align:top;font-size:0.9rem">
                                <?php if (!empty($p['features']) && is_array($p['features'])): ?>
                                    <ul style="margin:0;padding-left:1rem">
                                        <?php foreach ($p['features'] as $f): ?>
                                            <li><?= e($f) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>—<?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <?php foreach ($spec_keys as $k): ?>
                        <tr>
                            <th scope="row" style="text-align:left;padding:0.6rem;border-bottom:1px solid #e5e7eb;vertical-align:top"><?= e($k) ?></th>
                            <?php foreach ($products as $p): ?>
                                <td style="padding:0.6rem;border-bottom:1px solid #e5e7eb;vertical-align:top;font-size:0.9rem">
                                    <?php $v = $p['specs'][$k] ?? null; ?>
                                    <?= $v === null ? '—' : e(is_scalar($v) ? $v : json_encode($v)) ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <?php foreach ($products as $p): ?>
                            <td style="padding:0.6rem">
                                <a class="btn btn-primary" href="<?= e(product_url($p)) ?>">Ver detalle</a>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                </tfoot>
            </table>
        </div>
    <?php endif; ?>
</section>
