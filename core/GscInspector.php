<?php
namespace Core;

/**
 * Cliente de Google Search Console URL Inspection API.
 *
 * Setup requerido (user side, una vez):
 *   1. Crear proyecto en Google Cloud Console.
 *   2. Habilitar "Search Console API" en el proyecto.
 *   3. Crear service account, descargar JSON key.
 *   4. En Google Search Console, ir a Settings → Users → Add user, pegar
 *      el email del service account (formato xxx@xxx.iam.gserviceaccount.com)
 *      con permiso "Full" o "Restricted".
 *   5. Pegar el contenido del JSON key en /admin/settings → GSC Service Account.
 *   6. Guardar la Property URL de GSC (ej: https://capacero.online/ o
 *      sc-domain:capacero.online para Domain property).
 *
 * Quota: 2000 requests/dia por propiedad, 600/min. Para sitios <1000 URLs
 * no es limite practico.
 *
 * Docs: https://developers.google.com/webmaster-tools/v1/urlInspection.index/inspect
 */
final class GscInspector
{
    private const TOKEN_URL     = 'https://oauth2.googleapis.com/token';
    private const INSPECT_URL   = 'https://searchconsole.googleapis.com/v1/urlInspection/index:inspect';
    private const SCOPE         = 'https://www.googleapis.com/auth/webmasters.readonly';
    private const TIMEOUT       = 15;

    private const SETTING_JSON     = 'gsc_service_account_json';
    private const SETTING_PROPERTY = 'gsc_property_url';

    /** Cache de access token en memoria por request (JWT dura 1h, refresh cheap). */
    private static ?array $tokenCache = null;

    /**
     * Esta configurado? (Settings tiene JSON y property).
     */
    public static function isConfigured(int $siteId): bool
    {
        $json = (string)Settings::get($siteId, self::SETTING_JSON, '');
        $prop = (string)Settings::get($siteId, self::SETTING_PROPERTY, '');
        if ($json === '' || $prop === '') { return false; }
        $data = json_decode($json, true);
        return is_array($data)
            && !empty($data['client_email'])
            && !empty($data['private_key']);
    }

