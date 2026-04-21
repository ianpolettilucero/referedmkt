<?php
/**
 * @var string $ip IP a mostrar
 * @var bool $show_full (opcional) si true, muestra completa por default (default: false)
 *
 * Muestra una IP enmascarada ("190.***.***.67") con un toggle que revela la
 * version completa on-click. Mitiga exposicion accidental en screenshots o
 * screens compartidas.
 */
$show_full = $show_full ?? false;
if (empty($ip)) {
    echo '<span class="admin-muted">—</span>';
    return;
}
$masked = \Core\Security::maskIp($ip);
$full = htmlspecialchars($ip, ENT_QUOTES, 'UTF-8');
?>
<span class="ip-masked" data-ip-full="<?= $full ?>">
    <code class="ip-masked-value"><?= $show_full ? $full : htmlspecialchars($masked, ENT_QUOTES, 'UTF-8') ?></code>
    <button type="button" class="ip-masked-toggle" title="<?= $show_full ? 'Ocultar' : 'Revelar IP completa' ?>" aria-label="toggle IP">
        <?= $show_full ? '🙈' : '👁' ?>
    </button>
</span>
