<?php
/** @var \Admin\AdminView $view
 *  @var array  $stats
 *  @var array  $bans
 *  @var array  $whitelist
 *  @var array  $events
 *  @var array  $top_attackers
 *  @var string $filter
 *  @var string $my_ip
 *  @var string $csrf_token
 */
$view->layout('admin');

$eventLabel = function ($t) {
    return match ($t) {
        'login_fail'      => 'Login fallido',
        'login_success'   => 'Login ok',
        'logout'          => 'Logout',
        'auto_ban'        => 'Auto-ban',
        'manual_ban'      => 'Ban manual',
        'unban'           => 'Desban',
        'whitelist_add'   => 'Whitelist +',
        'whitelist_remove'=> 'Whitelist -',
        'blocked_request' => 'Request bloqueado',
        'csrf_fail'       => 'CSRF fallido',
        'suspicious'      => 'Sospechoso',
        default           => $t,
    };
};
$eventBadge = function ($t) {
    return match ($t) {
        'login_success','whitelist_add','unban' => 'admin-badge-success',
        'login_fail','auto_ban','csrf_fail','blocked_request' => 'admin-badge-danger',
        'manual_ban','whitelist_remove','suspicious' => 'admin-badge-warning',
        default => '',
    };
};
?>
<div class="admin-page-header">
    <h1 class="admin-page-title">Seguridad</h1>
    <span class="admin-muted" style="font-size:0.85rem">
        Tu IP actual:
        <?= $view->partial('ip_cell', ['ip' => $my_ip]) ?>
    </span>
</div>

<div class="admin-stats">
    <div class="admin-stat">
        <div class="admin-stat-value" style="color:var(--a-danger)"><?= (int)$stats['active_bans'] ?></div>
        <div class="admin-stat-label">IPs baneadas (activas)</div>
    </div>
    <div class="admin-stat">
        <div class="admin-stat-value" style="color:var(--a-success)"><?= (int)$stats['whitelist_count'] ?></div>
        <div class="admin-stat-label">IPs en whitelist</div>
    </div>
    <div class="admin-stat">
        <div class="admin-stat-value"><?= (int)$stats['failed_logins_24h'] ?></div>
        <div class="admin-stat-label">Logins fallidos (24h)</div>
    </div>
    <div class="admin-stat">
        <div class="admin-stat-value"><?= (int)$stats['blocked_req_24h'] ?></div>
        <div class="admin-stat-label">Requests bloqueados (24h)</div>
    </div>
    <div class="admin-stat">
        <div class="admin-stat-value"><?= (int)$stats['csrf_fails_24h'] ?></div>
        <div class="admin-stat-label">CSRF fallidos (24h)</div>
    </div>
</div>

<!-- ============== BAN MANUAL ============== -->
<div class="admin-card" style="margin-top:1rem">
    <h2 style="margin:0 0 0.5rem;font-size:1.05rem">Banear IP manualmente</h2>
    <form method="post" action="/admin/security/ban" class="admin-form" style="display:grid;grid-template-columns:1fr 2fr 120px auto;gap:0.6rem;align-items:end">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
        <div class="admin-field">
            <label>IP (v4 o v6)</label>
            <input name="ip" placeholder="1.2.3.4" required>
        </div>
        <div class="admin-field">
            <label>Motivo</label>
            <input name="reason" placeholder="ej: intentos de SQLi en /productos">
        </div>
        <div class="admin-field">
            <label>Horas (0 = permanente)</label>
            <input name="hours" type="number" min="0" value="24">
        </div>
        <button type="submit" class="admin-btn admin-btn-danger">Banear</button>
    </form>
</div>

