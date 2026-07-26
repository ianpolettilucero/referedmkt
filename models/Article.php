<?php
namespace Models;

use Core\Content;

final class Article extends Model
{
    protected const COLLECTION = 'articles';

    /** @return array<int, array<string, mixed>> */
    public static function recent(int $siteId, int $limit = 10, ?string $type = null): array
    {
        $rows = Content::publishedArticles();
        if ($type !== null) {
            $rows = array_values(array_filter($rows, fn($a) => $a['article_type'] === $type));
        }
        return array_slice($rows, 0, max(1, $limit));
    }

    /**
     * Un articulo publicado por slug. Los programados a futuro no cuentan.
     *
     * @return array<string, mixed>|null
     */
    public static function findPublished(int $siteId, string $slug): ?array
    {
        $a = Content::articles()[$slug] ?? null;
        if ($a === null || ($a['status'] ?? '') !== 'published') {
            return null;
        }
        if (!empty($a['published_at']) && $a['published_at'] > date('Y-m-d H:i:s')) {
            return null;
        }
        return $a;
    }

    /**
     * Articulos relacionados para el pie de una nota.
     *
     * Prioriza (en orden): misma categoria, mismo tipo, mas recientes.
     * Excluye el articulo actual para evitar auto-link.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function related(
        int $siteId,
        int $excludeId,
        ?int $categoryId,
        string $articleType,
        int $limit = 3
    ): array {
        $limit = max(1, min(12, $limit));

        $rows = [];
        foreach (Content::publishedArticles() as $a) {
            if ((int)$a['id'] === $excludeId) { continue; }
            // Score de afinidad: la categoria pesa mas que el tipo.
            $a['__score'] = ($categoryId && (int)($a['category_id'] ?? 0) === $categoryId ? 2 : 0)
                          + (($a['article_type'] ?? '') === $articleType ? 1 : 0);
            $rows[] = $a;
        }

        $rows = self::sort($rows, [['__score', 'desc'], ['published_at', 'desc']]);
        $rows = array_slice($rows, 0, $limit);

        foreach ($rows as &$r) { unset($r['__score']); }
        unset($r);

        return $rows;
    }

    public const SORTS = [
        'recent'  => 'Más recientes',
        'oldest'  => 'Más antiguos',
        'az'      => 'Título A-Z',
    ];

    /**
     * @param array{type?:?string, category_id?:?int, sort?:string} $filters
     * @return array{items: array<int, array<string, mixed>>, total: int, page: int, per_page: int}
     */
    public static function paginate(int $siteId, array $filters = [], int $page = 1, int $perPage = 20): array
    {
        $page = max(1, $page);
        $rows = Content::publishedArticles();

        if (!empty($filters['type'])) {
            $rows = array_filter($rows, fn($a) => $a['article_type'] === $filters['type']);
        }
        if (!empty($filters['category_id'])) {
            $cat = (int)$filters['category_id'];
            $rows = array_filter($rows, fn($a) => (int)($a['category_id'] ?? 0) === $cat);
        }
        $rows = array_values($rows);

        $rows = match ($filters['sort'] ?? 'recent') {
            'oldest' => self::sort($rows, [['published_at', 'asc']]),
            'az'     => self::sort($rows, [['title', 'asc']]),
            default  => self::sort($rows, [['published_at', 'desc']]),
        };

        return [
            'items'    => array_slice($rows, ($page - 1) * $perPage, $perPage),
            'total'    => count($rows),
            'page'     => $page,
            'per_page' => $perPage,
        ];
    }

    /**
     * Articulos firmados por un autor, mas recientes primero.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function byAuthor(int $siteId, int $authorId, int $limit = 50): array
    {
        $rows = array_values(array_filter(
            Content::publishedArticles(),
            fn($a) => (int)($a['author_id'] ?? 0) === $authorId
        ));
        return array_slice($rows, 0, $limit);
    }
}
