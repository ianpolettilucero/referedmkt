<?php
/**
 * Informe de Google Search Console y Google Analytics 4, de solo lectura.
 *
 * Uso:
 *   php bin/google-report.php                 los dos, ultimos 28 dias
 *   php bin/google-report.php --days=7
 *   php bin/google-report.php --only=gsc      solo Search Console
 *   php bin/google-report.php --only=ga       solo Analytics
 *   php bin/google-report.php --path=/noticia/   filtra Search Console por URL
 *
 * Necesita GOOGLE_SERVICE_ACCOUNT_JSON y, para Analytics, GA4_PROPERTY_ID.
 * Como configurarlas: docs/google.md.
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("CLI solamente.\n");
}

require dirname(__DIR__) . '/core/bootstrap.php';

use Core\GoogleApi;

$opts = getopt('', ['days::', 'only::', 'path::', 'limit::']);
$days  = max(1, (int)($opts['days'] ?? 28));
$only  = (string)($opts['only'] ?? '');
$path  = isset($opts['path']) ? (string)$opts['path'] : null;
$limit = max(1, min(250, (int)($opts['limit'] ?? 20)));

// Search Console tarda unos dos dias en consolidar: el rango termina antes de
// ayer para no leer un dia incompleto como una caida.
$end   = date('Y-m-d', strtotime('-2 days'));
$start = date('Y-m-d', strtotime("-" . ($days + 1) . " days"));

try {
    $api = GoogleApi::fromEnv();
} catch (\Throwable $e) {
    fwrite(STDERR, $e->getMessage() . "\nVer docs/google.md.\n");
    exit(2);
}

function tabla(array $head, array $rows): void
{
    $w = array_map('mb_strlen', $head);
    foreach ($rows as $r) {
        foreach ($r as $i => $c) { $w[$i] = max($w[$i], mb_strlen((string)$c)); }
    }
    $line = fn(array $r) => implode('  ', array_map(
        fn($c, $i) => $i === 0 ? str_pad((string)$c, $w[$i] + strlen((string)$c) - mb_strlen((string)$c))
                               : str_pad((string)$c, $w[$i], ' ', STR_PAD_LEFT),
        $r, array_keys($r)
    ));
    echo $line($head) . "\n" . str_repeat('-', array_sum($w) + 2 * (count($w) - 1)) . "\n";
    foreach ($rows as $r) { echo $line($r) . "\n"; }
    echo "\n";
}

$fail = 0;

if ($only === '' || $only === 'gsc') {
    $site = getenv('GSC_SITE_URL') ?: 'sc-domain:capacero.online';
    echo "== Search Console ($site), $start a $end" . ($path ? ", URLs con \"$path\"" : '') . " ==\n\n";
    try {
        foreach (['query' => 'Consulta', 'page' => 'Pagina'] as $dim => $label) {
            $rows = $api->searchAnalytics($site, $start, $end, [$dim], $limit, $path);
            $out = [];
            foreach ($rows as $r) {
                $out[] = [
                    $r['keys'][0] ?? '',
                    (int)$r['clicks'],
                    (int)$r['impressions'],
                    number_format(100 * (float)$r['ctr'], 1, ',', '.') . '%',
                    number_format((float)$r['position'], 1, ',', '.'),
                ];
            }
            tabla([$label, 'Clics', 'Impresiones', 'CTR', 'Posicion'], $out);
        }
    } catch (\Throwable $e) {
        fwrite(STDERR, "Search Console: " . $e->getMessage() . "\n\n");
        $fail = 1;
    }
}

if ($only === '' || $only === 'ga') {
    $prop = getenv('GA4_PROPERTY_ID');
    if (!$prop) {
        fwrite(STDERR, "Analytics: falta GA4_PROPERTY_ID (el id numerico de la propiedad, no el G-XXXX).\n\n");
        $fail = 1;
    } else {
        echo "== Google Analytics 4 (propiedad $prop), $start a $end ==\n\n";
        try {
            $rows = $api->ga4Report($prop, $start, $end, ['pagePath'],
                ['screenPageViews', 'sessions', 'engagementRate'], $limit);
            $out = [];
            foreach ($rows as $r) {
                $out[] = [
                    $r['dims'][0],
                    (int)$r['metrics'][0],
                    (int)$r['metrics'][1],
                    number_format(100 * (float)$r['metrics'][2], 1, ',', '.') . '%',
                ];
            }
            tabla(['Pagina', 'Vistas', 'Sesiones', 'Interaccion'], $out);

            $rows = $api->ga4Report($prop, $start, $end, ['sessionDefaultChannelGroup'], ['sessions'], 10);
            tabla(['Canal', 'Sesiones'], array_map(fn($r) => [$r['dims'][0], (int)$r['metrics'][0]], $rows));
        } catch (\Throwable $e) {
            fwrite(STDERR, "Analytics: " . $e->getMessage() . "\n\n");
            $fail = 1;
        }
    }
}

exit($fail);
