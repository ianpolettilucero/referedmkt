<?php
/** @var \Admin\AdminView $view
 *  @var array  $row
 *  @var bool   $is_new
 *  @var array  $categories
 *  @var array  $affiliate_links
 *  @var string $csrf_token
 */
$view->layout('admin');
$action = $is_new ? '/admin/products' : '/admin/products/' . (int)$row['id'];
$toLines = function ($arr) {
    if (!is_array($arr)) { return ''; }
    return implode("\n", array_map(fn($x) => is_scalar($x) ? (string)$x : json_encode($x), $arr));
};
$specsToText = function ($specs) {
    if (!is_array($specs)) { return ''; }
    $out = [];
    foreach ($specs as $k => $v) { $out[] = $k . ': ' . (is_scalar($v) ? $v : json_encode($v)); }
    return implode("\n", $out);
};
?>
<div class="admin-page-header">
    <h1 class="admin-page-title"><?= $is_new ? 'Nuevo producto' : 'Editar producto' ?></h1>
    <a class="admin-btn" href="/admin/products">← Volver</a>
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

    <div class="admin-grid admin-grid-2">
        <div class="admin-field">
            <label>Marca</label>
            <input name="brand" value="<?= htmlspecialchars($row['brand'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="admin-field">
            <label>Categoría</label>
            <select name="category_id">
                <option value="">— Ninguna —</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?= (int)$c['id'] ?>" <?= (int)($row['category_id'] ?? 0) === (int)$c['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($c['name'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="admin-grid admin-grid-2">
        <div class="admin-field">
            <label>Afiliado</label>
            <select name="affiliate_link_id">
                <option value="">— Ninguno —</option>
                <?php foreach ($affiliate_links as $a): ?>
                    <option value="<?= (int)$a['id'] ?>" <?= (int)($row['affiliate_link_id'] ?? 0) === (int)$a['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($a['name'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="admin-field">
            <label>Logo URL</label>
            <input name="logo_url" value="<?= htmlspecialchars($row['logo_url'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
    </div>

    <div class="admin-field">
        <label>Descripción corta (máx ~500 caracteres)</label>
        <textarea name="description_short" style="min-height:80px"><?= htmlspecialchars($row['description_short'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <div class="admin-field">
        <label>Descripción larga (Markdown)</label>
        <textarea name="description_long" style="min-height:220px"><?= htmlspecialchars($row['description_long'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <div class="admin-grid admin-grid-2">
        <div class="admin-field">
            <label>Rating (0-5)</label>
            <input type="number" step="0.1" min="0" max="5" name="rating" value="<?= htmlspecialchars((string)($row['rating'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="admin-field">
            <label>Precio desde</label>
            <input type="number" step="0.01" name="price_from" value="<?= htmlspecialchars((string)($row['price_from'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="admin-field">
            <label>Moneda</label>
            <input name="price_currency" maxlength="3" value="<?= htmlspecialchars((string)($row['price_currency'] ?? 'USD'), ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="admin-field">
            <label>Modelo de precio</label>
            <select name="pricing_model">
                <?php foreach (['one_time','monthly','yearly','free','custom'] as $opt): ?>
                    <option value="<?= $opt ?>" <?= ($row['pricing_model'] ?? 'custom') === $opt ? 'selected' : '' ?>><?= $opt ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="admin-grid admin-grid-2">
        <div class="admin-field">
            <label>Features (un item por linea)</label>
            <textarea name="features" style="min-height:140px"><?= htmlspecialchars($toLines($row['features'] ?? []), ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>
        <div class="admin-field">
            <label>Pros (un item por linea)</label>
            <textarea name="pros" style="min-height:140px"><?= htmlspecialchars($toLines($row['pros'] ?? []), ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>
        <div class="admin-field">
            <label>Contras (un item por linea)</label>
            <textarea name="cons" style="min-height:140px"><?= htmlspecialchars($toLines($row['cons'] ?? []), ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>
        <div class="admin-field">
            <label>Specs (KEY: VALUE por linea)</label>
            <textarea name="specs" style="min-height:140px" placeholder="Plataformas: Windows, macOS&#10;Licencia: por endpoint/año"><?= htmlspecialchars($specsToText($row['specs'] ?? []), ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>
    </div>

    <div class="admin-field">
        <label>Meta title</label>
        <input name="meta_title" value="<?= htmlspecialchars($row['meta_title'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </div>

    <div class="admin-field">
        <label>Meta description</label>
        <textarea name="meta_description"><?= htmlspecialchars($row['meta_description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <div class="admin-field">
        <label><input type="checkbox" name="featured" value="1" <?= !empty($row['featured']) ? 'checked' : '' ?>> Destacado en home</label>
    </div>

    <button type="submit" class="admin-btn admin-btn-primary">Guardar</button>
</form>
