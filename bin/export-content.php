<?php
/**
 * Migra el contenido de la base vieja al arbol content/.
 *
 * Lee un dump de MySQL (el .sql.gz que generaba el boton "Descargar backup de
 * DB" del admin, o cualquier mysqldump) y escribe un archivo Markdown por
 * articulo, producto, categoria y autor, mas site.php, affiliate-links.php y
 * redirects.php.
 *
 * Uso:
 *   php bin/export-content.php backup.sql.gz
 *   php bin/export-content.php backup.sql --out=content --force
 *
 * Opciones:
 *   --out=DIR   destino (default: content)
 *   --force     sobrescribe archivos existentes en el destino
 *   --site=ID   si el dump tiene varios sitios, cual exportar (default: el primero)
 *
 * Es idempotente y no toca la base: solo lee el archivo.
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("CLI solamente.\n");
}

require dirname(__DIR__) . '/core/bootstrap.php';

$argv = $_SERVER['argv'] ?? [];
$dumpPath = null;
$outDir   = APP_ROOT . '/content';
$force    = false;
$siteId   = null;

for ($i = 1; $i < count($argv); $i++) {
    $a = $argv[$i];
    if ($a === '--force')                    { $force = true; }
    elseif (strncmp($a, '--out=', 6) === 0)  { $outDir = substr($a, 6); }
    elseif (strncmp($a, '--site=', 7) === 0) { $siteId = (int)substr($a, 7); }
    elseif ($a[0] !== '-')                   { $dumpPath = $a; }
}

if ($dumpPath === null || !is_readable($dumpPath)) {
    fwrite(STDERR, "\n  Uso: php bin/export-content.php <backup.sql|backup.sql.gz> [--out=DIR] [--force] [--site=ID]\n\n");
    exit(1);
}
if ($outDir[0] !== '/') {
    $outDir = APP_ROOT . '/' . $outDir;
}

echo "\n  Leyendo " . basename($dumpPath) . "…\n";

$sql = substr($dumpPath, -3) === '.gz'
    ? (string)gzdecode((string)file_get_contents($dumpPath))
    : (string)file_get_contents($dumpPath);

if ($sql === '') {
    fwrite(STDERR, "  ✗ El dump esta vacio o no se pudo descomprimir.\n\n");
    exit(1);
}

$db = parseDump($sql);
foreach (['sites', 'articles'] as $t) {
    if (empty($db[$t])) {
        fwrite(STDERR, "  ✗ El dump no tiene filas en `$t`. ¿Es el backup correcto?\n\n");
        exit(1);
    }
}

$site = $siteId !== null
    ? (first(array_filter($db['sites'], fn($r) => (int)$r['id'] === $siteId)) ?? null)
    : first($db['sites']);

if ($site === null) {
    fwrite(STDERR, "  ✗ No se encontro el sitio pedido en el dump.\n\n");
    exit(1);
}
$sid = (int)$site['id'];
echo "  Sitio: {$site['name']} ({$site['domain']})\n\n";

$rowsFor = function (string $table) use ($db, $sid): array {
    return array_values(array_filter(
        $db[$table] ?? [],
        fn($r) => !isset($r['site_id']) || (int)$r['site_id'] === $sid
    ));
};

$categories = index($rowsFor('categories'), 'id');
$authors    = index($rowsFor('authors'), 'id');
$affiliates = index($rowsFor('affiliate_links'), 'id');
$products   = index($rowsFor('products'), 'id');
$articles   = $rowsFor('articles');
$settings   = $rowsFor('settings');
$redirects  = $rowsFor('redirects');

$written = 0;
$skipped = 0;

$put = function (string $path, string $body) use ($force, &$written, &$skipped): void {
    if (!$force && is_file($path)) {
        echo "    · existe, se saltea: " . basename($path) . "\n";
        $skipped++;
        return;
    }
    $dir = dirname($path);
    if (!is_dir($dir)) { mkdir($dir, 0775, true); }
    file_put_contents($path, $body);
    $written++;
};

// --- site.php -------------------------------------------------------------
$kv = [];
foreach ($settings as $s) { $kv[$s['key']] = $s['value']; }

$put($outDir . '/site.php', renderSitePhp($site, $kv));

// --- categorias -----------------------------------------------------------
foreach ($categories as $c) {
    $fm = [
        'name'             => $c['name'],
        'sort_order'       => (int)($c['sort_order'] ?? 0),
        'parent'           => !empty($c['parent_id']) ? ($categories[$c['parent_id']]['slug'] ?? null) : null,
        'meta_title'       => $c['meta_title']       ?? null,
        'meta_description' => $c['meta_description'] ?? null,
        'featured_image'   => $c['featured_image']   ?? null,
    ];
    $put($outDir . '/categories/' . $c['slug'] . '.md', renderMd($fm, (string)($c['description'] ?? '')));
}

// --- autores --------------------------------------------------------------
foreach ($authors as $a) {
    $fm = [
        'name'         => $a['name'],
        'expertise'    => $a['expertise']  ?? null,
        'avatar_url'   => $a['avatar_url'] ?? null,
        'social_links' => jsonList($a['social_links'] ?? null),
    ];
    $put($outDir . '/authors/' . $a['slug'] . '.md', renderMd($fm, (string)($a['bio'] ?? '')));
}

// --- afiliados ------------------------------------------------------------
if ($affiliates) {
    $put($outDir . '/affiliate-links.php', renderAffiliatesPhp($affiliates));
}

// --- productos ------------------------------------------------------------
foreach ($products as $p) {
    $fm = [
        'name'              => $p['name'],
        'brand'             => $p['brand'] ?? null,
        'category'          => !empty($p['category_id']) ? ($categories[$p['category_id']]['slug'] ?? null) : null,
        'affiliate'         => !empty($p['affiliate_link_id']) ? ($affiliates[$p['affiliate_link_id']]['tracking_slug'] ?? null) : null,
        'description_short' => $p['description_short'] ?? null,
        'logo_url'          => $p['logo_url']       ?? null,
        'rating'            => $p['rating']         !== null ? (float)$p['rating'] : null,
        'price_from'        => $p['price_from']     !== null ? (float)$p['price_from'] : null,
        'price_currency'    => $p['price_currency'] ?? null,
        'pricing_model'     => $p['pricing_model']  ?? 'custom',
        'featured'          => !empty($p['featured']),
        'meta_title'        => $p['meta_title']       ?? null,
        'meta_description'  => $p['meta_description'] ?? null,
        'updated'           => dateOnly($p['updated_at'] ?? null),
        'features'          => jsonList($p['features'] ?? null),
        'pros'              => jsonList($p['pros']     ?? null),
        'cons'              => jsonList($p['cons']     ?? null),
        'specs'             => jsonMap($p['specs']     ?? null),
    ];
    $put($outDir . '/products/' . $p['slug'] . '.md', renderMd($fm, (string)($p['description_long'] ?? '')));
}

// --- articulos ------------------------------------------------------------
foreach ($articles as $a) {
    $productSlugs = [];
    foreach (jsonList($a['related_product_ids'] ?? null) ?? [] as $pid) {
        if (isset($products[$pid])) { $productSlugs[] = $products[$pid]['slug']; }
    }
    $fm = [
        'title'            => $a['title'],
        'subtitle'         => $a['subtitle'] ?? null,
        'excerpt'          => $a['excerpt']  ?? null,
        'type'             => $a['article_type'] ?? 'guide',
        'status'           => $a['status']       ?? 'draft',
        'category'         => !empty($a['category_id']) ? ($categories[$a['category_id']]['slug'] ?? null) : null,
        'author'           => !empty($a['author_id'])   ? ($authors[$a['author_id']]['slug']     ?? null) : null,
        'published'        => $a['published_at'] ?? null,
        'updated'          => dateOnly($a['updated_at'] ?? null),
        'featured_image'   => $a['featured_image'] ?? null,
        'products'         => $productSlugs ?: null,
        'rating'           => isset($a['rating']) && $a['rating'] !== null ? (float)$a['rating'] : null,
        'verdict'          => $a['verdict'] ?? null,
        'pros'             => jsonList($a['pros'] ?? null),
        'cons'             => jsonList($a['cons'] ?? null),
        'meta_title'       => $a['meta_title']       ?? null,
        'meta_description' => $a['meta_description'] ?? null,
    ];
    $put($outDir . '/articles/' . $a['slug'] . '.md', renderMd($fm, (string)($a['content'] ?? '')));
}

// --- redirects ------------------------------------------------------------
if ($redirects) {
    $put($outDir . '/redirects.php', renderRedirectsPhp($redirects));
}

echo "\n  ✓ $written archivos escritos" . ($skipped ? ", $skipped salteados (usá --force para pisarlos)" : '') . "\n";
echo "    Revisá el resultado y despues: php bin/build-content.php\n\n";
exit(0);


// =============================================================================
// Parser de dump
// =============================================================================

/**
 * Extrae las filas de cada tabla del dump.
 *
 * Soporta INSERT con lista de columnas explicita (lo que emitia nuestro
 * backup) y sin ella (mysqldump por defecto), en cuyo caso el orden se toma
 * del CREATE TABLE.
 *
 * @return array<string, array<int, array<string, string|null>>>
 */
