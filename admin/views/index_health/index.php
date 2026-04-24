<?php
/** @var \Admin\AdminView $view
 *  @var array  $grouped
 *  @var bool   $configured
 *  @var array  $site
 *  @var string $csrf_token
 */
$view->layout('admin');

$verdictBadge = static function (?string $v): array {
    switch ($v) {
        case 'PASS':    return ['indexada',       'admin-badge-success'];
        case 'PARTIAL': return ['parcial',        'admin-badge-warning'];
        case 'NEUTRAL': return ['no indexada',    'admin-badge-warning'];
        case 'FAIL':    return ['bloqueada',      'admin-badge-danger'];
        default:        return [$v ?: 'sin data', 'admin-badge-danger'];
    }
};

$timeAgo = static function (?string $ts): string {
    if (!$ts) { return '—'; }
    $diff = time() - strtotime($ts);
    if ($diff < 60)    { return 'hace ' . $diff . 's'; }
    if ($diff < 3600)  { return 'hace ' . (int)floor($diff/60) . 'm'; }
    if ($diff < 86400) { return 'hace ' . (int)floor($diff/3600) . 'h'; }
    return 'hace ' . (int)floor($diff/86400) . 'd';
};

$gscInspectUrl = static function (string $url) use ($site): string {
    return \Models\IndexStatus::gscInspectUrl((int)$site['id'], $url);
};

$renderRow = static function (array $r) use ($csrf_token, $verdictBadge, $timeAgo, $gscInspectUrl) {
    [$label, $cls] = $verdictBadge($r['verdict'] ?? null);
    ?>
    <tr>
        <td style="max-width:420px">
            <div style="display:flex;flex-direction:column;gap:0.2rem">
                <code style="font-size:0.78rem;word-break:break-all"><?= htmlspecialchars($r['url'], ENT_QUOTES, 'UTF-8') ?></code>
                <?php if (!empty($r['coverage_state'])): ?>
                    <div class="admin-muted" style="font-size:0.78rem">
                        <?= htmlspecialchars($r['coverage_state'], ENT_QUOTES, 'UTF-8') ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($r['google_canonical']) && $r['google_canonical'] !== $r['url']): ?>
                    <div class="admin-muted" style="font-size:0.78rem">
                        canonical de Google: <code><?= htmlspecialchars($r['google_canonical'], ENT_QUOTES, 'UTF-8') ?></code>
                    </div>
                <?php endif; ?>
                <?php if (!empty($r['error_message'])): ?>
                    <div style="color:var(--a-danger);font-size:0.78rem">⚠ <?= htmlspecialchars($r['error_message'], ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
            </div>
        </td>
        <td><span class="admin-badge <?= $cls ?>"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></span></td>
        <td class="admin-muted" style="font-size:0.8rem;white-space:nowrap">
            <?php if (!empty($r['last_crawl_time'])): ?>
                crawl <?= htmlspecialchars($timeAgo($r['last_crawl_time']), ENT_QUOTES, 'UTF-8') ?><br>
            <?php endif; ?>
            check <?= htmlspecialchars($timeAgo($r['last_checked_at']), ENT_QUOTES, 'UTF-8') ?>
        </td>
        <td class="admin-row-actions">
            <form method="post" action="/admin/index-health/check-one" class="admin-inline-form">
                <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                <input type="hidden" name="url" value="<?= htmlspecialchars($r['url'], ENT_QUOTES, 'UTF-8') ?>">
                <button class="admin-btn admin-btn-subtle" title="Re-chequear en GSC">⟳</button>
            </form>
            <a class="admin-btn admin-btn-primary" style="font-size:0.8rem"
               href="<?= htmlspecialchars($gscInspectUrl($r['url']), ENT_QUOTES, 'UTF-8') ?>"
               target="_blank" rel="noopener">
                Inspect en GSC ↗
            </a>
        </td>
    </tr>
    <?php
};
?>
<div class="admin-page-header">
    <h1 class="admin-page-title">Health de indexación</h1>
    <div style="display:flex;gap:0.5rem;align-items:center">
        <?php if ($configured): ?>
            <form method="post" action="/admin/index-health/ping-indexnow" style="margin:0">
                <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                <button class="admin-btn admin-btn-subtle" title="Notificar a Bing/Yandex de todas las URLs">Pingear IndexNow</button>
            </form>
            <form method="post" action="/admin/index-health/check-all" style="margin:0" onsubmit="return confirm('Chequear todas las URLs contra GSC? Puede tardar 1-5 minutos.')">
                <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                <button class="admin-btn admin-btn-primary">Chequear todas</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php if (!$configured): ?>
    <div class="admin-card" style="border-left:4px solid var(--a-warning);margin-bottom:1rem">
        <h2 style="margin-top:0">⚠ GSC no está configurado</h2>
        <p style="margin:0 0 0.8rem 0">
            Para chequear estado de indexación necesitás un service account de Google con acceso a tu property en
            Google Search Console. Andá a <a href="/admin/settings"><strong>Settings → Indexación</strong></a> y seguí los pasos.
        </p>
        <p class="admin-muted" style="margin:0;font-size:0.88rem">
            IndexNow (Bing/Yandex) funciona sin configuración adicional — solo pinguearlo cuando publicás contenido nuevo.
        </p>
    </div>
