<?php
/** @var \Admin\AdminView $view
 *  @var array  $rows
 *  @var string $csrf_token
 */
$view->layout('admin');
?>
<div class="admin-page-header">
    <h1 class="admin-page-title">Biblioteca de imágenes</h1>
</div>

<form method="post" action="/admin/uploads" enctype="multipart/form-data" class="admin-card admin-form">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
    <div class="admin-grid admin-grid-2">
        <div class="admin-field">
            <label>Archivo</label>
            <input type="file" name="file" accept="image/jpeg,image/png,image/webp,image/gif,image/svg+xml" required>
            <small class="admin-hint">Max 5 MB. Formatos: JPG, PNG, WebP, GIF, SVG.</small>
        </div>
        <div class="admin-field">
            <label>Texto alternativo (alt)</label>
            <input name="alt_text" maxlength="255" placeholder="Descripcion breve para accesibilidad y SEO">
        </div>
    </div>
    <button type="submit" class="admin-btn admin-btn-primary">Subir</button>
</form>

<?php if (!$rows): ?>
    <div class="admin-card admin-empty" style="margin-top:1rem">Sin imágenes todavía.</div>
<?php else: ?>
    <div class="admin-card" style="margin-top:1rem">
        <div class="upload-grid" style="display:grid;gap:0.75rem;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));">
            <?php foreach ($rows as $r): ?>
                <?php $url = '/' . ltrim($r['path'], '/'); ?>
                <div class="upload-item" style="border:1px solid var(--a-border);border-radius:var(--a-radius);overflow:hidden;background:#fff">
                    <?php $isSvg = ($r['mime_type'] ?? '') === 'image/svg+xml'; ?>
                    <a href="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener">
                        <img src="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($r['alt_text'] ?? $r['original_name'], ENT_QUOTES, 'UTF-8') ?>" style="width:100%;aspect-ratio:1/1;object-fit:contain;background:#f6f7f9" loading="lazy">
                    </a>
                    <div style="padding:0.4rem 0.5rem;font-size:0.8rem;">
                        <div title="<?= htmlspecialchars($r['original_name'], ENT_QUOTES, 'UTF-8') ?>" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                            <?= htmlspecialchars($r['original_name'], ENT_QUOTES, 'UTF-8') ?>
                        </div>
                        <div class="admin-muted">
                            <?= number_format($r['size_bytes'] / 1024, 0) ?> KB
                            <?= $r['width'] ? ' · ' . (int)$r['width'] . '×' . (int)$r['height'] : '' ?>
                        </div>
                        <div style="display:flex;gap:0.3rem;margin-top:0.3rem;align-items:center;flex-wrap:wrap">
                            <button type="button" class="admin-btn admin-btn-subtle" onclick="copyToClipboard('<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>', this)">Copiar URL</button>
                            <button type="button" class="admin-btn admin-btn-subtle" onclick="copyToClipboard('![<?= htmlspecialchars(addslashes($r['alt_text'] ?? ''), ENT_QUOTES, 'UTF-8') ?>](<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>)', this)">Markdown</button>
                            <form method="post" action="/admin/uploads/<?= (int)$r['id'] ?>/delete" class="admin-inline-form" onsubmit="return confirm('Eliminar esta imagen? La URL dejara de funcionar.')">
                                <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                                <button type="submit" class="admin-btn admin-btn-subtle" style="color:var(--a-danger)">Borrar</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<script>
function copyToClipboard(text, btn) {
    const orig = btn.textContent;
    (navigator.clipboard?.writeText(text) ?? Promise.reject()).then(() => {
        btn.textContent = 'Copiado!';
        setTimeout(() => { btn.textContent = orig; }, 1200);
    }).catch(() => {
        const ta = document.createElement('textarea');
        ta.value = text; document.body.appendChild(ta); ta.select();
        document.execCommand('copy'); ta.remove();
        btn.textContent = 'Copiado!';
        setTimeout(() => { btn.textContent = orig; }, 1200);
    });
}
</script>