<!-- ============== IPS BANEADAS ============== -->
<div class="admin-card" style="margin-top:1rem">
    <h2 style="margin:0 0 0.5rem;font-size:1.05rem">IPs baneadas activas (<?= count($bans) ?>)</h2>
    <?php if (!$bans): ?>
        <p class="admin-muted">No hay bans activos.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>IP</th>
                    <th>Motivo</th>
                    <th>Tipo</th>
                    <th>Baneado</th>
                    <th>Expira</th>
                    <th>Intentos</th>
                    <th>Por</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bans as $b): ?>
                    <tr>
                        <td><?= $view->partial('ip_cell', ['ip' => $b['ip_address']]) ?></td>
                        <td><?= htmlspecialchars($b['reason'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <?php if ($b['auto_banned']): ?>
                                <span class="admin-badge admin-badge-warning">auto</span>
                            <?php else: ?>
                                <span class="admin-badge">manual</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($b['banned_at'])), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= $b['expires_at'] ? htmlspecialchars(date('d/m/Y H:i', strtotime($b['expires_at'])), ENT_QUOTES, 'UTF-8') : '<em>permanente</em>' ?></td>
                        <td style="font-variant-numeric:tabular-nums"><?= (int)$b['attempt_count'] ?></td>
                        <td><?= htmlspecialchars($b['banned_by_name'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <form method="post" action="/admin/security/unban" class="admin-inline-form">
                                <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="ip" value="<?= htmlspecialchars($b['ip_address'], ENT_QUOTES, 'UTF-8') ?>">
                                <button type="submit" class="admin-btn admin-btn-subtle" style="color:var(--a-success)">Desbanear</button>
                            </form>
                            <form method="post" action="/admin/security/whitelist/add" class="admin-inline-form" style="margin-left:0.2rem">
                                <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="ip" value="<?= htmlspecialchars($b['ip_address'], ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="note" value="Movida desde ban">
                                <button type="submit" class="admin-btn admin-btn-subtle">→ whitelist</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- ============== WHITELIST ============== -->
<div class="admin-card" style="margin-top:1rem">
    <h2 style="margin:0 0 0.5rem;font-size:1.05rem">IPs en whitelist (<?= count($whitelist) ?>)</h2>
    <p class="admin-muted" style="margin-top:0;font-size:0.85rem">
        Las IPs en esta lista jamás se banean, aunque fallen logins o disparen alertas.
        Típico uso: tu IP fija personal/oficina, Uptime Robot, health checkers externos.
    </p>
    <form method="post" action="/admin/security/whitelist/add" class="admin-form" style="display:grid;grid-template-columns:1fr 2fr auto;gap:0.6rem;align-items:end;margin-bottom:0.75rem">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
        <div class="admin-field">
            <label>IP</label>
            <input name="ip" placeholder="1.2.3.4" required>
        </div>
        <div class="admin-field">
            <label>Nota</label>
            <input name="note" placeholder="ej: mi casa, oficina, UptimeRobot">
        </div>
        <button type="submit" class="admin-btn admin-btn-primary">Agregar</button>
    </form>
    <?php if (!$whitelist): ?>
        <p class="admin-muted">Lista vacía. Agregá al menos tu propia IP para no autobanearte.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead><tr><th>IP</th><th>Nota</th><th>Agregada</th><th>Por</th><th>Acciones</th></tr></thead>
            <tbody>
                <?php foreach ($whitelist as $w): ?>
                    <tr>
                        <td>
                            <?= $view->partial('ip_cell', ['ip' => $w['ip_address']]) ?>
                            <?php if ($w['ip_address'] === $my_ip): ?>
                                <span class="admin-badge admin-badge-success">tu IP</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($w['note'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars(date('d/m/Y', strtotime($w['created_at'])), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($w['added_by_name'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                        <td>
                            <form method="post" action="/admin/security/whitelist/remove" class="admin-inline-form" onsubmit="return confirm('¿Remover IP de whitelist? Puede autobanearse si falla logins.')">
                                <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                                <input type="hidden" name="ip" value="<?= htmlspecialchars($w['ip_address'], ENT_QUOTES, 'UTF-8') ?>">
                                <button type="submit" class="admin-btn admin-btn-subtle" style="color:var(--a-danger)">Quitar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- ============== TOP ATTACKERS 7D ============== -->
<?php if ($top_attackers): ?>
<div class="admin-card" style="margin-top:1rem">
    <h2 style="margin:0 0 0.5rem;font-size:1.05rem">Top IPs con más intentos fallidos (7 días)</h2>
    <table class="admin-table">
        <thead><tr><th>IP</th><th>Fails</th><th>Último intento</th><th>Emails probados</th><th>Acciones</th></tr></thead>
        <tbody>
            <?php foreach ($top_attackers as $a): ?>
                <tr>
                    <td><?= $view->partial('ip_cell', ['ip' => $a['ip_address']]) ?></td>
                    <td style="font-variant-numeric:tabular-nums;color:var(--a-danger);font-weight:700"><?= (int)$a['fails'] ?></td>
                    <td><?= htmlspecialchars(date('d/m/Y H:i', strtotime($a['last_seen'])), ENT_QUOTES, 'UTF-8') ?></td>
                    <td style="font-size:0.82rem"><?= htmlspecialchars(mb_substr($a['emails_tried'] ?? '', 0, 120), ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <form method="post" action="/admin/security/ban" class="admin-inline-form">
                            <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="ip" value="<?= htmlspecialchars($a['ip_address'], ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="reason" value="Top attacker — fails recurrentes 7d">
                            <input type="hidden" name="hours" value="0">
                            <button type="submit" class="admin-btn admin-btn-subtle" style="color:var(--a-danger)">Ban permanente</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<!-- ============== EVENTS ============== -->
<div class="admin-card" style="margin-top:1rem">
    <div style="display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;margin-bottom:0.5rem">
        <h2 style="margin:0;font-size:1.05rem">Eventos de seguridad (últimos 200)</h2>
        <form method="get" action="/admin/security" style="display:flex;gap:0.5rem;align-items:center">
            <label class="admin-muted" style="font-size:0.85rem">Filtrar:</label>
            <select name="filter" onchange="this.form.submit()">
                <option value="">Todos</option>
                <?php foreach (['login_fail','login_success','auto_ban','manual_ban','unban','blocked_request','csrf_fail','whitelist_add','whitelist_remove','logout'] as $t): ?>
                    <option value="<?= $t ?>" <?= $filter === $t ? 'selected' : '' ?>><?= htmlspecialchars($eventLabel($t), ENT_QUOTES, 'UTF-8') ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>
    <?php if (!$events): ?>
        <p class="admin-muted">Sin eventos.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead><tr><th>Fecha</th><th>Tipo</th><th>IP</th><th>Usuario / Email</th><th>Path</th></tr></thead>
            <tbody>
                <?php foreach ($events as $e): ?>
                    <tr>
                        <td style="font-size:0.82rem;white-space:nowrap"><?= htmlspecialchars(date('d/m H:i:s', strtotime($e['created_at'])), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><span class="admin-badge <?= $eventBadge($e['event_type']) ?>"><?= htmlspecialchars($eventLabel($e['event_type']), ENT_QUOTES, 'UTF-8') ?></span></td>
                        <td>
                            <?php if (!empty($e['ip_address'])): ?>
                                <?= $view->partial('ip_cell', ['ip' => $e['ip_address']]) ?>
                            <?php else: ?>
                                <span class="admin-muted">—</span>
                            <?php endif; ?>
                        </td>
                        <td style="font-size:0.85rem">
                            <?php if (!empty($e['user_name'])): ?>
                                <strong><?= htmlspecialchars($e['user_name'], ENT_QUOTES, 'UTF-8') ?></strong>
                            <?php elseif (!empty($e['email'])): ?>
                                <?= htmlspecialchars($e['email'], ENT_QUOTES, 'UTF-8') ?>
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>
                        <td style="font-size:0.82rem;color:var(--a-text-muted)"><?= htmlspecialchars(mb_substr($e['path'] ?? '', 0, 60), ENT_QUOTES, 'UTF-8') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<!-- ============== GC ============== -->
<div class="admin-card" style="margin-top:1rem">
    <h3 style="margin-top:0;font-size:1rem">Mantenimiento</h3>
    <p class="admin-muted" style="margin-top:0;font-size:0.85rem">
        Limpia bans vencidos, login_attempts &gt; 24h, y security_events &gt; 90 días.
        Recomendado ejecutar semanal.
    </p>
    <form method="post" action="/admin/security/gc" class="admin-inline-form">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
        <button type="submit" class="admin-btn">Ejecutar cleanup</button>
    </form>
</div>
