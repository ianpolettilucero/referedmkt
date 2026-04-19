<?php
/**
 * @var \Admin\AdminView $view
 * @var string $csrf_token
 * @var array  $flashes
 */
$view->layout('blank');
?>
<div class="admin-login-card">
    <h1>Admin · referedmkt</h1>
    <?= $view->partial('flash', ['flashes' => $flashes]) ?>
    <form method="post" action="/admin/login" class="admin-form">
        <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8') ?>">
        <div class="admin-field">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required autofocus autocomplete="username">
        </div>
        <div class="admin-field">
            <label for="password">Contraseña</label>
            <input type="password" name="password" id="password" required autocomplete="current-password">
        </div>
        <button type="submit" class="admin-btn admin-btn-primary">Ingresar</button>
    </form>
</div>
