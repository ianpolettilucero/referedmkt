<?php
/** @var \Admin\AdminView $view
 *  @var array  $row
 *  @var bool   $is_new
 *  @var string $csrf_token
 */
$view->layout('admin');
$action = $is_new ? '/admin/affiliate-links' : '/admin/affiliate-links/' . (int)$row['id'];
?>
<div class="admin-page-header">
    <h1 class="admin-page-title"><?= $is_new ? 'Nuevo afiliado' : 'Editar afiliado' ?></h1>
    <a class="admin-btn" href="/admin/affiliate-links">← Volver</a>
</div>

<form method="post" action="<?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8') ?>" class="admin-form admin-card">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">

    <div class="admin-grid admin-grid-2">
        <div class="admin-field">
            <label>Nombre</label>
            <input name="name" required value="<?= htmlspecialchars($row['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="admin-field">
            <label>Red</label>
            <input name="network_name" value="<?= htmlspecialchars($row['network_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="Impact, PartnerStack, directo">
        </div>
    </div>

    <div class="admin-field">
        <label>URL destino (con codigo de afiliado)</label>
        <input name="destination_url" required value="<?= htmlspecialchars($row['destination_url'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="https://vendor.com/?aff=XYZ">
    </div>

    <div class="admin-field">
        <label>Tracking slug</label>
        <input name="tracking_slug" value="<?= htmlspecialchars($row['tracking_slug'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="bitdefender">
        <small class="admin-hint">La URL publica sera <code>/go/{slug}</code>.</small>
    </div>

    <div class="admin-field">
        <label>Estructura de comision</label>
        <input name="commission_structure" value="<?= htmlspecialchars($row['commission_structure'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="30% primer pago, 90 dias cookie">
    </div>

    <div class="admin-field">
        <label>Notas internas</label>
        <textarea name="notes"><?= htmlspecialchars($row['notes'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <div class="admin-field">
        <label><input type="checkbox" name="active" value="1" <?= !empty($row['active']) ? 'checked' : '' ?>> Activo</label>
    </div>

    <button type="submit" class="admin-btn admin-btn-primary">Guardar</button>
</form>
