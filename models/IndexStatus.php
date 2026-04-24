<?php
namespace Models;

use Core\Database;
use Core\GscInspector;

/**
 * Wrapper sobre la tabla index_status. No extiende Model porque es una
 * tabla de cache/aggregate, no un CRUD de entidad.
 */
final class IndexStatus
{
    private const RECHECK_HOURS = 24; // no re-chequeamos la misma URL antes de 24h

    /**
     * Lista todas las URLs publicas de un sitio (home, listados, articulos,
     * productos, categorias, autores) en formato absoluto. Ordenadas.
     *
     * @return array<int, string>
     */
    public static function publicUrls(int $siteId): array
    {
        $db = Database::instance();
        $site = $db->fetch('SELECT domain FROM sites WHERE id = :id LIMIT 1', ['id' => $siteId]);
        if (!$site) { return []; }
        $base = 'https://' . $site['domain'];

        $urls = [
            $base . '/',
            $base . '/productos',
            $base . '/guias',
            $base . '/resenas',
            $base . '/comparativas',
            $base . '/noticias',
        ];

        $arts = $db->fetchAll(
            "SELECT slug, article_type FROM articles
             WHERE site_id = :s AND status = 'published' AND published_at <= NOW()",
            ['s' => $siteId]
        );
        foreach ($arts as $a) {
            $urls[] = $base . self::articlePath((string)$a['article_type'], (string)$a['slug']);
        }

        $prods = $db->fetchAll('SELECT slug FROM products WHERE site_id = :s', ['s' => $siteId]);
        foreach ($prods as $p) { $urls[] = $base . '/producto/' . $p['slug']; }

        $cats = $db->fetchAll('SELECT slug FROM categories WHERE site_id = :s', ['s' => $siteId]);
        foreach ($cats as $c) { $urls[] = $base . '/productos/' . $c['slug']; }

        $auths = $db->fetchAll('SELECT slug FROM authors WHERE site_id = :s', ['s' => $siteId]);
        foreach ($auths as $au) { $urls[] = $base . '/autor/' . $au['slug']; }

        return array_values(array_unique($urls));
    }

    public static function articlePath(string $type, string $slug): string
    {
        return match ($type) {
            'review'     => '/resena/' . $slug,
            'comparison' => '/comparativa/' . $slug,
            'news'       => '/noticia/' . $slug,
            default      => '/guia/' . $slug,
        };
    }

    /**
     * Consulta GSC para una URL y guarda el resultado.
     * Si check reciente existe (<24h), skip a menos que $force.
     */
    public static function checkUrl(int $siteId, string $url, bool $force = false): array
    {
        if (!$force) {
            $recent = Database::instance()->fetchColumn(
                "SELECT 1 FROM index_status
                 WHERE site_id = :s AND url_hash = :h
                   AND last_checked_at >= (NOW() - INTERVAL " . self::RECHECK_HOURS . " HOUR)
                 LIMIT 1",
                ['s' => $siteId, 'h' => hash('sha256', $url)]
            );
            if ($recent) {
                return ['skipped' => true, 'url' => $url];
            }
        }

        $res = GscInspector::inspect($siteId, $url);
        self::upsert($siteId, $url, $res);
        return $res + ['url' => $url];
    }

    /**
     * Chequea todas las URLs del sitio. Respeta cache de 24h a menos que $force.
     * Usa un sleep chico entre requests para no pasarse del rate limit (600/min).
     *
     * @return array{checked:int, skipped:int, errors:int}
     */
    public static function checkAllForSite(int $siteId, bool $force = false): array
    {
        if (!GscInspector::isConfigured($siteId)) {
            return ['checked' => 0, 'skipped' => 0, 'errors' => 0, 'configured' => false];
        }
        $urls = self::publicUrls($siteId);

        // Sync: borrar del index_status las URLs que ya no existen (slugs eliminados)
        self::pruneRemovedUrls($siteId, $urls);

        $checked = $skipped = $errors = 0;
        foreach ($urls as $url) {
            $r = self::checkUrl($siteId, $url, $force);
            if (!empty($r['skipped'])) { $skipped++; continue; }
            if (empty($r['ok'])) { $errors++; continue; }
            $checked++;
            // 200ms entre requests → 300 req/min, margen de sobra vs 600/min quota
            usleep(200000);
        }
        return ['checked' => $checked, 'skipped' => $skipped, 'errors' => $errors, 'configured' => true];
    }

