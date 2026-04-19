<?php /** @var array $flashes */ ?>
<?php if ($flashes): ?>
    <div class="admin-flashes">
        <?php foreach ($flashes as $f): ?>
            <div class="admin-flash admin-flash-<?= htmlspecialchars($f['type'], ENT_QUOTES, 'UTF-8') ?>">
                <?= htmlspecialchars($f['message'], ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
