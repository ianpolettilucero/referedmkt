<?php
namespace Models;

use Core\Content;

final class AffiliateLink extends Model
{
    /**
     * @return array<string, mixed>|null
     */
    public static function findActiveBySlug(int $siteId, string $slug): ?array
    {
        $row = Content::affiliateLinks()[$slug] ?? null;
        if ($row === null || empty($row['active'])) {
            return null;
        }
        return $row;
    }
}