function parseDump(string $sql): array
{
    // Columnas por tabla, desde los CREATE TABLE.
    $schema = [];
    if (preg_match_all('/CREATE TABLE (?:IF NOT EXISTS )?`([^`]+)`\s*\((.*?)\n\)\s*ENGINE/is', $sql, $m, PREG_SET_ORDER)) {
        foreach ($m as $t) {
            preg_match_all('/^\s*`([^`]+)`\s+[a-zA-Z]/m', $t[2], $cm);
            $schema[$t[1]] = $cm[1];
        }
    }

    $out = [];
    $offset = 0;
    while (preg_match('/INSERT INTO `([^`]+)`\s*(\(([^)]*)\))?\s*VALUES\s*/i', $sql, $m, PREG_OFFSET_CAPTURE, $offset)) {
        $table = $m[1][0];
        $cols = !empty($m[3][0])
            ? array_map(fn($c) => trim($c, " `\t\n"), explode(',', $m[3][0]))
            : ($schema[$table] ?? []);

        $pos = $m[0][1] + strlen($m[0][0]);
        [$tuples, $pos] = readTuples($sql, $pos);
        $offset = $pos;

        if (!$cols) { continue; }
        foreach ($tuples as $vals) {
            if (count($vals) !== count($cols)) { continue; }
            $out[$table][] = array_combine($cols, $vals);
        }
    }
    return $out;
}

