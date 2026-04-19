<?php
namespace Models;

final class AffiliateLink extends Model
{
    protected const TABLE = 'affiliate_links';

    public static function findActiveBySlug(int $siteId, string $slug): ?array
    {
        return self::db()->fetch(
            "SELECT * FROM affiliate_links
             WHERE site_id = :site AND tracking_slug = :slug AND active = 1
             LIMIT 1",
            ['site' => $siteId, 'slug' => $slug]
        );
    }
}
