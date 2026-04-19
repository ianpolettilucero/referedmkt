<?php
/** @var \Admin\AdminView $view
 *  @var array  $row
 *  @var bool   $is_new
 *  @var string $csrf_token
 */
$view->layout('admin');
$action = $is_new ? '/admin/authors' : '/admin/authors/' . (int)$row['id'];
$social = is_array($row['social_links'] ?? null) ? $row['social_links'] : [];
?>
<div class="admin-page-header">
    <h1 class="admin-page-title"><?= $is_new ? 'Nuevo autor' : 'Editar autor' ?></h1>
    <a class="admin-btn" href="/admin/authors">← Volver</a>
</div>

<form method="post" action="<?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8') ?>" class="admin-form admin-card">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">

    <div class="admin-grid admin-grid-2">
        <div class="admin-field">
            <label>Nombre</label>
            <input name="name" required value="<?= htmlspecialchars($row['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="admin-field">
            <label>Slug</label>
            <input name="slug" value="<?= htmlspecialchars($row['slug'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
    </div>

    <div class="admin-field">
        <label>Expertise</label>
        <input name="expertise" value="<?= htmlspecialchars($row['expertise'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Ciberseguridad, SaaS B2B, privacidad">
    </div>

    <div class="admin-field">
        <label>Avatar URL</label>
        <input name="avatar_url" value="<?= htmlspecialchars($row['avatar_url'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </div>

    <div class="admin-field">
        <label>Bio</label>
        <textarea name="bio"><?= htmlspecialchars($row['bio'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <div class="admin-grid admin-grid-2">
        <div class="admin-field">
            <label>Twitter / X</label>
            <input name="social_twitter" value="<?= htmlspecialchars($social['twitter'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="admin-field">
            <label>LinkedIn</label>
            <input name="social_linkedin" value="<?= htmlspecialchars($social['linkedin'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="admin-field">
            <label>Website</label>
            <input name="social_website" value="<?= htmlspecialchars($social['website'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="admin-field">
            <label>GitHub</label>
            <input name="social_github" value="<?= htmlspecialchars($social['github'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
    </div>

    <button type="submit" class="admin-btn admin-btn-primary">Guardar</button>
</form>
