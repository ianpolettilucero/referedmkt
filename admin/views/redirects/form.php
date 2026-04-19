<?php
/** @var \Admin\AdminView $view
 *  @var array  $row
 *  @var bool   $is_new
 *  @var string $csrf_token
 */
$view->layout('admin');
$action = $is_new ? '/admin/redirects' : '/admin/redirects/' . (int)$row['id'];
?>
<div class="admin-page-header">
    <h1 class="admin-page-title"><?= $is_new ? 'Nuevo redirect' : 'Editar redirect' ?></h1>
    <a class="admin-btn" href="/admin/redirects">← Volver</a>
</div>

<form method="post" action="<?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8') ?>" class="admin-form admin-card">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">

    <div class="admin-field">
        <label>Desde (path)</label>
        <input name="from_path" required value="<?= htmlspecialchars($row['from_path'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="/articulo-viejo">
        <small class="admin-hint">Empieza con "/". Sin trailing slash. Sin querystring.</small>
    </div>

    <div class="admin-field">
        <label>Hacia (path absoluto o URL completa)</label>
        <input name="to_path" required value="<?= htmlspecialchars($row['to_path'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="/articulo-nuevo">
    </div>

    <div class="admin-grid admin-grid-2">
        <div class="admin-field">
            <label>Código</label>
            <select name="status_code">
                <?php foreach ([301,302,307,308] as $c): ?>
                    <option value="<?= $c ?>" <?= (int)($row['status_code'] ?? 301) === $c ? 'selected' : '' ?>><?= $c ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="admin-field">
            <label><input type="checkbox" name="active" value="1" <?= !empty($row['active']) ? 'checked' : '' ?>> Activo</label>
        </div>
    </div>

    <button type="submit" class="admin-btn admin-btn-primary">Guardar</button>
</form>