    /**
     * Inspeccionar una URL. Retorna estructura normalizada o error.
     *
     * @return array{
     *   ok:bool,
     *   verdict:?string,
     *   coverage_state:?string,
     *   indexing_state:?string,
     *   robots_txt_state:?string,
     *   page_fetch_state:?string,
     *   google_canonical:?string,
     *   user_canonical:?string,
     *   last_crawl_time:?string,
     *   error:?string
     * }
     */
    public static function inspect(int $siteId, string $url): array
    {
        $empty = [
            'ok' => false, 'verdict' => null, 'coverage_state' => null,
            'indexing_state' => null, 'robots_txt_state' => null,
            'page_fetch_state' => null, 'google_canonical' => null,
            'user_canonical' => null, 'last_crawl_time' => null, 'error' => null,
        ];

        if (!self::isConfigured($siteId)) {
            return $empty + ['error' => 'GSC no configurado en Settings'];
        }

        $property = (string)Settings::get($siteId, self::SETTING_PROPERTY, '');

        try {
            $token = self::accessToken($siteId);
        } catch (\Throwable $e) {
            return $empty + ['error' => 'Token error: ' . mb_substr($e->getMessage(), 0, 200)];
        }

        $body = json_encode([
            'inspectionUrl' => $url,
            'siteUrl'       => $property,
        ], JSON_UNESCAPED_SLASHES);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => self::INSPECT_URL,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $body,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token,
            ],
            CURLOPT_TIMEOUT        => self::TIMEOUT,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ]);
        $resp = curl_exec($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $err  = curl_error($ch);
        curl_close($ch);

        if ($resp === false || $err) {
            return $empty + ['error' => 'cURL: ' . ($err ?: 'unknown')];
        }
        $data = json_decode((string)$resp, true);
        if ($code !== 200 || !is_array($data)) {
            $apiMsg = is_array($data) && isset($data['error']['message']) ? $data['error']['message'] : '';
            return $empty + ['error' => "HTTP $code" . ($apiMsg ? ": $apiMsg" : '')];
        }

        $idx = $data['inspectionResult']['indexStatusResult'] ?? [];
        return [
            'ok'                => true,
            'verdict'           => isset($idx['verdict']) ? (string)$idx['verdict'] : null,
            'coverage_state'    => isset($idx['coverageState']) ? (string)$idx['coverageState'] : null,
            'indexing_state'    => isset($idx['indexingState']) ? (string)$idx['indexingState'] : null,
            'robots_txt_state'  => isset($idx['robotsTxtState']) ? (string)$idx['robotsTxtState'] : null,
            'page_fetch_state'  => isset($idx['pageFetchState']) ? (string)$idx['pageFetchState'] : null,
            'google_canonical'  => isset($idx['googleCanonical']) ? (string)$idx['googleCanonical'] : null,
            'user_canonical'    => isset($idx['userCanonical']) ? (string)$idx['userCanonical'] : null,
            'last_crawl_time'   => isset($idx['lastCrawlTime']) ? (string)$idx['lastCrawlTime'] : null,
            'error'             => null,
        ];
    }

    /**
     * Get access token via JWT bearer flow (service account).
     * Cache en memoria por request (1 token por site).
     */
    private static function accessToken(int $siteId): string
    {
        $now = time();
        if (isset(self::$tokenCache[$siteId])
            && self::$tokenCache[$siteId]['expires_at'] > ($now + 30)) {
            return self::$tokenCache[$siteId]['token'];
        }

        $json = (string)Settings::get($siteId, self::SETTING_JSON, '');
        $sa = json_decode($json, true);
        if (!is_array($sa) || empty($sa['client_email']) || empty($sa['private_key'])) {
            throw new \RuntimeException('Service account JSON invalido');
        }

        $jwt = self::signJwt($sa['client_email'], $sa['private_key']);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => self::TOKEN_URL,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query([
                'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'  => $jwt,
            ]),
            CURLOPT_TIMEOUT        => self::TIMEOUT,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ]);
        $resp = curl_exec($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);

        if ($code !== 200 || $resp === false) {
            throw new \RuntimeException('Token exchange HTTP ' . $code);
        }
        $data = json_decode((string)$resp, true);
        if (!is_array($data) || empty($data['access_token'])) {
            throw new \RuntimeException('Token response sin access_token');
        }

        self::$tokenCache[$siteId] = [
            'token'      => $data['access_token'],
            'expires_at' => $now + (int)($data['expires_in'] ?? 3600),
        ];
        return $data['access_token'];
    }

    /**
     * Firma un JWT RS256 con la private key del service account.
     */
    private static function signJwt(string $email, string $privateKey): string
    {
        $header = ['alg' => 'RS256', 'typ' => 'JWT'];
        $now = time();
        $payload = [
            'iss'   => $email,
            'scope' => self::SCOPE,
            'aud'   => self::TOKEN_URL,
            'iat'   => $now,
            'exp'   => $now + 3600,
        ];
        $segments = [
            self::b64url(json_encode($header, JSON_UNESCAPED_SLASHES)),
            self::b64url(json_encode($payload, JSON_UNESCAPED_SLASHES)),
        ];
        $signingInput = implode('.', $segments);

        $pk = openssl_pkey_get_private($privateKey);
        if ($pk === false) {
            throw new \RuntimeException('openssl_pkey_get_private fallo: private_key invalida');
        }
        $signature = '';
        $ok = openssl_sign($signingInput, $signature, $pk, OPENSSL_ALGO_SHA256);
        if (!$ok) {
            throw new \RuntimeException('openssl_sign fallo');
        }
        $segments[] = self::b64url($signature);
        return implode('.', $segments);
    }

    private static function b64url(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
