<?php /** @var \Core\Site $site */ ?>
<?php if ($site->affiliateDisclosureText): ?>
<aside class="affiliate-disclosure" role="note">
    <div class="container">
        <small><?= e($site->affiliateDisclosureText) ?></small>
    </div>
</aside>
<?php endif; ?>
