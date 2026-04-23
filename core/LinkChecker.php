<?php
namespace Core;

use Models\Article;

/**
 * Health check de links EXTERNOS dentro del contenido de articulos.
 *
 * Excluye:
 *   - Links internos (mismo dominio)
 *   - Links afiliados /go/ (ya estan en /admin/affiliate-links/health)
 *   - mailto:, tel:, javascript:, anchors (#)
 *
 * Estrategia de chequeo:
 *   - HEAD request (rapido). Si responde 4xx/5xx probamos GET (muchos
 *     vendors bloquean HEAD solamente). Ambos siguen hasta 3 redirects.
 *   - User-agent "real" para evitar falsos 403 de bot-blockers.
 *   - Timeout 5s por URL.
 *   - Paralelismo via curl_multi (batches de 10).
 *
 * Interpretacion:
 *   - 200-399 → OK
 *   - 404, 410, 500+, 502, 503, 504 → roto
 *   - 403, 429 → sospechoso (no lo marcamos como roto sin mas; puede ser
 *     bot-blocker). Admin puede re-chequear manual si lo duda.
 *   - null → no se pudo conectar (DNS, SSL, timeout)
 */
final class LinkChecker
{
    private const PROBE_TIMEOUT       = 5;
    private const PROBE_CONNECT_TO    = 3;
    private const PROBE_MAX_REDIRECTS = 3;
    private const BATCH_SIZE          = 10;
    private const USER_AGENT          = 'Mozilla/5.0 (compatible; referedmkt-link-checker/1.0; +https://capacero.online)';
    private const RECHECK_MIN_HOURS   = 6;

