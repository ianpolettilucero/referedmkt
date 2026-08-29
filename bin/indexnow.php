<?php
/**
 * Avisa a los buscadores que soportan IndexNow cuales URLs cambiaron.
 *
 * Por que hace falta: el sitio publica casi a diario notas cuyo valor caduca
 * en dias —una con vencimiento a 3 dias en el titulo, sin ir mas lejos—, y
 * hasta ahora nada le avisaba a nadie. Google dio de baja el ping de sitemaps
 * en junio de 2023, asi que para Google no hay nada que hacer mas alla del
 * sitemap. Bing, Yandex, Seznam y Naver comparten el protocolo IndexNow, que
 * es un POST y nada mas, y ahi si se gana el dia de diferencia.
 *
 * Un solo POST al endpoint generico se replica entre los buscadores que
 * participan, asi que no hace falta pegarle a cada uno.
 *
 * La clave vive como archivo estatico en public/<clave>.txt, que es como el
 * protocolo verifica que quien avisa controla el dominio.
 *
 * Uso:
 *   php bin/indexnow.php <base> <head>     URLs de los articulos del rango
 *   php bin/indexnow.php --all             todas las URLs publicadas
 *   php bin/indexnow.php --dry-run ...     muestra el payload y no envia
 */

const HOST     = 'capacero.online';
const ENDPOINT = 'https://api.indexnow.org/indexnow';

$args   = array_slice($argv, 1);
$dryRun = in_array('--dry-run', $args, true);
$args   = array_values(array_filter($args, fn($a) => $a !== '--dry-run'));

// La clave es el unico .txt de 8 a 128 caracteres hexadecimales en public/.
$claves = glob(__DIR__ . '/../public/*.txt') ?: [];
$clave  = null;
foreach ($claves as $ruta) {
    $nombre = basename($ruta, '.txt');
    if (preg_match('/^[a-zA-Z0-9-]{8,128}$/', $nombre) && trim((string)file_get_contents($ruta)) === $nombre) {
        $clave = $nombre;
        break;
    }
}
if ($clave === null) {
    fwrite(STDERR, "No hay archivo de clave de IndexNow en public/. Se omite el aviso.\n");
    exit(0);
}

/** Prefijo de URL por tipo de articulo, igual que article_url(). */
function prefijo(string $type): string
{
    return match ($type) {
        'review'     => '/resena/',
        'comparison' => '/comparativa/',
        'news'       => '/noticia/',
        default      => '/guia/',
    };
}

/** Lee type y status del front-matter sin cargar el motor de contenido. */
function meta(string $file): array
{
    $txt = @file_get_contents($file);
    if ($txt === false) { return []; }
    $type = preg_match('/^type:\s*(\S+)/m', $txt, $m) ? $m[1] : 'guide';
    $st   = preg_match('/^status:\s*(\S+)/m', $txt, $m) ? $m[1] : 'draft';
    return ['type' => trim($type, "\"'"), 'status' => trim($st, "\"'")];
}

$archivos = [];
if (in_array('--all', $args, true)) {
    $archivos = glob(__DIR__ . '/../content/articles/*.md') ?: [];
} else {
    $base = $args[0] ?? 'HEAD~1';
    $head = $args[1] ?? 'HEAD';
    exec('git diff --name-only ' . escapeshellarg("$base..$head")
        . ' -- content/articles 2>/dev/null', $out, $code);
    if ($code !== 0) {
        fwrite(STDERR, "No se pudo leer el diff $base..$head. Se omite el aviso.\n");
        exit(0);
    }
    foreach ($out as $rel) {
        if (substr($rel, -3) === '.md' && is_file(__DIR__ . '/../' . $rel)) {
            $archivos[] = __DIR__ . '/../' . $rel;
        }
    }
}

$urls = [];
foreach ($archivos as $f) {
    $m = meta($f);
    if (($m['status'] ?? '') !== 'published') { continue; }
    $urls[] = 'https://' . HOST . prefijo($m['type'] ?? 'guide') . basename($f, '.md');
}

if (!$urls) {
    echo "Sin articulos publicados en el rango: nada que avisar.\n";
    exit(0);
}

// Los listados y la home cambian cuando entra una nota, asi que van con ella.
array_unshift($urls, 'https://' . HOST . '/', 'https://' . HOST . '/noticias');
$urls = array_values(array_unique($urls));

$payload = json_encode([
    'host'        => HOST,
    'key'         => $clave,
    'keyLocation' => 'https://' . HOST . '/' . $clave . '.txt',
    'urlList'     => $urls,
], JSON_UNESCAPED_SLASHES);

echo count($urls) . " URL(s) a avisar:\n";
foreach ($urls as $u) { echo "  $u\n"; }

if ($dryRun) {
    echo "\n--dry-run: no se envia nada.\n$payload\n";
    exit(0);
}

$ch = curl_init(ENDPOINT);
curl_setopt_array($ch, [
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_HTTPHEADER     => ['Content-Type: application/json; charset=utf-8'],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 20,
]);
$resp = curl_exec($ch);
$http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$err  = curl_error($ch);
curl_close($ch);

// 200 y 202 son exito; el resto se informa pero no rompe el deploy, porque el
// sitio ya esta publicado y el aviso es una mejora, no un requisito.
if ($err !== '') {
    fwrite(STDERR, "IndexNow: error de red ($err). El deploy sigue.\n");
    exit(0);
}
echo "IndexNow respondio HTTP $http" . ($resp !== '' ? ": $resp" : '') . "\n";
if ($http !== 200 && $http !== 202) {
    fwrite(STDERR, "IndexNow no acepto el aviso. El deploy sigue igual.\n");
}
exit(0);