/**
 * Lee las tuplas `(a,b),(c,d);` que siguen a un VALUES, respetando comillas.
 *
 * @return array{0: array<int, array<int, string|null>>, 1: int}
 */
function readTuples(string $s, int $pos): array
{
    $tuples = [];
    $len = strlen($s);

    while ($pos < $len) {
        while ($pos < $len && ($s[$pos] === ' ' || $s[$pos] === "\n" || $s[$pos] === "\r" || $s[$pos] === ',')) {
            $pos++;
        }
        if ($pos >= $len || $s[$pos] !== '(') { break; }
        $pos++;

        $vals = [];
        $buf = '';
        $inStr = false;
        $sawStr = false;   // el valor actual vino entrecomillado

        while ($pos < $len) {
            $c = $s[$pos];

            if ($inStr) {
                if ($c === '\\' && $pos + 1 < $len) {
                    // Escapes de mysqldump: \' \" \\ \n \r \0 \Z
                    $next = $s[$pos + 1];
                    $buf .= match ($next) {
                        'n' => "\n", 'r' => "\r", '0' => "\0",
                        'Z' => "\x1A", default => $next,
                    };
                    $pos += 2;
                    continue;
                }
                if ($c === "'") {
                    // '' dentro de un string es una comilla literal.
                    if ($pos + 1 < $len && $s[$pos + 1] === "'") {
                        $buf .= "'";
                        $pos += 2;
                        continue;
                    }
                    $inStr = false;
                    $pos++;
                    continue;
                }
                $buf .= $c;
                $pos++;
                continue;
            }

            // Espacios y saltos fuera de comillas son separacion, no contenido:
            // mysqldump corta lineas dentro de una tupla cuando es larga.
            if ($c === ' ' || $c === "\n" || $c === "\r" || $c === "\t") { $pos++; continue; }

            if ($c === "'") { $inStr = true; $sawStr = true; $pos++; continue; }
            if ($c === ',') { $vals[] = finishValue($buf, $sawStr); $buf = ''; $sawStr = false; $pos++; continue; }
            if ($c === ')') { $vals[] = finishValue($buf, $sawStr); $pos++; break; }
            $buf .= $c;
            $pos++;
        }

        $tuples[] = $vals;

        // Fin del statement.
        $peek = $pos;
        while ($peek < $len && ($s[$peek] === ' ' || $s[$peek] === "\n" || $s[$peek] === "\r")) { $peek++; }
        if ($peek < $len && $s[$peek] === ';') { $pos = $peek + 1; break; }
    }

    return [$tuples, $pos];
}

