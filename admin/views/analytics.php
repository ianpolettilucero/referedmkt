<?php
/** @var \Admin\AdminView $view
 *  @var int   $days
 *  @var int   $total_clicks
 *  @var int   $prev_clicks
 *  @var int|null $delta
 *  @var int   $total_views_alltime
 *  @var int   $active_links
 *  @var array $by_link
 *  @var array $by_article
 *  @var array $by_product
 *  @var array $by_day
 *  @var array $by_country
 */
$view->layout('admin');

// Helper: label humano del rango
$rangeLabel = $days === 1 ? 'último día'
    : ($days === 7 ? 'últimos 7 días'
    : ($days === 14 ? 'últimas 2 semanas'
    : ($days === 30 ? 'últimos 30 días'
    : ($days === 60 ? 'últimos 2 meses'
    : ($days === 90 ? 'últimos 3 meses'
    : ($days === 180 ? 'últimos 6 meses'
    : 'último año'))))));

// Sparkline inline SVG
$maxDay = 0;
foreach ($by_day as $d) { $maxDay = max($maxDay, (int)$d['clicks']); }

// Nombres de paises mas comunes en LATAM (fallback a codigo ISO)
$countryNames = [
    'AR' => 'Argentina', 'MX' => 'México', 'BR' => 'Brasil', 'CO' => 'Colombia',
    'CL' => 'Chile', 'PE' => 'Perú', 'UY' => 'Uruguay', 'PY' => 'Paraguay',
    'EC' => 'Ecuador', 'VE' => 'Venezuela', 'BO' => 'Bolivia', 'DO' => 'R. Dominicana',
    'US' => 'EE.UU.', 'ES' => 'España', 'CR' => 'Costa Rica', 'PA' => 'Panamá',
    'GT' => 'Guatemala', 'SV' => 'El Salvador', 'HN' => 'Honduras', 'NI' => 'Nicaragua',
    'CU' => 'Cuba', 'PR' => 'Puerto Rico',
];
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
        <div class="admin-stat-label">Clicks (<?= htmlspecialchars($rangeLabel, ENT_QUOTES, 'UTF-8') ?>)</div>
        <?php if ($delta !== null): ?>
            <?php $color = $delta > 0 ? 'var(--a-success)' : ($delta < 0 ? 'var(--a-danger)' : 'var(--a-text-muted)'); ?>
            <div style="color:<?= $color ?>;font-size:0.78rem;font-weight:600;margin-top:0.35rem">
                <?= $delta > 0 ? '▲ +' . $delta . '%' : ($delta < 0 ? '▼ ' . $delta . '%' : '→ sin cambio') ?>
                <span class="admin-muted" style="font-weight:500">vs. período anterior (<?= (int)$prev_clicks ?>)</span>
            </div>
        <?php elseif ($prev_clicks === 0 && $total_clicks > 0): ?>
            <div style="color:var(--a-success);font-size:0.78rem;font-weight:600;margin-top:0.35rem">
                ▲ nuevo <span class="admin-muted" style="font-weight:500">vs. 0 antes</span>
            </div>
        <?php endif; ?>
    </div>
    <div class="admin-stat">
        <div class="admin-stat-value"><?= count($by_day) ?></div>
        <div class="admin-stat-label">Días con actividad (de <?= (int)$days ?>)</div>
    </div>
    <div class="admin-stat">
        <div class="admin-stat-value"><?= (int)$total_views_alltime ?></div>
        <div class="admin-stat-label">Vistas totales de artículos (all-time)</div>
    </div>
    <div class="admin-stat">
        <div class="admin-stat-value"><?= (int)$active_links ?></div>
        <div class="admin-stat-label">Afiliados activos</div>
    </div>
</div>

