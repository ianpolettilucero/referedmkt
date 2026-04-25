<?php
/** @var \Admin\AdminView $view
 *  @var array  $row
 *  @var bool   $is_new
 *  @var string $csrf_token
 */
$view->layout('admin');
$action = $is_new ? '/admin/sites' : '/admin/sites/' . (int)$row['id'];
?>
<div class="admin-page-header">
    <h1 class="admin-page-title"><?= $is_new ? 'Nuevo sitio' : 'Editar sitio' ?></h1>
    <a class="admin-btn" href="/admin/sites">← Volver</a>
</div>

<form method="post" action="<?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8') ?>" class="admin-form admin-card">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">

    <div class="admin-grid admin-grid-2">
        <div class="admin-field">
            <label>Nombre</label>
            <input name="name" value="<?= htmlspecialchars($row['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
        <div class="admin-field">
            <label>Slug</label>
            <input name="slug" value="<?= htmlspecialchars($row['slug'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="auto desde nombre">
        </div>
    </div>

    <div class="admin-grid admin-grid-2">
        <div class="admin-field">
            <label>Dominio</label>
            <input name="domain" value="<?= htmlspecialchars($row['domain'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="ejemplo.com" required>
            <small class="admin-hint">Sin "https://" y sin "www.". Ej: <code>ciberseguridadpyme.com</code></small>
        </div>
        <div class="admin-field">
            <label>Tema (carpeta en /themes)</label>
            <input name="theme_name" value="<?= htmlspecialchars($row['theme_name'] ?? 'default', ENT_QUOTES, 'UTF-8') ?>">
        </div>
    </div>

    <div class="admin-grid admin-grid-2">
        <div class="admin-field">
            <label>Color primario (hex)</label>
            <input name="primary_color" value="<?= htmlspecialchars($row['primary_color'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="#2b6cb0">
        </div>
        <div class="admin-field">
            <label>Logo URL</label>
            <input name="logo_url" value="<?= htmlspecialchars($row['logo_url'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
    </div>

    <div class="admin-grid admin-grid-2">
        <div class="admin-field">
            <label>Favicon URL</label>
            <input name="favicon_url" value="<?= htmlspecialchars($row['favicon_url'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="admin-field">
            <label>Google Analytics 4 ID</label>
            <input name="google_analytics_id" value="<?= htmlspecialchars($row['google_analytics_id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="G-XXXXXXXXXX">
            <small class="admin-hint">Formato <code>G-XXXXXXXXXX</code>. De analytics.google.com → Admin → Streams.</small>
        </div>
    </div>

    <div class="admin-grid admin-grid-2">
        <div class="admin-field">
            <label>Google Tag Manager ID (opcional)</label>
            <input name="google_tag_manager_id" value="<?= htmlspecialchars($row['google_tag_manager_id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="GTM-XXXXXXX">
            <small class="admin-hint">Solo si usás GTM como contenedor. Formato <code>GTM-XXXXXXX</code>. No hace falta si ya pusiste GA4 directo arriba.</small>
        </div>
        <div class="admin-field">
            <label>Google Search Console — código de verificación</label>
            <input name="google_search_console_verification" value="<?= htmlspecialchars($row['google_search_console_verification'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="abc123def456...">
            <small class="admin-hint">Solo el valor del <code>content="..."</code> que te da Google. No el meta tag entero.</small>
        </div>
    </div>

    <div class="admin-grid admin-grid-2">
        <div class="admin-field">
            <label>Google Ads ID (opcional)</label>
            <input name="google_ads_id" value="<?= htmlspecialchars($row['google_ads_id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="AW-XXXXXXXXX">
            <small class="admin-hint">Para conversiones / remarketing. Formato <code>AW-XXXXXXXXX</code>. Comparte el script gtag.js con GA4, no se duplica.</small>
        </div>
        <div class="admin-field">
            <label>Microsoft Clarity ID (opcional)</label>
            <input name="microsoft_clarity_id" value="<?= htmlspecialchars($row['microsoft_clarity_id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="wgytkj3a9g">
            <small class="admin-hint">Heatmaps + session recordings gratis. De clarity.microsoft.com → tu proyecto → Settings → Tracking code (es el ID corto, no el script entero).</small>
        </div>
    </div>

    <div class="admin-grid admin-grid-2">
        <div class="admin-field">
            <label>Meta Pixel ID (opcional)</label>
            <input name="meta_pixel_id" value="<?= htmlspecialchars($row['meta_pixel_id'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="123456789012345">
            <small class="admin-hint">Solo si vas a correr ads de Facebook/Instagram. ID numérico de Meta Business → Events Manager → Pixel.</small>
        </div>
        <div class="admin-field">
            <label>Idioma</label>
            <input name="default_language" value="<?= htmlspecialchars($row['default_language'] ?? 'es', ENT_QUOTES, 'UTF-8') ?>" maxlength="5">
        </div>
    </div>

    <div class="admin-grid admin-grid-2">
        <div class="admin-field">
            <label>País (ISO 2)</label>
            <input name="default_country" value="<?= htmlspecialchars($row['default_country'] ?? 'AR', ENT_QUOTES, 'UTF-8') ?>" maxlength="2">
        </div>
        <div class="admin-field">
            <!-- placeholder para mantener simetría del grid -->
        </div>
    </div>

    <div class="admin-field">
        <label>Meta title template</label>
        <input name="meta_title_template" value="<?= htmlspecialchars($row['meta_title_template'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="{title} | Nombre del sitio">
        <small class="admin-hint"><code>{title}</code> se reemplaza por el titulo de cada pagina.</small>
    </div>

    <div class="admin-field">
        <label>Meta description template</label>
        <textarea name="meta_description_template"><?= htmlspecialchars($row['meta_description_template'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <div class="admin-field">
        <label>Disclosure de afiliados</label>
        <textarea name="affiliate_disclosure_text"><?= htmlspecialchars($row['affiliate_disclosure_text'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <div class="admin-field">
        <label><input type="checkbox" name="active" value="1" <?= !empty($row['active']) ? 'checked' : '' ?>> Sitio activo</label>
    </div>

    <button type="submit" class="admin-btn admin-btn-primary">Guardar</button>
</form>