/**
 * Convierte el buffer crudo de un valor al tipo PHP.
 *
 * $wasQuoted distingue el string vacio ('') del NULL: sin ese dato los dos
 * llegarian como buffer vacio.
 */
function finishValue(string $buf, bool $wasQuoted): ?string
{
    if ($wasQuoted) {
        return $buf;
    }
    $t = trim($buf);
    if ($t === '' || strcasecmp($t, 'NULL') === 0) { return null; }
    return $t;
}

// =============================================================================
// Render
// =============================================================================

/**
 * Arma el archivo Markdown con front-matter.
 *
 * @param array<string, mixed> $fm
 */
function renderMd(array $fm, string $body): string
{
    $out = "---\n";
    foreach ($fm as $k => $v) {
        if ($v === null || $v === '' || $v === []) { continue; }

        if (is_array($v)) {
            $isMap = array_keys($v) !== range(0, count($v) - 1);
            $out .= "$k:\n";
            foreach ($v as $ik => $iv) {
                $out .= $isMap
                    ? '  ' . $ik . ': ' . scalarOut((string)$iv, false) . "\n"
                    : '  - ' . scalarOut((string)$iv, false) . "\n";
            }
            continue;
        }
        if (is_bool($v)) { $out .= "$k: " . ($v ? 'true' : 'false') . "\n"; continue; }
        if (is_int($v) || is_float($v)) { $out .= "$k: $v\n"; continue; }

        $s = (string)$v;
        if (strpos($s, "\n") !== false) {
            $out .= "$k: |\n";
            foreach (explode("\n", rtrim($s)) as $line) { $out .= '  ' . $line . "\n"; }
            continue;
        }
        $out .= "$k: " . scalarOut($s, true) . "\n";
    }
    $out .= "---\n\n" . rtrim($body) . "\n";
    return $out;
}

/**
 * Entrecomilla si el valor podria confundir al parser de front-matter.
 */
function scalarOut(string $s, bool $topLevel): string
{
    $needsQuote = $s === ''
        || ($topLevel && strpos($s, ': ') !== false)
        || strpos($s, ' #') !== false
        || in_array(strtolower($s), ['true','false','yes','no','on','off','null','~'], true)
        || $s[0] === '[' || $s[0] === '"' || $s[0] === "'" || $s[0] === '|' || $s[0] === '>' || $s[0] === '-';

    if (!$needsQuote) { return $s; }
    return '"' . str_replace(['\\', '"', "\n"], ['\\\\', '\\"', '\\n'], $s) . '"';
}

/**
 * @param array<string, mixed> $site
 * @param array<string, mixed> $settings
 */
function renderSitePhp(array $site, array $settings): string
{
    $keep = [
        'theme_preset', 'custom_css',
        'newsletter_enabled', 'newsletter_heading', 'newsletter_description',
        'newsletter_button_text', 'newsletter_action_url', 'newsletter_email_field_name',
        'newsletter_hidden_fields_json', 'newsletter_success_message',
    ];
    $clean = [];
    foreach ($keep as $k) {
        if (array_key_exists($k, $settings)) { $clean[$k] = $settings[$k]; }
    }

    $cfg = [
        'domain' => $site['domain'],
        'name'   => $site['name'],
        'slug'   => $site['slug'],
        'theme_name'    => $site['theme_name'] ?: 'default',
        'primary_color' => $site['primary_color'] ?: null,
        'logo_url'      => $site['logo_url'] ?: null,
        'favicon_url'   => $site['favicon_url'] ?: null,
        'default_language' => $site['default_language'] ?: 'es',
        'default_country'  => $site['default_country'] ?: 'AR',
        'meta_title_template'       => $site['meta_title_template'] ?: null,
        'meta_description_template' => $site['meta_description_template'] ?: null,
        'affiliate_disclosure_text' => $site['affiliate_disclosure_text'] ?: null,
        'google_analytics_id'                => $site['google_analytics_id'] ?: null,
        'google_tag_manager_id'              => $site['google_tag_manager_id'] ?? null ?: null,
        'google_ads_id'                      => $site['google_ads_id'] ?? null ?: null,
        'microsoft_clarity_id'               => $site['microsoft_clarity_id'] ?? null ?: null,
        'meta_pixel_id'                      => $site['meta_pixel_id'] ?? null ?: null,
        'google_search_console_verification' => $site['google_search_console_verification'] ?: null,
        'settings' => $clean,
    ];

    return "<?php\n/**\n * Configuracion del sitio. Generado por bin/export-content.php.\n"
         . " * Nada secreto va aca: para eso esta .env, que no se commitea.\n */\n\nreturn "
         . varExport($cfg, 0) . ";\n";
}

