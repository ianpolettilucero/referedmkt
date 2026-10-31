<?php
namespace Core;

/**
 * Cliente minimo, de solo lectura, para Google Search Console y el Data API
 * de Google Analytics 4. Lo usa bin/google-report.php; el sitio publico no lo
 * carga nunca.
 *
 * Autentica con una cuenta de servicio: arma un JWT firmado con su clave
 * privada (RS256) y lo cambia por un token de acceso. Los dos alcances son de
 * lectura: el script no puede modificar ninguna propiedad aunque quisiera.
 *
 * Variables de entorno:
 *   GOOGLE_SERVICE_ACCOUNT_JSON  el JSON completo de la clave de la cuenta
 *   GA4_PROPERTY_ID              id numerico de la propiedad (no el G-XXXX)
 *   GSC_SITE_URL                 propiedad de Search Console; por defecto
 *                                sc-domain:capacero.online
 */
final class GoogleApi
{
    public const SCOPES = [
        'https://www.googleapis.com/auth/webmasters.readonly',
        'https://www.googleapis.com/auth/analytics.readonly',
    ];

    private ?string $token = null;

    /** @param array<string, mixed> $serviceAccount */
    public function __construct(private array $serviceAccount)
    {
        foreach (['client_email', 'private_key'] as $k) {
            if (empty($serviceAccount[$k]) || !is_string($serviceAccount[$k])) {
                throw new \InvalidArgumentException("La clave de la cuenta de servicio no tiene '$k'");
            }
        }
    }

    public static function fromEnv(): self
    {
        $raw = getenv('GOOGLE_SERVICE_ACCOUNT_JSON');
        if ($raw === false || trim($raw) === '') {
            throw new \RuntimeException('Falta la variable GOOGLE_SERVICE_ACCOUNT_JSON');
        }
        $sa = json_decode($raw, true);
        if (!is_array($sa)) {
            throw new \RuntimeException('GOOGLE_SERVICE_ACCOUNT_JSON no es un JSON valido');
        }
        return new self($sa);
    }

    public static function b64url(string $bin): string
    {
        return rtrim(strtr(base64_encode($bin), '+/', '-_'), '=');
    }

    /**
     * JWT para el intercambio de la cuenta de servicio (RFC 7523).
     *
     * @param array<string, mixed> $sa
     * @param string[] $scopes
     */
    public static function jwt(array $sa, array $scopes, int $now): string
    {
        $header = ['alg' => 'RS256', 'typ' => 'JWT'];
        if (!empty($sa['private_key_id'])) {
            $header['kid'] = (string)$sa['private_key_id'];
        }
        $claims = [
            'iss'   => $sa['client_email'],
            'scope' => implode(' ', $scopes),
            'aud'   => $sa['token_uri'] ?? 'https://oauth2.googleapis.com/token',
            'iat'   => $now,
            'exp'   => $now + 3600,
        ];
        $input = self::b64url(json_encode($header)) . '.' . self::b64url(json_encode($claims));

        $key = openssl_pkey_get_private((string)$sa['private_key']);
        if ($key === false) {
            throw new \RuntimeException('No se pudo leer la clave privada de la cuenta de servicio');
        }
        if (!openssl_sign($input, $sig, $key, OPENSSL_ALGO_SHA256)) {
            throw new \RuntimeException('No se pudo firmar el JWT');
        }
        return $input . '.' . self::b64url($sig);
    }

    public function token(): string
    {
        if ($this->token !== null) {
            return $this->token;
        }
        $uri = (string)($this->serviceAccount['token_uri'] ?? 'https://oauth2.googleapis.com/token');
        $res = $this->request($uri, http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion'  => self::jwt($this->serviceAccount, self::SCOPES, time()),
        ]), ['Content-Type: application/x-www-form-urlencoded']);
        if (empty($res['access_token'])) {
            throw new \RuntimeException('Google no devolvio un token de acceso');
        }
        return $this->token = (string)$res['access_token'];
    }

    /**
     * Search Console: filas de rendimiento de busqueda.
     *
     * @param string[] $dimensions query, page, country, device, date
     * @return array<int, array<string, mixed>>
     */
    public function searchAnalytics(string $site, string $start, string $end, array $dimensions, int $limit = 25, ?string $pageContains = null): array
    {
        $body = [
            'startDate'  => $start,
            'endDate'    => $end,
            'dimensions' => $dimensions,
            'rowLimit'   => $limit,
        ];
        if ($pageContains !== null) {
            $body['dimensionFilterGroups'] = [[
                'filters' => [['dimension' => 'page', 'operator' => 'contains', 'expression' => $pageContains]],
            ]];
        }
        $url = 'https://www.googleapis.com/webmasters/v3/sites/' . rawurlencode($site) . '/searchAnalytics/query';
        $res = $this->postJson($url, $body);
        return $res['rows'] ?? [];
    }

    /**
     * GA4 Data API: runReport.
     *
     * @param string[] $dimensions
     * @param string[] $metrics
     * @return array<int, array{dims: string[], metrics: string[]}>
     */
    public function ga4Report(string $property, string $start, string $end, array $dimensions, array $metrics, int $limit = 25): array
    {
        $body = [
            'dateRanges' => [['startDate' => $start, 'endDate' => $end]],
            'dimensions' => array_map(fn($d) => ['name' => $d], $dimensions),
            'metrics'    => array_map(fn($m) => ['name' => $m], $metrics),
            'orderBys'   => [['metric' => ['metricName' => $metrics[0]], 'desc' => true]],
            'limit'      => $limit,
        ];
        $url = 'https://analyticsdata.googleapis.com/v1beta/properties/' . rawurlencode($property) . ':runReport';
        $res = $this->postJson($url, $body);
        $out = [];
        foreach ($res['rows'] ?? [] as $r) {
            $out[] = [
                'dims'    => array_map(fn($v) => (string)($v['value'] ?? ''), $r['dimensionValues'] ?? []),
                'metrics' => array_map(fn($v) => (string)($v['value'] ?? ''), $r['metricValues'] ?? []),
            ];
        }
        return $out;
    }

    /** @param array<string, mixed> $body */
    private function postJson(string $url, array $body): array
    {
        return $this->request($url, json_encode($body), [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->token(),
        ]);
    }

    /** @param string[] $headers */
    private function request(string $url, string $payload, array $headers): array
    {
        // libcurl toma HTTPS_PROXY del entorno, que es como sale todo el
        // trafico en el entorno de trabajo.
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $payload,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => 30,
        ]);
        $raw  = curl_exec($ch);
        $code = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $err  = curl_error($ch);
        curl_close($ch);

        if ($raw === false) {
            throw new \RuntimeException("Fallo la conexion con $url: $err");
        }
        $data = json_decode((string)$raw, true);
        if ($code >= 400) {
            $msg = is_array($data) ? ($data['error']['message'] ?? $data['error_description'] ?? $raw) : $raw;
            throw new \RuntimeException("Google respondio $code: " . (is_string($msg) ? $msg : json_encode($msg)));
        }
        return is_array($data) ? $data : [];
    }
}