<?php else: ?>
    <div class="admin-card" style="margin-bottom:1rem">
        <p style="margin:0 0 0.5rem 0">
            <strong>GSC conectado ✓</strong> · Consultamos la URL Inspection API de Google Search Console
            y cacheamos los resultados 24h (quota: 2000 req/día por propiedad).
        </p>
        <p class="admin-muted" style="margin:0;font-size:0.88rem">
            El botón "Inspect en GSC ↗" abre Google Search Console con la URL prellenada — ahí podés clickear
            "Request Indexing" para pedir re-indexación manual (Google tarda horas a días).
        </p>
    </div>
<?php endif; ?>

<!-- No indexadas -->
<div class="admin-card">
    <h2 style="margin-top:0">
        <span style="color:var(--a-danger)">●</span>
        No indexadas <span class="admin-muted" style="font-weight:normal">(<?= count($grouped['not_indexed']) ?>)</span>
    </h2>
    <?php if (!$grouped['not_indexed']): ?>
        <p class="admin-muted" style="margin:0">
            <?= $configured ? 'Todas las URLs chequeadas están indexadas. 🎉' : 'Ejecutá "Chequear todas" para empezar.' ?>
        </p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr><th>URL</th><th>Estado</th><th>Visto</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                <?php foreach ($grouped['not_indexed'] as $r) { $renderRow($r); } ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- Con error de API -->
<?php if ($grouped['errors']): ?>
<div class="admin-card" style="margin-top:1rem">
    <h2 style="margin-top:0">
        <span style="color:var(--a-warning)">●</span>
        Con error de API <span class="admin-muted" style="font-weight:normal">(<?= count($grouped['errors']) ?>)</span>
    </h2>
    <p class="admin-muted" style="font-size:0.88rem;margin-top:0">
        URLs que no se pudieron consultar (cuota superada, JSON inválido, property incorrecta, etc).
        Revisá el <code>error_message</code> de cada una.
    </p>
    <table class="admin-table">
        <thead>
            <tr><th>URL</th><th>Estado</th><th>Visto</th><th>Acciones</th></tr>
        </thead>
        <tbody>
            <?php foreach ($grouped['errors'] as $r) { $renderRow($r); } ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<!-- Indexadas (OK) -->
<?php if ($grouped['indexed']): ?>
<div class="admin-card" style="margin-top:1rem">
    <h2 style="margin-top:0">
        <span style="color:var(--a-success)">●</span>
        Indexadas <span class="admin-muted" style="font-weight:normal">(<?= count($grouped['indexed']) ?>)</span>
    </h2>
    <details>
        <summary style="cursor:pointer;color:var(--a-text-muted);font-size:0.9rem">Ver lista</summary>
        <table class="admin-table" style="margin-top:0.75rem">
            <thead>
                <tr><th>URL</th><th>Estado</th><th>Visto</th><th>Acciones</th></tr>
            </thead>
            <tbody>
                <?php foreach ($grouped['indexed'] as $r) { $renderRow($r); } ?>
            </tbody>
        </table>
    </details>
</div>
<?php endif; ?>
