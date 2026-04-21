<?php
/** @var \Admin\AdminView $view
 *  @var array $results
 */
$view->layout('admin');

$statusClass = static function (?int $s): string {
    if ($s === null)                  { return 'admin-badge-danger'; }
    if ($s >= 200 && $s < 300)        { return 'admin-badge-success'; }
    if ($s >= 300 && $s < 400)        { return 'admin-badge-warning'; }
    return 'admin-badge-danger';
};

$msClass = static function (?int $ms): string {
    if ($ms === null)  { return ''; }
    if ($ms < 500)     { return 'color:var(--a-success)'; }
    if ($ms < 1500)    { return 'color:var(--a-warning,#b45309)'; }
    return 'color:var(--a-danger)';
};

$okCount = 0;
$failCount = 0;
foreach ($results as $r) {
    $s = $r['status'] ?? null;
    if ($s !== null && $s >= 200 && $s < 400) { $okCount++; } else { $failCount++; }
}
?>
<div class="admin-page-header">
    <h1 class="admin-page-title">Health check de afiliados</h1>
    <a class="admin-btn admin-btn-subtle" href="/admin/affiliate-links">← Volver</a>
</div>

<div class="admin-card" style="margin-bottom:1rem">
    <p style="margin:0 0 0.5rem 0">
        Probamos cada URL destino con un HEAD request (timeout 5s, sigue hasta 3 redirects).
        Útil para detectar links rotos, afiliados desactivados por el vendor o redirects inesperados.
    </p>
    <p style="margin:0;font-size:0.9rem">
        <strong style="color:var(--a-success)"><?= (int)$okCount ?> OK</strong>
        ·
        <strong style="color:var(--a-danger)"><?= (int)$failCount ?> con problemas</strong>
        ·
        <a href="/admin/affiliate-links/health" class="admin-btn admin-btn-subtle" style="margin-left:0.5rem">Re-correr</a>
    </p>
</div>

<?php if (!$results): ?>
    <div class="admin-card admin-empty">Sin afiliados cargados en este sitio.</div>
<?php else: ?>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Red</th>
                <th>Activo</th>
                <th>Status</th>
                <th>Tiempo</th>
                <th>URL original → final</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $r): ?>
                <?php
                    $status    = $r['status'] ?? null;
                    $ms        = $r['ms'] ?? null;
                    $finalUrl  = $r['final_url'] ?? null;
                    $redirected = $finalUrl && rtrim($finalUrl, '/') !== rtrim((string)$r['destination_url'], '/');
                    $err       = $r['error'] ?? null;
                ?>
                <tr>
                    <td><strong><?= htmlspecialchars($r['name'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                    <td><?= htmlspecialchars($r['network_name'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <?php if ($r['active']): ?>
                            <span class="admin-badge admin-badge-success">activo</span>
                        <?php else: ?>
                            <span class="admin-badge">pausado</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($status !== null): ?>
                            <span class="admin-badge <?= $statusClass($status) ?>"><?= (int)$status ?></span>
                        <?php else: ?>
                            <span class="admin-badge admin-badge-danger">ERR</span>
                        <?php endif; ?>
                    </td>
                    <td style="<?= $msClass($ms) ?>">
                        <?= $ms !== null ? (int)$ms . ' ms' : '—' ?>
                    </td>
                    <td style="font-size:0.8rem;max-width:420px">
                        <code style="word-break:break-all"><?= htmlspecialchars($r['destination_url'], ENT_QUOTES, 'UTF-8') ?></code>
                        <?php if ($redirected): ?>
                            <div class="admin-muted" style="margin-top:0.2rem">
                                ↳ redirect a <code style="word-break:break-all"><?= htmlspecialchars($finalUrl, ENT_QUOTES, 'UTF-8') ?></code>
                            </div>
                        <?php endif; ?>
                        <?php if ($err): ?>
                            <div style="color:var(--a-danger);margin-top:0.2rem;font-weight:600">
                                <?= htmlspecialchars($err, ENT_QUOTES, 'UTF-8') ?>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
