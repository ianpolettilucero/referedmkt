<?php
/**
 * @var \Core\View $view
 * @var string     $message
 */
$view->layout('default');
?>
<section class="error-page">
    <h1>404</h1>
    <p><?= e($message ?? 'No encontrado') ?></p>
    <p><a href="/">Volver al inicio</a></p>
</section>