<div class="admin-card" style="margin-top:1rem">
    <h2 style="margin:0 0 0.75rem;font-size:1.1rem">Clicks por día (<?= htmlspecialchars($rangeLabel, ENT_QUOTES, 'UTF-8') ?>)</h2>
    <?php if (!$by_day): ?>
        <p class="admin-muted">Sin datos en el rango. Los clicks a /go/{slug} aparecen acá.</p>
    <?php else: ?>
        <?php $w = 800; $h = 80; $count = count($by_day); $step = $count > 1 ? ($w / ($count - 1)) : 0; ?>
        <svg viewBox="0 0 <?= $w ?> <?= $h ?>" width="100%" style="max-width:800px;height:80px">
            <polyline fill="none" stroke="var(--a-primary)" stroke-width="2"
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
    <p class="admin-muted" style="margin:0 0 0.5rem;font-size:0.85rem">
        Clicks a cada URL <code>/go/{slug}</code> en el período seleccionado.
    </p>
    <?php if (!$by_link): ?>
        <p class="admin-muted">Sin datos.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Afiliado</th>
                    <th>Red</th>
                    <th>Slug (<code>/go/…</code>)</th>
                    <th style="text-align:right">Clicks</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($by_link as $r): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($r['name'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                        <td><?= htmlspecialchars($r['network_name'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
                        <td><code><?= htmlspecialchars($r['tracking_slug'], ENT_QUOTES, 'UTF-8') ?></code></td>
                        <td style="text-align:right;font-variant-numeric:tabular-nums"><?= (int)$r['clicks'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<div class="admin-card" style="margin-top:1rem">
    <h2 style="margin:0 0 0.5rem;font-size:1.1rem">Top artículos</h2>
    <p class="admin-muted" style="margin:0 0 0.5rem;font-size:0.85rem">
        CTR = clicks a afiliados / vistas totales del artículo. Un buen CTR es &gt;2%.
    </p>
    <?php if (!$by_article): ?>
        <p class="admin-muted">Sin datos.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Tipo</th>
                    <th style="text-align:right">Vistas (all-time)</th>
                    <th style="text-align:right">Clicks (<?= (int)$days ?>d)</th>
                    <th style="text-align:right">CTR</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($by_article as $r): ?>
                    <?php
                        $v = (int)$r['views_count'];
                        $c = (int)$r['clicks'];
                        $ctr = $v > 0 ? round($c * 100 / $v, 1) : null;
                        $ctrColor = $ctr === null ? 'var(--a-text-subtle)'
                            : ($ctr >= 3 ? 'var(--a-success)'
                            : ($ctr >= 1 ? 'var(--a-text)' : 'var(--a-text-muted)'));
                    ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($r['title'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                        <td><?= htmlspecialchars($r['article_type'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td style="text-align:right;font-variant-numeric:tabular-nums"><?= $v ?></td>
                        <td style="text-align:right;font-variant-numeric:tabular-nums"><?= $c ?></td>
                        <td style="text-align:right;font-variant-numeric:tabular-nums;color:<?= $ctrColor ?>">
                            <?= $ctr === null ? '—' : $ctr . '%' ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<div class="admin-card" style="margin-top:1rem">
    <h2 style="margin:0 0 0.5rem;font-size:1.1rem">Top productos</h2>
    <p class="admin-muted" style="margin:0 0 0.5rem;font-size:0.85rem">
        Clicks a afiliado disparados desde la página del producto (<code>/producto/…</code>).
    </p>
    <?php if (!$by_product): ?>
        <p class="admin-muted">Sin datos.</p>
    <?php else: ?>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Slug</th>
                    <th style="text-align:right">Clicks (<?= (int)$days ?>d)</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($by_product as $r): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($r['name'], ENT_QUOTES, 'UTF-8') ?></strong></td>
                        <td><code><?= htmlspecialchars($r['slug'], ENT_QUOTES, 'UTF-8') ?></code></td>
                        <td style="text-align:right;font-variant-numeric:tabular-nums"><?= (int)$r['clicks'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php if ($by_country): ?>
<div class="admin-card" style="margin-top:1rem">
    <h2 style="margin:0 0 0.5rem;font-size:1.1rem">Países (de los clicks)</h2>
    <p class="admin-muted" style="margin:0 0 0.5rem;font-size:0.85rem">
        País del usuario al hacer click. Requiere header <code>CF-IPCOUNTRY</code> (Cloudflare).
    </p>
    <table class="admin-table">
        <thead>
            <tr>
                <th>País</th>
                <th>Código</th>
                <th style="text-align:right">Clicks</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($by_country as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($countryNames[$r['country']] ?? $r['country'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><code><?= htmlspecialchars($r['country'], ENT_QUOTES, 'UTF-8') ?></code></td>
                    <td style="text-align:right;font-variant-numeric:tabular-nums"><?= (int)$r['clicks'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<div class="admin-card" style="margin-top:1rem">
    <h2 style="margin:0 0 0.5rem;font-size:1.1rem">¿Qué métricas NO ves acá?</h2>
    <p class="admin-muted" style="font-size:0.9rem">
        Este panel trackea <strong>clicks a afiliados</strong> (nuestra propia infraestructura, no bloqueable por ad blockers).
        Para tráfico general, fuentes, device/browser, bounce rate, tiempo en sitio, etc., usá
        <a href="https://analytics.google.com" target="_blank" rel="noopener">Google Analytics 4</a>
        (ya configurado en tu sitio). Para queries de búsqueda y posiciones en Google SERP,
        <a href="https://search.google.com/search-console" target="_blank" rel="noopener">Google Search Console</a>.
    </p>
</div>
