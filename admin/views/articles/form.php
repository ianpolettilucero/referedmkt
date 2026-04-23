<?php
/** @var \Admin\AdminView $view
 *  @var array  $row
 *  @var bool   $is_new
 *  @var array  $categories
 *  @var array  $authors
 *  @var array  $products
 *  @var array  $broken_links
 *  @var string $csrf_token
 */
$view->layout('admin');
$action = $is_new ? '/admin/articles' : '/admin/articles/' . (int)$row['id'];
$selectedProductIds = is_array($row['related_product_ids'] ?? null) ? array_map('intval', $row['related_product_ids']) : [];
$publishedLocal = '';
if (!empty($row['published_at'])) {
    $publishedLocal = date('Y-m-d\TH:i', strtotime($row['published_at']));
}
$broken_links = $broken_links ?? [];
?>
<div class="admin-page-header">
    <h1 class="admin-page-title"><?= $is_new ? 'Nuevo artículo' : 'Editar artículo' ?></h1>
    <a class="admin-btn" href="/admin/articles">← Volver</a>
</div>

<?php if (!$is_new && !empty($broken_links)): ?>
    <div class="admin-flash admin-flash-error" style="margin-bottom:1rem;flex-direction:column;align-items:flex-start;gap:0.5rem">
        <strong>⚠ Este artículo tiene <?= count($broken_links) ?> link(s) con problemas:</strong>
        <ul style="margin:0;padding-left:1.25rem;font-size:0.88rem">
            <?php foreach ($broken_links as $bl): ?>
                <li>
                    <code style="font-size:0.85em;word-break:break-all"><?= htmlspecialchars($bl['url'], ENT_QUOTES, 'UTF-8') ?></code>
                    — <?= $bl['status_code'] ? (int)$bl['status_code'] : 'no responde' ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <a class="admin-btn admin-btn-primary" href="/admin/link-health">Ver panel de health check →</a>
    </div>