    /**
     * Extrae URLs externas (http/https) del HTML renderizado de un articulo.
     * Filtra:
     *   - Links al mismo dominio
     *   - /go/* (afiliados)
     *   - URLs sin scheme http/https (mailto:, tel:, anchors, etc.)
     *   - Duplicados (misma URL solo una vez)
     *
     * @return array<int, string>
     */
    public static function extractExternalUrls(string $html, string $siteDomain): array
    {
        if ($html === '') { return []; }
        $siteDomain = strtolower(trim($siteDomain));

        // DOMDocument con supresion de warnings por HTML mal formado en el
        // contenido (entity errors son comunes y no queremos que rompa).
        libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        // UTF-8 hack: prefix con meta charset para que DOMDocument no rompa acentos.
        $wrapped = '<?xml encoding="UTF-8"><div>' . $html . '</div>';
        $dom->loadHTML($wrapped, LIBXML_NOWARNING | LIBXML_NOERROR | LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();

        $urls = [];
        foreach ($dom->getElementsByTagName('a') as $a) {
            /** @var \DOMElement $a */
            $href = trim($a->getAttribute('href'));
            if ($href === '') { continue; }

            // Solo http/https absolutos
            if (!preg_match('#^https?://#i', $href)) { continue; }

            $host = strtolower((string)parse_url($href, PHP_URL_HOST));
            if ($host === '') { continue; }

            // Skip mismo dominio (normalizando www.)
            $normalize = static fn(string $h): string => preg_replace('/^www\./i', '', $h) ?? $h;
            if ($normalize($host) === $normalize($siteDomain)) {
                continue;
            }

            // Skip /go/ en caso de links afiliados absolutos (capacero.online/go/..)
            $path = (string)parse_url($href, PHP_URL_PATH);
            if (strpos($path, '/go/') === 0) { continue; }

            $urls[$href] = true;
        }
        return array_keys($urls);
    }

    /**
     * Chequea todos los links externos de un articulo, upserts en article_links.
     * Si $force es false, URLs chequeadas hace menos de RECHECK_MIN_HOURS se
     * skipean (reusamos status guardado).
     *
     * @return array{checked:int, ok:int, broken:int, suspicious:int, total:int}
     */
    public static function checkArticle(int $articleId, bool $force = false): array
    {
        $article = Database::instance()->fetch(
            'SELECT id, site_id, content FROM articles WHERE id = :id LIMIT 1',
            ['id' => $articleId]
        );
        if (!$article) {
            return ['checked' => 0, 'ok' => 0, 'broken' => 0, 'suspicious' => 0, 'total' => 0];
        }

        $site = Database::instance()->fetch(
            'SELECT domain FROM sites WHERE id = :id LIMIT 1',
            ['id' => $article['site_id']]
        );
        $domain = $site['domain'] ?? '';

        $html = Markdown::toHtml((string)$article['content']);
        $urls = self::extractExternalUrls($html, $domain);

        // Borrar de article_links las URLs que ya no estan en el articulo
        // (link eliminado/editado). Evita basura acumulandose.
        self::pruneRemovedLinks($articleId, $urls);

        if (!$urls) {
            return ['checked' => 0, 'ok' => 0, 'broken' => 0, 'suspicious' => 0, 'total' => 0];
        }

        // Separar URLs que ya tienen un check reciente (skip) de las que hay que chequear
        $toCheck = $urls;
        if (!$force) {
            $existing = Database::instance()->fetchAll(
                "SELECT url FROM article_links
                 WHERE article_id = :a
                   AND last_checked_at >= (NOW() - INTERVAL " . self::RECHECK_MIN_HOURS . " HOUR)",
                ['a' => $articleId]
            );
            $recent = array_column($existing, 'url');
            $toCheck = array_values(array_diff($urls, $recent));
        }

        foreach (array_chunk($toCheck, self::BATCH_SIZE) as $batch) {
            $results = self::probeBatch($batch);
            foreach ($results as $url => $r) {
                self::upsertResult($articleId, $url, $r);
            }
        }

        // Resumen devuelto refleja el estado ACTUAL de todos los links del articulo
        $rows = Database::instance()->fetchAll(
            'SELECT status_code, ignored_at FROM article_links WHERE article_id = :a',
            ['a' => $articleId]
        );
        $ok = $broken = $suspicious = 0;
        foreach ($rows as $row) {
            if ($row['ignored_at'] !== null) { $ok++; continue; }
            $s = $row['status_code'];
            if ($s === null) { $broken++; }
            elseif ((int)$s >= 200 && (int)$s < 400) { $ok++; }
            elseif ((int)$s === 403 || (int)$s === 429) { $suspicious++; }
            else { $broken++; }
        }
        return [
            'checked'    => count($toCheck),
            'ok'         => $ok,
            'broken'     => $broken,
            'suspicious' => $suspicious,
            'total'      => count($rows),
        ];
    }

    /**
     * Chequea todos los articulos publicados de un sitio. Skipea URLs
     * chequeadas hace menos de RECHECK_MIN_HOURS (default 6).
     *
     * @return array{articles_processed:int, urls_checked:int, broken:int}
     */
    public static function checkAllForSite(int $siteId): array
    {
        $articles = Database::instance()->fetchAll(
            "SELECT id FROM articles WHERE site_id = :s AND status = 'published' AND published_at <= NOW()",
            ['s' => $siteId]
        );
        $urlsChecked = 0;
        $brokenTotal = 0;
        foreach ($articles as $a) {
            $r = self::checkArticle((int)$a['id'], false);
            $urlsChecked += $r['checked'];
            $brokenTotal += $r['broken'];
        }
        return [
            'articles_processed' => count($articles),
            'urls_checked'       => $urlsChecked,
            'broken'             => $brokenTotal,
        ];
    }

    /**
     * Count de links rotos (no ignorados) para un sitio. Usado por badge de admin.
     * Defensivo: si la tabla no existe retorna 0.
     */
    public static function brokenCountForSite(int $siteId): int
    {
        try {
            return (int)Database::instance()->fetchColumn(
                "SELECT COUNT(*) FROM article_links al
                 JOIN articles a ON a.id = al.article_id
                 WHERE a.site_id = :s
                   AND al.ignored_at IS NULL
                   AND (al.status_code IS NULL
                        OR al.status_code = 404
                        OR al.status_code = 410
                        OR al.status_code >= 500)",
                ['s' => $siteId]
            );
        } catch (\Throwable $e) {
            return 0;
        }
    }

    /**
     * Links problematicos de un articulo puntual. Para mostrar warning en edit form.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function brokenForArticle(int $articleId): array
    {
        try {
            return Database::instance()->fetchAll(
                "SELECT * FROM article_links
                 WHERE article_id = :a
                   AND ignored_at IS NULL
                   AND (status_code IS NULL
                        OR status_code = 404
                        OR status_code = 410
                        OR status_code >= 500)
                 ORDER BY first_seen_broken_at ASC",
                ['a' => $articleId]
            );
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Aplicar redirect: si el link tiene final_url distinta (301/302 → nueva URL
     * que responde OK), reescribimos el markdown del articulo reemplazando la
     * URL vieja por la nueva.
     *
     * Retorna int = numero de ocurrencias reemplazadas, o false si no se pudo.
     *
     * @return int|false
     */
    public static function applyRedirect(int $linkId)
    {
        $link = Database::instance()->fetch(
            'SELECT * FROM article_links WHERE id = :id LIMIT 1',
            ['id' => $linkId]
        );
        if (!$link || empty($link['final_url']) || $link['final_url'] === $link['url']) {
            return false;
        }
        // Solo aplicar si el final_url respondio OK
        $status = (int)($link['status_code'] ?? 0);
        if ($status < 200 || $status >= 400) { return false; }

        $article = Database::instance()->fetch(
            'SELECT id, content FROM articles WHERE id = :id LIMIT 1',
            ['id' => $link['article_id']]
        );
        if (!$article) { return false; }

        $content = (string)$article['content'];
        $count = 0;
        $newContent = str_replace($link['url'], $link['final_url'], $content, $count);
        if ($count === 0) {
            // La URL ya no esta en el contenido (probablemente la editaron manualmente).
            // Limpiamos el registro para que desaparezca del listado.
            Database::instance()->query('DELETE FROM article_links WHERE id = :id', ['id' => $linkId]);
            return 0;
        }

        Database::instance()->query(
            'UPDATE articles SET content = :c, updated_at = CURRENT_TIMESTAMP WHERE id = :id',
            ['c' => $newContent, 'id' => $article['id']]
        );

        // Borrar el registro viejo del link; el proximo check-all descubrira el
        // nuevo URL si sigue siendo relevante.
        Database::instance()->query('DELETE FROM article_links WHERE id = :id', ['id' => $linkId]);
        return $count;
    }

    public static function ignore(int $linkId): void
    {
        Database::instance()->query(
            'UPDATE article_links SET ignored_at = CURRENT_TIMESTAMP WHERE id = :id',
            ['id' => $linkId]
        );
    }

    public static function unignore(int $linkId): void
    {
        Database::instance()->query(
            'UPDATE article_links SET ignored_at = NULL WHERE id = :id',
            ['id' => $linkId]
        );
    }

    // -----------------------------------------------------------------------
    // Internals
    // -----------------------------------------------------------------------

    /**
     * Chequea un batch de URLs en paralelo con curl_multi. HEAD primero,
     * fallback a GET si HEAD retorna 4xx/5xx.
     *
     * @param array<int, string> $urls
     * @return array<string, array{status:int|null, final_url:string|null, error:string|null}>
     */
    private static function probeBatch(array $urls): array
    {
        $out = [];
        if (!$urls) { return $out; }
        if (!function_exists('curl_multi_init')) {
            foreach ($urls as $u) {
                $out[$u] = ['status' => null, 'final_url' => null, 'error' => 'cURL no disponible'];
            }
            return $out;
        }

        // Pasada 1: HEAD
        $headResults = self::doMulti($urls, true);

        // Pasada 2: GET para los que dieron 4xx/5xx o error (fallback)
        $retryUrls = [];
        foreach ($headResults as $u => $r) {
            $s = $r['status'];
            if ($s === null || ($s >= 400 && $s < 600)) {
                $retryUrls[] = $u;
            }
        }
        $getResults = $retryUrls ? self::doMulti($retryUrls, false) : [];

        foreach ($urls as $u) {
            $getR  = $getResults[$u]  ?? null;
            $headR = $headResults[$u] ?? null;
            // Preferimos el resultado de GET si lo hicimos (mas confiable).
            $out[$u] = $getR ?? $headR ?? ['status' => null, 'final_url' => null, 'error' => 'sin resultado'];
        }
        return $out;
    }

    /**
     * @param array<int, string> $urls
     * @return array<string, array{status:int|null, final_url:string|null, error:string|null}>
     */
    private static function doMulti(array $urls, bool $head): array
    {
        $mh = curl_multi_init();
        $handles = [];
        foreach ($urls as $u) {
            $ch = curl_init();
            $opts = [
                CURLOPT_URL            => $u,
                CURLOPT_NOBODY         => $head,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS      => self::PROBE_MAX_REDIRECTS,
                CURLOPT_TIMEOUT        => self::PROBE_TIMEOUT,
                CURLOPT_CONNECTTIMEOUT => self::PROBE_CONNECT_TO,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_USERAGENT      => self::USER_AGENT,
                CURLOPT_ACCEPT_ENCODING => '',  // acepta gzip sin pedir explicito
            ];
            if (!$head) {
                // Para GET, limitamos el body para no bajar megas si el HTML es gigante
                $opts[CURLOPT_RANGE] = '0-16383'; // primeros 16KB
            }
            curl_setopt_array($ch, $opts);
            curl_multi_add_handle($mh, $ch);
            $handles[$u] = $ch;
        }

        $running = null;
        do {
            $st = curl_multi_exec($mh, $running);
            if ($running) { curl_multi_select($mh, 1.0); }
        } while ($running && $st === CURLM_OK);

        $out = [];
        foreach ($handles as $u => $ch) {
            $code     = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
            $finalUrl = (string)curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
            $err      = curl_error($ch) ?: null;
            curl_multi_remove_handle($mh, $ch);
            curl_close($ch);
            $out[$u] = [
                'status'    => $code > 0 ? $code : null,
                'final_url' => $finalUrl !== '' ? $finalUrl : null,
                'error'     => $err,
            ];
        }
        curl_multi_close($mh);
        return $out;
    }

    /**
     * @param array{status:int|null, final_url:string|null, error:string|null} $r
     */
    private static function upsertResult(int $articleId, string $url, array $r): void
    {
        $status   = $r['status'];
        $finalUrl = $r['final_url'];
        // Normalizar: si final_url es igual al url original, guardar null
        if ($finalUrl !== null && rtrim($finalUrl, '/') === rtrim($url, '/')) {
            $finalUrl = null;
        }
        $err = $r['error'];
        if (is_string($err)) { $err = mb_substr($err, 0, 255); }

        $isBroken = ($status === null)
            || (int)$status === 404
            || (int)$status === 410
            || (int)$status >= 500;

        $hash = hash('sha256', $url);

        // INSERT primero; si ya existe, UPDATE respetando first_seen_broken_at.
        Database::instance()->query(
            "INSERT INTO article_links
                (article_id, url, url_hash, status_code, final_url, error_message,
                 last_checked_at, first_seen_broken_at)
             VALUES
                (:aid, :url, :hash, :s, :fu, :err, CURRENT_TIMESTAMP, :fsb)
             ON DUPLICATE KEY UPDATE
                url             = VALUES(url),
                status_code     = VALUES(status_code),
                final_url       = VALUES(final_url),
                error_message   = VALUES(error_message),
                last_checked_at = CURRENT_TIMESTAMP,
                first_seen_broken_at = IF(:is_broken = 1,
                    IFNULL(first_seen_broken_at, CURRENT_TIMESTAMP),
                    NULL)",
            [
                'aid'       => $articleId,
                'url'       => mb_substr($url, 0, 2048),
                'hash'      => $hash,
                's'         => $status,
                'fu'        => $finalUrl !== null ? mb_substr($finalUrl, 0, 2048) : null,
                'err'       => $err,
                'fsb'       => $isBroken ? date('Y-m-d H:i:s') : null,
                'is_broken' => $isBroken ? 1 : 0,
            ]
        );
    }

    /**
     * Borra registros de article_links cuyas URLs ya no estan en el articulo
     * (fueron editadas/removidas del contenido).
     *
     * @param array<int, string> $currentUrls
     */
    private static function pruneRemovedLinks(int $articleId, array $currentUrls): void
    {
        if (!$currentUrls) {
            Database::instance()->query(
                'DELETE FROM article_links WHERE article_id = :a',
                ['a' => $articleId]
            );
            return;
        }
        $hashes = array_map(fn($u) => hash('sha256', $u), $currentUrls);
        $in = implode(',', array_map(fn($h) => "'" . $h . "'", $hashes));
        Database::instance()->query(
            "DELETE FROM article_links
             WHERE article_id = :a AND url_hash NOT IN ($in)",
            ['a' => $articleId]
        );
    }
}
