<?php
/** @var \Admin\AdminView $view
 *  @var array  $broken
 *  @var array  $suspicious
 *  @var array  $redirected
 *  @var array  $ignored
 *  @var string $csrf_token
 */
$view->layout('admin');

$articleEditUrl = static fn(int $id): string => '/admin/articles/' . $id . '/edit';

$statusBadge = static function ($s): array {
    if ($s === null) { return ['ERR',  'admin-badge-danger']; }
    $s = (int)$s;
    if ($s === 404 || $s === 410)               { return [(string)$s, 'admin-badge-danger']; }
    if ($s >= 500)                              { return [(string)$s, 'admin-badge-danger']; }
    if ($s === 403 || $s === 429)               { return [(string)$s, 'admin-badge-warning']; }
    return [(string)$s, 'admin-badge-success'];
};

$timeAgo = static function (?string $ts): string {
    if (!$ts) { return '—'; }
    $diff = time() - strtotime($ts);
    if ($diff < 60)        { return 'hace ' . $diff . 's'; }
    if ($diff < 3600)      { return 'hace ' . (int)floor($diff/60) . 'm'; }
    if ($diff < 86400)     { return 'hace ' . (int)floor($diff/3600) . 'h'; }
    return 'hace ' . (int)floor($diff/86400) . 'd';
};

$renderRow = static function (array $r, bool $showFix, bool $showIgnore, bool $showUnignore) use ($csrf_token, $statusBadge, $timeAgo, $articleEditUrl) {
    [$label, $cls] = $statusBadge($r['status_code']);
    $err = $r['error_message'] ?? null;
    ?>
    <tr>
        <td style="max-width:440px">
            <div style="display:flex;flex-direction:column;gap:0.2rem">
                <code style="font-size:0.78rem;word-break:break-all"><?= htmlspecialchars($r['url'], ENT_QUOTES, 'UTF-8') ?></code>
                <?php if (!empty($r['final_url'])): ?>
                    <div class="admin-muted" style="font-size:0.78rem">
                        ↳ <code style="word-break:break-all"><?= htmlspecialchars($r['final_url'], ENT_QUOTES, 'UTF-8') ?></code>
                    </div>
                <?php endif; ?>
                <?php if ($err): ?>
                    <div style="color:var(--a-danger);font-size:0.78rem">⚠ <?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
            </div>
        </td>
        <td><span class="admin-badge <?= $cls ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span></td>
        <td>
            <a href="<?= htmlspecialchars($articleEditUrl((int)$r['article_id']), ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars($r['article_title'], ENT_QUOTES, 'UTF-8') ?>
            </a>
        </td>
        <td class="admin-muted" style="font-size:0.8rem;white-space:nowrap">
            <?php if (!empty($r['first_seen_broken_at'])): ?>
                roto <?= htmlspecialchars($timeAgo($r['first_seen_broken_at']), ENT_QUOTES, 'UTF-8') ?><br>
            <?php endif; ?>
            check <?= htmlspecialchars($timeAgo($r['last_checked_at']), ENT_QUOTES, 'UTF-8') ?>
        </td>
        <td class="admin-row-actions">
            <form method="post" action="/admin/link-health/check-article/<?= (int)$r['article_id'] ?>" class="admin-inline-form">
                <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                <button class="admin-btn admin-btn-subtle" title="Re-chequear">⟳</button>
            </form>
            <?php if ($showFix && !empty($r['final_url'])): ?>
                <form method="post" action="/admin/link-health/<?= (int)$r['id'] ?>/apply-fix" class="admin-inline-form"
                      onsubmit="return confirm('Reemplazar la URL vieja por la nueva en el contenido del articulo?\n\nViejo: <?= htmlspecialchars(addslashes($r['url']), ENT_QUOTES, 'UTF-8') ?>\nNuevo: <?= htmlspecialchars(addslashes($r['final_url']), ENT_QUOTES, 'UTF-8') ?>')">
                    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                    <button class="admin-btn admin-btn-primary" style="font-size:0.8rem">Aplicar redirect</button>
                </form>
            <?php endif; ?>
            <?php if ($showIgnore): ?>
                <form method="post" action="/admin/link-health/<?= (int)$r['id'] ?>/ignore" class="admin-inline-form"
                      onsubmit="return confirm('Silenciar este link como falso positivo?')">
                    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                    <button class="admin-btn admin-btn-subtle" style="font-size:0.8rem">Marcar OK</button>
                </form>
            <?php endif; ?>
            <?php if ($showUnignore): ?>
                <form method="post" action="/admin/link-health/<?= (int)$r['id'] ?>/unignore" class="admin-inline-form">
                    <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                    <button class="admin-btn admin-btn-subtle" style="font-size:0.8rem">Reactivar</button>
                </form>
            <?php endif; ?>
        </td>
    </tr>
    <?php
};
?>
<div class="admin-page-header">
    <h1 class="admin-page-title">Health check de links</h1>
    <form method="post" action="/admin/link-health/check-all" style="margin:0" onsubmit="return confirm('Chequear todos los articulos? Puede tardar 1-2 minutos si tenes muchos links.')">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
        <button class="admin-btn admin-btn-primary">Re-chequear todo</button>
    </form>
