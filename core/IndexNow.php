<?php
namespace Core;

/**
 * Cliente de IndexNow — standard abierto para notificar a motores de busqueda
 * sobre URLs nuevas o actualizadas.
 *
 * Soporta: Bing, Yandex, Seznam, Naver, Yep. Google NO soporta IndexNow (2026).
 *
 * Setup:
 *   1. Generar un key (UUID) via Settings en el admin o auto-generado al vuelo.
 *   2. Servir el key en https://{domain}/{key}.txt con contenido = key (verificacion).
 *   3. Llamar a IndexNow::ping($siteId, [$url1, $url2]) al publicar/actualizar.
 *
 * La llamada es fire-and-forget: no bloquea el save. Usa timeout corto (3s).
 * Si falla, logueamos pero no rompemos el flujo.
 *
 * Docs: https://www.indexnow.org/documentation
 */
final class IndexNow
{
    private const ENDPOINT      = 'https://api.indexnow.org/IndexNow';
    private const TIMEOUT       = 3;
    private const KEY_SETTING   = 'indexnow_key';

    /**
     * Retorna la key de IndexNow del sitio, generandola si no existe.
     * La key es un UUID v4 hex (32 chars) que Bing requiere en el body + verificar
     * en https://{domain}/{key}.txt.
     */
    public static function keyForSite(int $siteId): string
    {
        $existing = (string)Settings::get($siteId, self::KEY_SETTING, '');
        if ($existing !== '' && preg_match('/^[a-f0-9]{8,128}$/', $existing)) {
            return $existing;
        }
        $key = bin2hex(random_bytes(16)); // 32 chars hex, dentro del rango permitido
        Settings::set($siteId, self::KEY_SETTING, $key);
        return $key;
    }

    /**
     * Pinguea IndexNow con una lista de URLs para el sitio dado.
     * Fire-and-forget: retorna bool por conveniencia de tests pero los callers
     * no deberian dependrer del resultado.
     *
     * @param array<int, string> $urls URLs absolutas del mismo dominio que el site
     */
    public static function ping(int $siteId, array $urls): bool
    {
        $urls = array_values(array_unique(array_filter($urls, static function ($u) {
            return is_string($u) && preg_match('#^https?://#i', $u);
        })));
        if (!$urls) { return false; }
        if (count($urls) > 10000) { $urls = array_slice($urls, 0, 10000); }

        $site = Database::instance()->fetch(
            'SELECT domain FROM sites WHERE id = :id LIMIT 1',
            ['id' => $siteId]
        );
        if (!$site || empty($site['domain'])) { return false; }
        $host = $site['domain'];

        // Validar que todas las URLs son del mismo dominio (IndexNow lo requiere).
        foreach ($urls as $u) {
            $urlHost = strtolower((string)parse_url($u, PHP_URL_HOST));
            $siteHost = strtolower($host);
            $normalize = static fn(string $h): string => preg_replace('/^www\./i', '', $h) ?? $h;
            if ($normalize($urlHost) !== $normalize($siteHost)) {
                error_log('[referedmkt][indexnow] URL de dominio distinto skip: ' . $u);
                return false;
            }
        }

        $key = self::keyForSite($siteId);
        $keyLocation = 'https://' . $host . '/' . $key . '.txt';

        $payload = [
            'host'        => $host,
            'key'         => $key,
            'keyLocation' => $keyLocation,
            'urlList'     => $urls,
        ];

        if (!function_exists('curl_init')) {
            error_log('[referedmkt][indexnow] cURL no disponible');
            return false;
        }

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => self::ENDPOINT,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload, JSON_UNESCAPED_SLASHES),
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json; charset=utf-8',
                'Accept: application/json',
            ],
            CURLOPT_TIMEOUT        => self::TIMEOUT,
            CURLOPT_CONNECTTIMEOUT => 2,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ]);
        $resp = curl_exec($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $err  = curl_error($ch);
        curl_close($ch);

        // IndexNow retorna:
        //   200 → OK
        //   202 → Accepted (procesado async)
        //   400 → bad request, 403 → key inválida, 422 → URLs inválidas, 429 → rate limit
        $ok = ($code === 200 || $code === 202);
        if (!$ok) {
            error_log(sprintf(
                '[referedmkt][indexnow] ping fallo: code=%d err=%s body=%s urls=%d',
                $code, $err ?: '-', is_string($resp) ? mb_substr($resp, 0, 200) : '-', count($urls)
            ));
        }
        return $ok;
    }

    /**
     * Endpoint helper: sirve el contenido del {key}.txt para la verificacion
     * de IndexNow. Bing pega un GET a https://{domain}/{key}.txt y espera
     * recibir la misma key como contenido.
     *
     * Retorna true si el path matcheaba y se sirvio; false si no.
     */
    public static function serveKeyFileIfMatch(int $siteId, string $path): bool
    {
        if (!preg_match('#^/([a-f0-9]{8,128})\.txt$#i', $path, $m)) {
            return false;
        }
        $requested = $m[1];
        $configured = (string)Settings::get($siteId, self::KEY_SETTING, '');
        if ($configured === '' || $requested !== $configured) {
            return false;
        }
        header('Content-Type: text/plain; charset=utf-8');
        header('Cache-Control: public, max-age=86400');
        echo $configured;
        return true;
    }
}
