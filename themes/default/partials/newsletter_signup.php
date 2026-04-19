<?php
/**
 * @var \Core\Site $site
 *
 * Si el operador configuro el form en /admin/settings, lo renderiza.
 * El form postea directo al proveedor (ConvertKit, Buttondown, etc).
 * No pasa por nuestro backend -> no guardamos emails, menos superficie de ataque.
 */
use Core\Settings;

$enabled = Settings::getBool($site->id, 'newsletter_enabled', false);
$action  = (string)Settings::get($site->id, 'newsletter_action_url', '');
if (!$enabled || $action === '') {
    return;
}

$heading     = (string)Settings::get($site->id, 'newsletter_heading', 'Suscribite al newsletter');
$description = (string)Settings::get($site->id, 'newsletter_description', '');
$buttonText  = (string)Settings::get($site->id, 'newsletter_button_text', 'Suscribirme');
$emailField  = (string)Settings::get($site->id, 'newsletter_email_field_name', 'email');
$hiddenJson  = (string)Settings::get($site->id, 'newsletter_hidden_fields_json', '');
$hidden      = [];
if ($hiddenJson !== '') {
    $decoded = json_decode($hiddenJson, true);
    if (is_array($decoded)) { $hidden = $decoded; }
}
?>
<aside class="newsletter-block" aria-labelledby="newsletter-heading">
    <h2 id="newsletter-heading"><?= e($heading) ?></h2>
    <?php if ($description !== ''): ?>
        <p class="newsletter-desc"><?= e($description) ?></p>
    <?php endif; ?>
    <form class="newsletter-form" method="post" action="<?= e($action) ?>" target="_blank" rel="noopener">
        <?php foreach ($hidden as $k => $v): ?>
            <input type="hidden" name="<?= e($k) ?>" value="<?= e(is_scalar($v) ? $v : json_encode($v)) ?>">
        <?php endforeach; ?>
        <label class="visually-hidden" for="newsletter-email">Email</label>
        <input type="email" id="newsletter-email" name="<?= e($emailField) ?>" required placeholder="tu@email.com" autocomplete="email">
        <button type="submit" class="btn btn-primary"><?= e($buttonText) ?></button>
    </form>
    <p class="newsletter-legal muted"><small>Sin spam. Podés desuscribirte en cualquier momento.</small></p>
</aside>
