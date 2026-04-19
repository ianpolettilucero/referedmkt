<?php /** @var array $article */ ?>
<article class="article-card">
    <a class="article-card-link" href="<?= e(article_url($article)) ?>">
        <?php if (!empty($article['featured_image'])): ?>
            <img class="article-card-img" src="<?= e($article['featured_image']) ?>" alt="<?= e($article['title']) ?>" loading="lazy">
        <?php endif; ?>
        <h3 class="article-card-title"><?= e($article['title']) ?></h3>
        <?php if (!empty($article['excerpt'])): ?>
            <p class="article-card-excerpt"><?= e(excerpt($article['excerpt'], 180)) ?></p>
        <?php endif; ?>
        <?php if (!empty($article['published_at'])): ?>
            <time datetime="<?= e(date('c', strtotime($article['published_at']))) ?>">
                <?= e(date('d/m/Y', strtotime($article['published_at']))) ?>
            </time>
        <?php endif; ?>
    </a>
</article>
