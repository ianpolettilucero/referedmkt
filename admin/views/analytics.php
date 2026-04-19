<?php
/** @var \Admin\AdminView $view
 *  @var int   $days
 *  @var int   $total_clicks
 *  @var array $by_link
 *  @var array $by_article
 *  @var array $by_product
 *  @var array $by_day
 */
$view->layout('admin');

// Sparkline minimal inline SVG.
$maxDay = 0;
foreach ($by_day as $d) { $maxDay = max($maxDay, (int)$d['clicks']); }
?>
<div class="admin-page-header">
    <h1 class="admin-page-title">Analytics</h1>
    <form method="get" action="/admin/analytics" class="admin-inline-form">
        <label class="admin-muted">Rango:</label>
        <select name="days" onchange="this.form.submit()">
            <?php foreach ([7,14,30,60,90,180,365] as $opt): ?>
                <option value="<?= $opt ?>" <?= $days === $opt ? 'selected' : '' ?>><?= $opt ?> días</option>
            <?php endforeach; ?>
        </select>
    </form>
</div>

<div class="admin-stats">
    <div class="admin-stat">
        <div class="admin-stat-value"><?= (int)$total_clicks ?></div>
        <div class="admin-stat-label">Clicks en <?= (int)$days ?>d</div>
    </div>
    <div class="admin-stat">
        <div class="admin-stat-value"><?= count($by_day) ?></div>
        <div class="admin-stat-label">Días con clicks</div>
    </div>
</div>

<div class="admin-card" style="margin-top:1rem">
    <h2 style="margin:0 0 0.75rem;font-size:1.1rem">Clicks por día</h2>
    <?php if (!$by_day): ?>
        <p class="admin-muted">Sin datos en el rango.</p>
    <?php else: ?>
        <?php $w = 800; $h = 80; $count = count($by_day); $step = $count > 1 ? ($w / ($count - 1)) : 0; ?>
        <svg viewBox="0 0 <?= $w ?> <?= $h ?>" width="100%" style="max-width:800px;height:80px">
            <polyline fill="none" stroke="#2b6cb0" stroke-width="2"
                points="<?php
                    foreach ($by_day as $i => $d) {
                        $x = number_format($i * $step, 2, '.', '');
                        $y = $maxDay > 0 ? ($h - ((int)$d['clicks'] / $maxDay) * ($h - 4) - 2) : $h - 2;
                        echo "$x," . number_format($y, 2, '.', '') . ' ';
                    }
                ?>"/>
        </svg>
    <?php endif; ?>
</div>

<div class="admin-card" style="margin-top:1rem">
    <h2 style="margin:0 0 0.5rem;font-size:1.1rem">Afiliados más clickeados</h2>
    <?php if (!$by_link): ?>
        <p class="admin-muted">Sin datos.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead><tr><th>Afiliado</th><th>Red</th><th>Slug</th><th>Clicks</th></tr></thead>
            <tbody>
                <?php foreach ($by_link as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($r['network_name'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><code><?= htmlspecialchars($r['tracking_slug'], ENT_QUOTES, 'UTF-8') ?></code></td>
                        <td><?= (int)$r['clicks'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<div class="admin-card" style="margin-top:1rem">
    <h2 style="margin:0 0 0.5rem;font-size:1.1rem">Top artículos</h2>
    <?php if (!$by_article): ?>
        <p class="admin-muted">Sin datos.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead><tr><th>Título</th><th>Tipo</th><th>Vistas (total)</th><th>Clicks (rango)</th></tr></thead>
            <tbody>
                <?php foreach ($by_article as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['title'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($r['article_type'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= (int)$r['views_count'] ?></td>
                        <td><?= (int)$r['clicks'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<div class="admin-card" style="margin-top:1rem">
    <h2 style="margin:0 0 0.5rem;font-size:1.1rem">Top productos (via /producto/)</h2>
    <?php if (!$by_product): ?>
        <p class="admin-muted">Sin datos.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead><tr><th>Producto</th><th>Slug</th><th>Clicks (rango)</th></tr></thead>
            <tbody>
                <?php foreach ($by_product as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['name'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><code><?= htmlspecialchars($r['slug'], ENT_QUOTES, 'UTF-8') ?></code></td>
                        <td><?= (int)$r['clicks'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