/**
 * @param array<int, array<string, mixed>> $affiliates
 */
function renderAffiliatesPhp(array $affiliates): string
{
    $out = [];
    foreach ($affiliates as $a) {
        $out[$a['tracking_slug']] = [
            'name'            => $a['name'],
            'destination_url' => $a['destination_url'],
            'network'         => $a['network_name'] ?: null,
            'active'          => !empty($a['active']),
        ];
    }
    return "<?php\n/**\n * Links de afiliado. La clave es el tracking_slug: lo que va en /go/{slug}.\n"
         . " * Generado por bin/export-content.php.\n */\n\nreturn " . varExport($out, 0) . ";\n";
}

/**
 * @param array<int, array<string, mixed>> $redirects
 */
function renderRedirectsPhp(array $redirects): string
{
    $out = [];
    foreach ($redirects as $r) {
        if (isset($r['active']) && !$r['active']) { continue; }
        $code = (int)($r['status_code'] ?? 301);
        $out[$r['from_path']] = $code === 301
            ? $r['to_path']
            : ['to' => $r['to_path'], 'status' => $code];
    }
    return "<?php\n/**\n * Redirects. Generado por bin/export-content.php.\n */\n\nreturn "
         . varExport($out, 0) . ";\n";
}

/**
 * var_export con indentacion de 4 espacios y arrays cortos.
 *
 * @param mixed $v
 */
function varExport($v, int $depth): string
{
    $pad = str_repeat('    ', $depth + 1);
    if (is_array($v)) {
        if ($v === []) { return '[]'; }
        $isList = array_keys($v) === range(0, count($v) - 1);
        $out = "[\n";
        foreach ($v as $k => $item) {
            $out .= $pad . ($isList ? '' : var_export((string)$k, true) . ' => ') . varExport($item, $depth + 1) . ",\n";
        }
        return $out . str_repeat('    ', $depth) . ']';
    }
    if ($v === null)  { return 'null'; }
    if (is_bool($v))  { return $v ? 'true' : 'false'; }
    if (is_int($v) || is_float($v)) { return (string)$v; }
    return var_export((string)$v, true);
}

// =============================================================================
// Helpers
// =============================================================================

/**
 * @param array<int, array<string, mixed>> $rows
 * @return array<string|int, array<string, mixed>>
 */
function index(array $rows, string $key): array
{
    $out = [];
    foreach ($rows as $r) { $out[$r[$key]] = $r; }
    return $out;
}

/**
 * @param array<int, mixed> $a
 * @return mixed
 */
function first(array $a)
{
    return $a ? reset($a) : null;
}

/**
 * Decodifica una columna JSON a lista de strings.
 *
 * @return array<int, mixed>|null
 */
function jsonList($raw): ?array
{
    if ($raw === null || $raw === '') { return null; }
    $d = json_decode((string)$raw, true);
    return is_array($d) && $d !== [] ? array_values($d) : null;
}

/**
 * Decodifica una columna JSON a mapa clave => valor.
 *
 * @return array<string, mixed>|null
 */
function jsonMap($raw): ?array
{
    if ($raw === null || $raw === '') { return null; }
    $d = json_decode((string)$raw, true);
    return is_array($d) && $d !== [] ? $d : null;
}

function dateOnly(?string $ts): ?string
{
    if (!$ts) { return null; }
    $t = strtotime($ts);
    return $t ? date('Y-m-d', $t) : null;
}
