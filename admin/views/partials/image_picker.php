<?php /** @var string $csrf_token */ ?>
<div id="image-picker-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1000;align-items:center;justify-content:center;padding:1rem">
    <div style="background:#fff;max-width:900px;width:100%;max-height:85vh;overflow:auto;border-radius:8px;padding:1rem">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.75rem">
            <h3 style="margin:0">Elegir imagen</h3>
            <div style="display:flex;gap:0.5rem;align-items:center">
                <a href="/admin/uploads" class="admin-btn admin-btn-subtle" target="_blank">Subir nueva →</a>
                <button type="button" class="admin-btn admin-btn-subtle" id="image-picker-close">Cerrar</button>
            </div>
        </div>
        <div id="image-picker-grid" style="display:grid;gap:0.5rem;grid-template-columns:repeat(auto-fill,minmax(140px,1fr))">
            <div class="admin-muted" style="grid-column:1/-1;padding:1rem;text-align:center">Cargando…</div>
        </div>
    </div>
</div>

<script>
(function(){
    const modal = document.getElementById('image-picker-modal');
    const grid  = document.getElementById('image-picker-grid');
    const closeBtn = document.getElementById('image-picker-close');
    let activeTarget = null;
    let activeMode = null;
    let loaded = false;

    async function load() {
        try {
            const res = await fetch('/admin/uploads.json');
            const data = await res.json();
            if (!data.items || data.items.length === 0) {
                grid.innerHTML = '<div class="admin-muted" style="grid-column:1/-1;padding:1rem;text-align:center">Sin imágenes. <a href="/admin/uploads" target="_blank">Subir una</a>.</div>';
                return;
            }
            grid.innerHTML = data.items.map(it =>
                `<button type="button" class="image-picker-item" data-url="${escape(it.url)}" data-alt="${escape(it.alt_text || '')}"
                    style="padding:0;border:1px solid #ddd;background:#fff;border-radius:6px;cursor:pointer;overflow:hidden">
                    <img src="${escape(it.url)}" loading="lazy" style="width:100%;aspect-ratio:1/1;object-fit:contain;background:#f6f7f9" alt="">
                    <div style="padding:0.3rem;font-size:0.75rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">${escape(it.filename)}</div>
                </button>`
            ).join('');
        } catch (e) {
            grid.innerHTML = '<div style="grid-column:1/-1;padding:1rem;color:#c53030">Error cargando imágenes.</div>';
        }
    }
    function escape(s) {
        return String(s).replace(/["&<>']/g, c => ({'"':'&quot;','&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#39;'}[c]));
    }

    document.addEventListener('click', async function (e) {
        const trigger = e.target.closest('[data-picker-target]');
        if (trigger) {
            e.preventDefault();
            activeTarget = trigger.getAttribute('data-picker-target');
            activeMode   = trigger.getAttribute('data-picker-mode') || 'url';
            modal.style.display = 'flex';
            if (!loaded) { loaded = true; await load(); }
            return;
        }
        const item = e.target.closest('.image-picker-item');
        if (item && activeTarget) {
            const url = item.getAttribute('data-url');
            const alt = item.getAttribute('data-alt');
            const el = document.getElementById(activeTarget) || document.querySelector('[name="' + activeTarget + '"]');
            if (el) {
                if (activeMode === 'markdown' && el.tagName === 'TEXTAREA') {
                    const snippet = `\n\n![${alt || ''}](${url})\n\n`;
                    const start = el.selectionStart, endPos = el.selectionEnd;
                    el.value = el.value.slice(0, start) + snippet + el.value.slice(endPos);
                    el.focus();
                    el.setSelectionRange(start + snippet.length, start + snippet.length);
                } else {
                    el.value = url;
                    el.dispatchEvent(new Event('change', { bubbles: true }));
                }
            }
            modal.style.display = 'none';
        }
    });

    closeBtn.addEventListener('click', () => { modal.style.display = 'none'; });
    modal.addEventListener('click', (e) => { if (e.target === modal) { modal.style.display = 'none'; } });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') { modal.style.display = 'none'; } });
})();
</script>
