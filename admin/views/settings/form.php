<?php
/** @var \Admin\AdminView $view
 *  @var array  $values
 *  @var string $csrf_token
 *  @var array|null $active_site
 */
$view->layout('admin');
?>
<div class="admin-page-header">
    <h1 class="admin-page-title">Settings del sitio</h1>
    <?php if ($active_site): ?>
        <span class="admin-muted">Sitio: <strong><?= htmlspecialchars($active_site['name'], ENT_QUOTES, 'UTF-8') ?></strong></span>
    <?php endif; ?>
</div>

<form method="post" action="/admin/settings" class="admin-form admin-card">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">

    <h2 style="margin-top:0;font-size:1.1rem">Tema visual</h2>
    <p class="admin-muted" style="margin-top:0">
        Preset de colores + tipografía + radios. Cambia todo el look del sitio público con un click.
    </p>
    <div class="admin-field">
        <label>Preset</label>
        <select name="theme_preset">
            <?php foreach (\Core\ThemePresets::all() as $id => $preset): ?>
                <option value="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>" <?= ($values['theme_preset'] ?? 'indigo-night') === $id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($preset['name'], ENT_QUOTES, 'UTF-8') ?> — <?= htmlspecialchars($preset['vibe'], ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>
        <small class="admin-hint">
            <?php
            $active = \Core\ThemePresets::get($values['theme_preset'] ?? 'indigo-night');
            if ($active) echo htmlspecialchars($active['description'], ENT_QUOTES, 'UTF-8');
            ?>
        </small>
    </div>

    <h2 style="margin-top:2rem;font-size:1.1rem">Newsletter</h2>
    <p class="admin-muted" style="margin-top:0">
        Form embebido que postea directo a tu proveedor (ConvertKit, Buttondown, Mailchimp, etc).
        No guardamos emails en esta base de datos.
    </p>

    <div class="admin-field">
        <label><input type="checkbox" name="newsletter_enabled" value="1" <?= !empty($values['newsletter_enabled']) && $values['newsletter_enabled'] !== '0' ? 'checked' : '' ?>> Mostrar form al final de articulos</label>
    </div>

    <div class="admin-grid admin-grid-2">
        <div class="admin-field">
            <label>Titulo</label>
            <input name="newsletter_heading" value="<?= htmlspecialchars($values['newsletter_heading'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="admin-field">
            <label>CTA del boton</label>
            <input name="newsletter_button_text" value="<?= htmlspecialchars($values['newsletter_button_text'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
    </div>

    <div class="admin-field">
        <label>Descripcion</label>
        <textarea name="newsletter_description"><?= htmlspecialchars($values['newsletter_description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <div class="admin-field">
        <label>Action URL del proveedor</label>
        <input name="newsletter_action_url" value="<?= htmlspecialchars($values['newsletter_action_url'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="https://app.convertkit.com/forms/XXXXXX/subscriptions">
        <small class="admin-hint">Copiá el <code>action</code> del HTML embed que te da tu proveedor.</small>
    </div>

    <div class="admin-grid admin-grid-2">
        <div class="admin-field">
            <label>Nombre del campo email</label>
            <input name="newsletter_email_field_name" value="<?= htmlspecialchars($values['newsletter_email_field_name'] ?? 'email', ENT_QUOTES, 'UTF-8') ?>">
            <small class="admin-hint">ConvertKit: <code>email_address</code>. Buttondown: <code>email</code>. Mailchimp: <code>EMAIL</code>.</small>
        </div>
        <div class="admin-field">
            <label>Hidden fields extra (JSON)</label>
            <input name="newsletter_hidden_fields_json" value="<?= htmlspecialchars($values['newsletter_hidden_fields_json'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder='{"tag":"blog"}'>
            <small class="admin-hint">Se agregan como &lt;input type="hidden"&gt; al form. Dejar vacio si no hace falta.</small>
        </div>
    </div>

    <div class="admin-field">
        <label>Mensaje al enviar</label>
        <input name="newsletter_success_message" value="<?= htmlspecialchars($values['newsletter_success_message'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        <small class="admin-hint">Solo informativo (el proveedor generalmente redirige a su propia pagina de thanks).</small>
    </div>

    <h2 style="margin-top:2rem;font-size:1.1rem">CSS personalizado</h2>
    <p class="admin-muted" style="margin-top:0">
        CSS que se inyecta al final del <code>&lt;head&gt;</code> del sitio publico. Override rapido
        de colores, espaciados o cualquier estilo sin tocar el tema. Las variables clave:
        <code>--primary</code>, <code>--accent</code>, <code>--bg</code>, <code>--surface</code>,
        <code>--text</code>. Ejemplo:
    </p>
    <pre style="background:var(--a-bg);padding:0.75rem;border-radius:var(--a-radius);font-size:0.8rem;overflow:auto"><code>:root {
  --primary: #ef4444;          /* rojo en vez de indigo */
  --accent: #10b981;           /* verde mint en vez de dorado */
}
.hero h1 { font-weight: 900; }
.site-footer { background: var(--bg-elevated); }</code></pre>

    <div class="admin-field">
        <label>custom_css</label>
        <textarea name="custom_css" style="min-height:220px;font-family:ui-monospace,Menlo,monospace;font-size:13px"><?= htmlspecialchars($values['custom_css'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <button type="submit" class="admin-btn admin-btn-primary">Guardar</button>
</form>