    /**
     * Rows para el panel admin. Agrupados por "problema" para el render.
     *
     * @return array<string, array<int, array<string, mixed>>>
     */
    public static function groupedForSite(int $siteId): array
    {
        $rows = Database::instance()->fetchAll(
            'SELECT * FROM index_status WHERE site_id = :s
             ORDER BY last_checked_at DESC',
            ['s' => $siteId]
        );
        $indexed = $notIndexed = $errors = $notChecked = [];
        foreach ($rows as $r) {
            if ($r['verdict'] === null && $r['error_message']) { $errors[] = $r; continue; }
            $v = (string)($r['verdict'] ?? '');
            if ($v === 'PASS')                             { $indexed[]    = $r; continue; }
            if ($v === 'PARTIAL' || $v === 'NEUTRAL'
                || $v === 'FAIL')                          { $notIndexed[] = $r; continue; }
            $notChecked[] = $r;
        }
        return [
            'indexed'     => $indexed,
            'not_indexed' => $notIndexed,
            'errors'      => $errors,
            'not_checked' => $notChecked,
        ];
    }

    /**
     * Count de no-indexadas para el badge del dashboard.
     */
    public static function notIndexedCount(int $siteId): int
    {
        try {
            return (int)Database::instance()->fetchColumn(
                "SELECT COUNT(*) FROM index_status
                 WHERE site_id = :s
                   AND verdict IS NOT NULL
                   AND verdict <> 'PASS'",
                ['s' => $siteId]
            );
        } catch (\Throwable $e) {
            return 0;
        }
    }

    /**
     * Construye la URL de GSC URL Inspection con la URL prellenada.
     * Abre en una tab nueva y el user solo tiene que clickear "Request indexing".
     */
    public static function gscInspectUrl(int $siteId, string $url): string
    {
        $property = (string)\Core\Settings::get($siteId, 'gsc_property_url', '');
        if ($property === '') {
            return 'https://search.google.com/search-console';
        }
        return 'https://search.google.com/search-console/inspect?resource_id='
            . rawurlencode($property)
            . '&id=' . rawurlencode($url);
    }

    // -----------------------------------------------------------------------

    private static function upsert(int $siteId, string $url, array $res): void
    {
        $hash = hash('sha256', $url);
        // Convertir ISO 8601 a MySQL DATETIME si viene
        $lastCrawl = null;
        if (!empty($res['last_crawl_time'])) {
            $ts = strtotime((string)$res['last_crawl_time']);
            if ($ts !== false) { $lastCrawl = date('Y-m-d H:i:s', $ts); }
        }
        Database::instance()->query(
            "INSERT INTO index_status
                (site_id, url, url_hash, verdict, coverage_state, indexing_state,
                 robots_txt_state, page_fetch_state, google_canonical, user_canonical,
                 last_crawl_time, error_message, last_checked_at)
             VALUES
                (:s, :url, :hash, :v, :cov, :ix, :rb, :pf, :gc, :uc, :lc, :err, CURRENT_TIMESTAMP)
             ON DUPLICATE KEY UPDATE
                url              = VALUES(url),
                verdict          = VALUES(verdict),
                coverage_state   = VALUES(coverage_state),
                indexing_state   = VALUES(indexing_state),
                robots_txt_state = VALUES(robots_txt_state),
                page_fetch_state = VALUES(page_fetch_state),
                google_canonical = VALUES(google_canonical),
                user_canonical   = VALUES(user_canonical),
                last_crawl_time  = VALUES(last_crawl_time),
                error_message    = VALUES(error_message),
                last_checked_at  = CURRENT_TIMESTAMP",
            [
                's'    => $siteId,
                'url'  => mb_substr($url, 0, 2048),
                'hash' => $hash,
                'v'    => $res['verdict'] ?? null,
                'cov'  => $res['coverage_state'] ?? null,
                'ix'   => $res['indexing_state'] ?? null,
                'rb'   => $res['robots_txt_state'] ?? null,
                'pf'   => $res['page_fetch_state'] ?? null,
                'gc'   => $res['google_canonical'] ?? null,
                'uc'   => $res['user_canonical'] ?? null,
                'lc'   => $lastCrawl,
                'err'  => isset($res['error']) ? mb_substr((string)$res['error'], 0, 500) : null,
            ]
        );
    }

    /**
     * @param array<int, string> $currentUrls
     */
    private static function pruneRemovedUrls(int $siteId, array $currentUrls): void
    {
        if (!$currentUrls) {
            Database::instance()->query('DELETE FROM index_status WHERE site_id = :s', ['s' => $siteId]);
            return;
        }
        $hashes = array_map(static fn($u) => hash('sha256', $u), $currentUrls);
        $in = implode(',', array_map(static fn($h) => "'" . $h . "'", $hashes));
        Database::instance()->query(
            "DELETE FROM index_status
             WHERE site_id = :s AND url_hash NOT IN ($in)",
            ['s' => $siteId]
        );
    }
}
