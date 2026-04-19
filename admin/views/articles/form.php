<?php
/** @var \Admin\AdminView $view
 *  @var array  $row
 *  @var bool   $is_new
 *  @var array  $categories
 *  @var array  $authors
 *  @var array  $products
 *  @var string $csrf_token
 */
$view->layout('admin');
$action = $is_new ? '/admin/articles' : '/admin/articles/' . (int)$row['id'];
$selectedProductIds = is_array($row['related_product_ids'] ?? null) ? array_map('intval', $row['related_product_ids']) : [];
$publishedLocal = '';
if (!empty($row['published_at'])) {
    $publishedLocal = date('Y-m-d\TH:i', strtotime($row['published_at']));
}
?>
<div class="admin-page-header">
    <h1 class="admin-page-title"><?= $is_new ? 'Nuevo artículo' : 'Editar artículo' ?></h1>
    <a class="admin-btn" href="/admin/articles">← Volver</a>
</div>

<form method="post" action="<?= htmlspecialchars($action, ENT_QUOTES, 'UTF-8') ?>" class="admin-form admin-card" id="article-form">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">

    <div class="admin-grid admin-grid-2">
        <div class="admin-field">
            <label>Título</label>
            <input name="title" required value="<?= htmlspecialchars($row['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
        <div class="admin-field">
            <label>Slug</label>
            <input name="slug" value="<?= htmlspecialchars($row['slug'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
        </div>
    </div>

    <div class="admin-field">
        <label>Subtítulo</label>
        <input name="subtitle" value="<?= htmlspecialchars($row['subtitle'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
    </div>

    <div class="admin-field">
        <label>Excerpt</label>
        <textarea name="excerpt" style="min-height:80px"><?= htmlspecialchars($row['excerpt'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
    </div>

    <div class="admin-grid admin-grid-2">
        <div class="admin-field">
            <label>Tipo</label>
            <select name="article_type">
                <?php foreach (['guide'=>'Guía','review'=>'Reseña','comparison'=>'Comparativa','news'=>'Noticia'] as $k => $v): ?>
                    <option value="<?= $k ?>" <?= ($row['article_type'] ?? 'guide') === $k ? 'selected' : '' ?>><?= $v ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="admin-field">
            <label>Estado</label>
            <select name="status">
                <?php foreach (['draft'=>'Borrador','published'=>'Publicado','archived'=>'Archivado'] as $k => $v): ?>
                    <option value="<?= $k ?>" <?= ($row['status'] ?? 'draft') === $k ? 'selected' : '' ?>><?= $v ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="admin-grid admin-grid-2">
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
        <div class="admin-field">
            <label>Autor</label>
            <select name="author_id">
                <option value="">— Ninguno —</option>
                <?php foreach ($authors as $a): ?>
                    <option value="<?= (int)$a['id'] ?>" <?= (int)($row['author_id'] ?? 0) === (int)$a['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($a['name'], ENT_QUOTES, 'UTF-8') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="admin-grid admin-grid-2">
        <div class="admin-field">
            <label>Imagen destacada</label>
            <div style="display:flex;gap:0.3rem">
                <input name="featured_image" id="featured_image" value="<?= htmlspecialchars($row['featured_image'] ?? '', ENT_QUOTES, 'UTF-8') ?>" style="flex:1">
                <button type="button" class="admin-btn" data-picker-target="featured_image">Elegir…</button>
            </div>
        </div>
        <div class="admin-field">
            <label>Fecha de publicación</label>
            <input type="datetime-local" name="published_at" value="<?= htmlspecialchars($publishedLocal, ENT_QUOTES, 'UTF-8') ?>">
            <small class="admin-hint">Si está vacía y el estado es "Publicado", se usa la hora actual.</small>
        </div>
    </div>

    <div class="admin-field">
        <label>Productos relacionados</label>
        <select name="related_product_ids[]" multiple size="6">
            <?php foreach ($products as $p): ?>
                <option value="<?= (int)$p['id'] ?>" <?= in_array((int)$p['id'], $selectedProductIds, true) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="admin-field">
        <label>Contenido (Markdown)</label>
        <textarea name="content" id="content" style="min-height:360px" required><?= htmlspecialchars($row['content'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        <div style="display:flex;gap:0.5rem;align-items:center;margin-top:0.3rem;flex-wrap:wrap">
            <button type="button" id="preview-btn" class="admin-btn admin-btn-subtle">Previsualizar</button>
            <button type="button" class="admin-btn admin-btn-subtle" data-picker-target="content" data-picker-mode="markdown">Insertar imagen…</button>
            <small class="admin-hint">Markdown soportado: #, listas, **bold**, *italic*, `code`, ```fences```, [link](url), ![img](src), > quote.</small>
        </div>
        <div id="preview" class="admin-card" style="margin-top:0.5rem;display:none"></div>
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

<?= $view->partial('image_picker', ['csrf_token' => $csrf_token]) ?>
<script>
(function(){
    const btn = document.getElementById('preview-btn');
    const preview = document.getElementById('preview');
    const content = document.getElementById('content');
    const csrf = <?= json_encode($csrf_token) ?>;
    btn.addEventListener('click', async function () {
        btn.disabled = true;
        try {
            const fd = new FormData();
            fd.append('_csrf', csrf);
            fd.append('content', content.value);
            const res = await fetch('/admin/articles/preview', { method: 'POST', body: fd });
            preview.innerHTML = await res.text();
            preview.style.display = 'block';
        } catch (e) {
            preview.textContent = 'Error: ' + e.message;
            preview.style.display = 'block';
        } finally {
            btn.disabled = false;
        }
    });
})();
</script>