</div>

<div class="admin-card" style="margin-bottom:1rem">
    <p style="margin:0 0 0.5rem 0">
        Monitoriamos los links <strong>externos</strong> dentro del contenido de tus articulos.
        Los links <code>/go/</code> (afiliados) se chequean en
        <a href="/admin/affiliate-links/health">Afiliados → Health check</a>.
    </p>
    <p class="admin-muted" style="margin:0;font-size:0.88rem">
        Cada URL se chequea con HEAD y fallback a GET si el servidor bloquea HEAD. Cache de 6h por URL —
        correr "Re-chequear todo" no sobrecarga destinos ya chequeados recientemente.
    </p>
</div>

<!-- Rotos -->
<div class="admin-card">
    <h2 style="margin-top:0">
        <span style="color:var(--a-danger)">●</span>
        Rotos <span class="admin-muted" style="font-weight:normal">(<?= count($broken) ?>)</span>
    </h2>
    <?php if (!$broken): ?>
        <p class="admin-muted" style="margin:0">Sin links rotos. 🎉</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr><th>URL</th><th>Status</th><th>Artículo</th><th>Visto</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                <?php foreach ($broken as $r) { $renderRow($r, true, true, false); } ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- Sospechosos (403/429) -->
<?php if ($suspicious): ?>
<div class="admin-card" style="margin-top:1rem">
    <h2 style="margin-top:0">
        <span style="color:var(--a-warning)">●</span>
        Sospechosos <span class="admin-muted" style="font-weight:normal">(<?= count($suspicious) ?>)</span>
    </h2>
    <p class="admin-muted" style="font-size:0.88rem;margin-top:0">
        Status 403/429: el vendor probablemente bloquea bots. El link puede estar OK para humanos.
        Verificá manualmente y "Marcar OK" si es falso positivo.
    </p>
    <table class="admin-table">
        <thead>
            <tr><th>URL</th><th>Status</th><th>Artículo</th><th>Visto</th><th>Acciones</th></tr>
        </thead>
        <tbody>
            <?php foreach ($suspicious as $r) { $renderRow($r, false, true, false); } ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<!-- Redirects (OK pero con URL final distinta) -->
<?php if ($redirected): ?>
<div class="admin-card" style="margin-top:1rem">
    <h2 style="margin-top:0">
        <span style="color:var(--a-success)">●</span>
        Con redirect <span class="admin-muted" style="font-weight:normal">(<?= count($redirected) ?>)</span>
    </h2>
    <p class="admin-muted" style="font-size:0.88rem;margin-top:0">
        Estos links funcionan pero redirigen a otra URL. Podés aplicar el redirect
        para actualizar el markdown y evitar el hop en cada click.
    </p>
    <table class="admin-table">
        <thead>
            <tr><th>URL</th><th>Status</th><th>Artículo</th><th>Visto</th><th>Acciones</th></tr>
        </thead>
        <tbody>
            <?php foreach ($redirected as $r) { $renderRow($r, true, false, false); } ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<!-- Silenciados -->
<?php if ($ignored): ?>
<div class="admin-card" style="margin-top:1rem">
    <h2 style="margin-top:0">
        <span class="admin-muted">●</span>
        Silenciados <span class="admin-muted" style="font-weight:normal">(<?= count($ignored) ?>)</span>
    </h2>
    <p class="admin-muted" style="font-size:0.88rem;margin-top:0">
        Marcados como OK manualmente (falsos positivos). Se siguen chequeando pero no rompen los contadores.
    </p>
    <table class="admin-table">
        <thead>
            <tr><th>URL</th><th>Status</th><th>Artículo</th><th>Visto</th><th>Acciones</th></tr>
        </thead>
        <tbody>
            <?php foreach ($ignored as $r) { $renderRow($r, false, false, true); } ?>
        </tbody>
    </table>
</div>
<?php endif; ?>
