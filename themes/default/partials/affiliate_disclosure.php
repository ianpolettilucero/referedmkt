<?php
/**
 * Divulgacion de afiliados.
 *
 * Ya NO se incluye en el layout global: se saco la banda al pie de todas las
 * paginas. El partial se conserva porque el lugar donde de verdad corresponde
 * —y donde la guia de la FTC pide que este— es junto al link de afiliado, no
 * al final del documento. Para usarlo asi:
 *
 *     <?= $view->partial('affiliate_disclosure') ?>
 *
 * en themes/default/views/product.php, arriba del boton de CTA.
 *
 * @var \Core\Site $site
 */
?>
<?php if ($site->affiliateDisclosureText): ?>
<aside class="affiliate-disclosure" role="note">
    <div class="container">
        <small><?= e($site->affiliateDisclosureText) ?></small>
    </div>
</aside>
<?php endif; ?>
