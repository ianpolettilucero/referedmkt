<?php
/** @var \Admin\AdminView $view
 *  @var array  $row
 *  @var bool   $is_new
 *  @var array  $parents
 *  @var string $csrf_token
 */
$view->layout('admin');
$action = $is_new ? '/admin/categories' : '/admin/categories/' . (int)$row['id'];
?>
<div class="admin-page-header">
    <h1 class="admin-page-title"><?= $is_new ? 'Nueva categoría' : 'Editar categoría' ?></h1>
    <a class="admin-btn" href="/admin/categories">← Volver</a>
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
            <input name="slug" value="<?= htmlspecialchars($row['slug'] ?? '', ENT_QUOTES, 'UTF-8') ?>" placeholder="auto desde nombre">
        </div>
    </div>

    <div class="admin-grid admin-grid-2">
        <div class="admin-field">
            <label>Categoría padre</label>
            <select name="parent_id">
                <option value="">— Ninguna —</option>
                <?php foreach ($parents as $p): ?>
                    <option value="<?= (int)$p['id'] ?>" <?= (int)($row['parent_id'] ?? 0) === (int)$p['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="admin-field">
            <label>Orden</label>
            <input type="number" name="sort_order" value="<?= (int)($row['sort_order'] ?? 0) ?>">
        </div>
    </div>

    <div class="admin-field">
        <label>Descripción</label>
        <textarea name="description"><?= htmlspecialchars($row['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <div class="admin-field">
        <label>Imagen destacada (URL)</label>
        <input name="featured_image" value="<?= htmlspecialchars($row['featured_image'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </div>

    <div class="admin-field">
        <label>Meta title</label>
        <input name="meta_title" value="<?= htmlspecialchars($row['meta_title'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </div>

    <div class="admin-field">
        <label>Meta description</label>
        <textarea name="meta_description"><?= htmlspecialchars($row['meta_description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <button type="submit" class="admin-btn admin-btn-primary">Guardar</button>
</form>