<?php endif; ?>

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
        <label>Productos relacionados
            <small class="admin-hint" style="display:inline;font-weight:400">(opcional — aparecen al final del artículo)</small>
        </label>
        <?php if (!$products): ?>
            <div class="admin-muted" style="padding:0.75rem;border:1px dashed var(--a-border);border-radius:var(--a-radius-sm)">
                Todavía no hay productos cargados. <a href="/admin/products/new">Creá el primero</a>.
            </div>
        <?php else: ?>
            <div class="product-picker">
                <input type="search" class="product-picker-search" placeholder="Buscar por nombre o marca…" aria-label="Buscar producto">
                <div class="product-picker-actions">
                    <button type="button" class="admin-btn admin-btn-subtle" data-picker-action="select-all">Marcar todos</button>
                    <button type="button" class="admin-btn admin-btn-subtle" data-picker-action="clear-all">Desmarcar todos</button>
                    <span class="product-picker-count admin-muted"><?= count($selectedProductIds) ?> seleccionado(s)</span>
                </div>
                <div class="product-picker-list">
                    <?php foreach ($products as $p): ?>
                        <?php $checked = in_array((int)$p['id'], $selectedProductIds, true); ?>
                        <label class="product-picker-item<?= $checked ? ' is-checked' : '' ?>">
                            <input type="checkbox" name="related_product_ids[]" value="<?= (int)$p['id'] ?>" <?= $checked ? 'checked' : '' ?>>
                            <span class="product-picker-name">
                                <?= htmlspecialchars($p['name'], ENT_QUOTES, 'UTF-8') ?>
                                <?php if (!empty($p['brand'])): ?>
                                    <small class="admin-muted"><?= htmlspecialchars($p['brand'], ENT_QUOTES, 'UTF-8') ?></small>
                                <?php endif; ?>
                                <?php if (!empty($p['affiliate_slug'])): ?>
                                    <span class="admin-badge admin-badge-success" style="font-size:0.65rem">afiliado</span>
                                <?php endif; ?>
                            </span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="admin-field">
        <label>Contenido (Markdown)</label>
        <textarea name="content" id="content" style="min-height:400px" required><?= htmlspecialchars($row['content'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        <div style="display:flex;gap:0.5rem;align-items:center;margin-top:0.3rem;flex-wrap:wrap">
            <button type="button" id="preview-btn" class="admin-btn admin-btn-subtle">Previsualizar</button>
            <button type="button" class="admin-btn admin-btn-subtle" data-picker-target="content" data-picker-mode="markdown">Insertar imagen…</button>
            <button type="button" class="admin-btn admin-btn-subtle" id="affiliate-picker-btn">Insertar link de producto…</button>
            <button type="button" class="admin-btn admin-btn-subtle" onclick="document.getElementById('md-cheat').open=!document.getElementById('md-cheat').open">Sintaxis Markdown ▾</button>
        </div>
        <details id="md-cheat" style="margin-top:0.5rem;background:var(--a-bg-elev);border:1px solid var(--a-border);border-radius:var(--a-radius);padding:0.75rem 1rem;font-size:0.85rem">
            <summary style="cursor:pointer;font-weight:600;color:var(--a-text)">Sintaxis Markdown soportada (click para ver)</summary>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-top:0.75rem;font-family:ui-monospace,Menlo,monospace;font-size:12px">
                <div>
                    <strong style="font-family:var(--a-font)">Títulos</strong><br>
                    <code># Título principal (H1)</code><br>
                    <code>## Subtítulo (H2)</code><br>
                    <code>### Sección (H3)</code><br><br>

                    <strong style="font-family:var(--a-font)">Texto</strong><br>
                    <code>**negrita**</code> → <strong>negrita</strong><br>
                    <code>*cursiva*</code> → <em>cursiva</em><br>
                    <code>`inline code`</code><br><br>

                    <strong style="font-family:var(--a-font)">Listas</strong><br>
                    <code>- item</code><br>
                    <code>- otro item</code><br><br>
                    <code>1. primero</code><br>
                    <code>2. segundo</code><br>
                </div>
                <div>
                    <strong style="font-family:var(--a-font)">Links e imágenes</strong><br>
                    <code>[texto](https://url.com)</code><br>
                    <code>![alt](https://url.com/img.jpg)</code><br><br>

                    <strong style="font-family:var(--a-font)">Cita</strong><br>
                    <code>&gt; Texto citado</code><br><br>

                    <strong style="font-family:var(--a-font)">Código en bloque</strong><br>
                    <code>```<br>codigo<br>```</code><br><br>

                    <strong style="font-family:var(--a-font)">Tabla</strong><br>
                    <code>| A | B |</code><br>
                    <code>|---|---|</code><br>
                    <code>| x | y |</code><br><br>

                    <strong style="font-family:var(--a-font)">Separador</strong><br>
                    <code>---</code> (línea horizontal)
                </div>
            </div>
            <p style="margin:0.75rem 0 0;font-family:var(--a-font);color:var(--a-text-muted)">
                Tip: dejá una línea en blanco entre párrafos. Los enlaces externos se agregan
                automáticamente con <code>rel="nofollow noopener"</code> y <code>target="_blank"</code>.
            </p>
        </details>
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

<!-- Modal: Insertar link de producto/afiliado -->
<div id="affiliate-picker-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;padding:1rem">
    <div style="background:var(--a-surface);max-width:720px;width:100%;max-height:85vh;overflow:auto;border-radius:var(--a-radius-lg);padding:1.25rem;border:1px solid var(--a-border)">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.75rem">
            <h3 style="margin:0">Insertar link de producto</h3>
            <button type="button" class="admin-btn admin-btn-subtle" id="affiliate-picker-close">Cerrar</button>
        </div>
        <p class="admin-muted" style="margin-top:0">
            Elegí un producto. Click en un botón para insertar el link en tu posición del cursor.
            <strong>Review</strong> lleva al usuario a tu página del producto (recomendado: más contexto, mejor conversión).
            <strong>Afiliado</strong> salta directo al sitio del vendor (para CTAs finales).
        </p>
        <input type="search" id="affiliate-picker-search" placeholder="Buscar…" style="width:100%;padding:0.5rem 0.7rem;margin-bottom:0.75rem;background:var(--a-bg-elev);border:1px solid var(--a-border);border-radius:var(--a-radius-sm);color:var(--a-text);font:inherit">
        <div id="affiliate-picker-list" style="display:flex;flex-direction:column;gap:0.4rem"></div>
    </div>
</div>

<script>
(function(){
    const products = <?= json_encode(array_map(fn($p) => [
        'id' => (int)$p['id'],
        'name' => $p['name'],
        'slug' => $p['slug'],
        'brand' => $p['brand'] ?? '',
        'affiliate_slug' => $p['affiliate_slug'] ?? null,
    ], $products), JSON_UNESCAPED_UNICODE) ?>;
    const modal = document.getElementById('affiliate-picker-modal');
    const list  = document.getElementById('affiliate-picker-list');
    const search= document.getElementById('affiliate-picker-search');
    const content = document.getElementById('content');

    function escape(s){return String(s).replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));}

    function render(filter){
        const q = (filter || '').toLowerCase().trim();
        const items = products.filter(p =>
            !q || p.name.toLowerCase().includes(q) || (p.brand || '').toLowerCase().includes(q));
        if (!items.length) {
            list.innerHTML = '<p class="admin-muted" style="text-align:center;padding:1rem">Sin productos.</p>';
            return;
        }
        list.innerHTML = items.map(p => {
            const reviewUrl = '/producto/' + p.slug;
            const aff = p.affiliate_slug ? ('/go/' + p.affiliate_slug) : null;
            return `<div style="display:flex;gap:0.5rem;align-items:center;padding:0.6rem;border:1px solid var(--a-border);border-radius:var(--a-radius-sm);background:var(--a-bg-elev)">
                <div style="flex:1;min-width:0">
                    <strong>${escape(p.name)}</strong>
                    ${p.brand ? `<small class="admin-muted" style="margin-left:0.4rem">${escape(p.brand)}</small>` : ''}
                </div>
                <button type="button" class="admin-btn admin-btn-subtle" data-action="review" data-name="${escape(p.name)}" data-url="${escape(reviewUrl)}">Review</button>
                ${aff ? `<button type="button" class="admin-btn admin-btn-primary" data-action="affiliate" data-name="${escape(p.name)}" data-url="${escape(aff)}">Afiliado</button>` : `<span class="admin-muted" style="font-size:0.8rem">sin afiliado</span>`}
            </div>`;
        }).join('');
    }

    function insertAtCursor(snippet){
        const start = content.selectionStart;
        const end = content.selectionEnd;
        content.value = content.value.slice(0, start) + snippet + content.value.slice(end);
        const pos = start + snippet.length;
        content.focus();
        content.setSelectionRange(pos, pos);
    }

    document.getElementById('affiliate-picker-btn').addEventListener('click', function(){
        modal.style.display = 'flex';
        search.value = '';
        render('');
        search.focus();
    });
    document.getElementById('affiliate-picker-close').addEventListener('click', () => { modal.style.display='none'; });
    modal.addEventListener('click', e => { if (e.target === modal) modal.style.display='none'; });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') modal.style.display='none'; });
    search.addEventListener('input', e => render(e.target.value));

    list.addEventListener('click', function(e){
        const btn = e.target.closest('button[data-action]');
        if (!btn) return;
        const name = btn.getAttribute('data-name');
        const url = btn.getAttribute('data-url');
        insertAtCursor('[' + name + '](' + url + ')');
        modal.style.display = 'none';
    });
})();
</script>

<script>
(function(){
    // Multi-select de productos con search y actions
    const picker = document.querySelector('.product-picker');
    if (!picker) return;
    const search = picker.querySelector('.product-picker-search');
    const items = picker.querySelectorAll('.product-picker-item');
    const count = picker.querySelector('.product-picker-count');

    function updateCount(){
        const n = picker.querySelectorAll('input[type="checkbox"]:checked').length;
        count.textContent = n + ' seleccionado(s)';
    }
    function filter(q){
        q = (q || '').toLowerCase().trim();
        items.forEach(item => {
            const txt = item.textContent.toLowerCase();
            item.style.display = !q || txt.includes(q) ? '' : 'none';
        });
    }

    search.addEventListener('input', e => filter(e.target.value));
    items.forEach(item => {
        const cb = item.querySelector('input[type="checkbox"]');
        cb.addEventListener('change', () => {
            item.classList.toggle('is-checked', cb.checked);
            updateCount();
        });
    });
    picker.querySelector('[data-picker-action="select-all"]').addEventListener('click', () => {
        items.forEach(item => {
            if (item.style.display === 'none') return;
            const cb = item.querySelector('input[type="checkbox"]');
            cb.checked = true;
            item.classList.add('is-checked');
        });
        updateCount();
    });
    picker.querySelector('[data-picker-action="clear-all"]').addEventListener('click', () => {
        items.forEach(item => {
            const cb = item.querySelector('input[type="checkbox"]');
            cb.checked = false;
            item.classList.remove('is-checked');
        });
        updateCount();
    });
})();
</script>

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
