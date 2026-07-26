<?php
/**
 * Importa articulos escritos como markdown en content/ hacia la tabla articles.
 *
 * Estructura esperada:
 *   content/{dominio}/articulos/{slug}.md
 *
 * Cada archivo arranca con un front-matter delimitado por --- :
 *
 *   ---
 *   title: Qué Es un EDR y En Qué Se Diferencia de un Antivirus
 *   subtitle: Detección y respuesta en endpoints, explicado sin marketing
 *   excerpt: Un EDR no es "un antivirus mejor"...
 *   category: antivirus-edr-empresas
 *   author: ian-poletti-lucero
 *   type: guide
 *   status: published
 *   published_at: 2026-07-26
 *   meta_title: Qué Es un EDR: Diferencias con Antivirus (2026)
 *   meta_description: Guía para PyMEs...
 *   products: crowdstrike-falcon, bitdefender-gravityzone
 *   ---
 *
 * El slug del articulo sale del nombre del archivo (x.md -> slug "x").
 *
 * Idempotencia:
 *   Se guarda el sha256 del archivo en content_files. Si no cambio, se skipea
 *   sin tocar la fila de articles (no bumpea updated_at ni el sitemap).
 *
 * Uso:
 *   php bin/import-content.php                # importa todo lo que cambio
 *   php bin/import-content.php --dry-run      # muestra que haria, sin escribir
 *   php bin/import-content.php --force        # reimporta aunque el hash coincida
 *   php bin/import-content.php --site dominio # solo ese sitio
 *
 * Se ejecuta solo en cada deploy via bin/post-deploy.php.
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("CLI solamente.\n");
}

require dirname(__DIR__) . '/core/bootstrap.php';

use Core\Database;

$argv    = $_SERVER['argv'] ?? [];
$dryRun  = in_array('--dry-run', $argv, true);
$force   = in_array('--force', $argv, true);
$onlySite = null;
foreach ($argv as $i => $a) {
    if ($a === '--help' || $a === '-h') {
        echo "Uso:\n";
        echo "  php bin/import-content.php               importa lo que cambio\n";
        echo "  php bin/import-content.php --dry-run     preview sin escribir\n";
        echo "  php bin/import-content.php --force       reimporta todo\n";
        echo "  php bin/import-content.php --site DOMAIN solo ese sitio\n";
        exit(0);
    }
    if ($a === '--site' && isset($argv[$i + 1])) { $onlySite = $argv[$i + 1]; }
}

$root = APP_ROOT;
$contentRoot = $root . '/content';
if (!is_dir($contentRoot)) {
    echo "Sin directorio content/. Nada que importar.\n";
    exit(0);
}

$db = Database::instance();
$stats = ['nuevos' => 0, 'actualizados' => 0, 'sin_cambios' => 0, 'errores' => 0];

foreach (glob($contentRoot . '/*', GLOB_ONLYDIR) ?: [] as $siteDir) {
    $domain = basename($siteDir);
    if ($onlySite !== null && $domain !== $onlySite) { continue; }

    $site = $db->fetch('SELECT id, domain FROM sites WHERE domain = :d LIMIT 1', ['d' => $domain]);
    if (!$site) {
        fwrite(STDERR, "  ! No existe el sitio '$domain' en la tabla sites. Skip.\n");
        $stats['errores']++;
        continue;
    }
    $siteId = (int)$site['id'];

    foreach (glob($siteDir . '/articulos/*.md') ?: [] as $file) {
        $rel  = ltrim(str_replace($root, '', $file), '/');
        $slug = basename($file, '.md');
        $raw  = (string)file_get_contents($file);
        $hash = hash('sha256', $raw);

        // Skip si el archivo no cambio desde el ultimo import.
        if (!$force) {
            $prev = $db->fetch(
                'SELECT content_hash FROM content_files WHERE site_id = :s AND path = :p LIMIT 1',
                ['s' => $siteId, 'p' => $rel]
            );
            if ($prev && $prev['content_hash'] === $hash) {
                $stats['sin_cambios']++;
                continue;
            }
        }

        try {
            [$meta, $body] = parse_front_matter($raw);
        } catch (\Throwable $e) {
            fwrite(STDERR, "  ! $rel: " . $e->getMessage() . "\n");
            $stats['errores']++;
            continue;
        }

        if (trim((string)($meta['title'] ?? '')) === '') {
            fwrite(STDERR, "  ! $rel: falta 'title' en el front-matter.\n");
            $stats['errores']++;
            continue;
        }
        if (trim($body) === '') {
            fwrite(STDERR, "  ! $rel: el cuerpo del articulo esta vacio.\n");
            $stats['errores']++;
            continue;
        }

        $categoryId = resolve_id($db, 'categories', $siteId, $meta['category'] ?? null, $rel, 'categoria');
        $authorId   = resolve_id($db, 'authors',    $siteId, $meta['author']   ?? null, $rel, 'autor');

        // products: lista de slugs -> array de IDs para related_product_ids (JSON)
        $productIds = [];
        foreach (split_list($meta['products'] ?? '') as $pslug) {
            $row = $db->fetch(
                'SELECT id FROM products WHERE site_id = :s AND slug = :sl LIMIT 1',
                ['s' => $siteId, 'sl' => $pslug]
            );
            if ($row) {
                $productIds[] = (int)$row['id'];
            } else {
                fwrite(STDERR, "  ~ $rel: producto '$pslug' no existe todavia, se ignora.\n");
            }
        }

        $type = strtolower(trim((string)($meta['type'] ?? 'guide')));
        if (!in_array($type, ['guide', 'review', 'comparison', 'news'], true)) { $type = 'guide'; }

        $status = strtolower(trim((string)($meta['status'] ?? 'published')));
        if (!in_array($status, ['draft', 'published', 'archived'], true)) { $status = 'draft'; }

        $publishedAt = trim((string)($meta['published_at'] ?? ''));
        if ($publishedAt !== '') {
            $ts = strtotime($publishedAt);
            $publishedAt = $ts ? date('Y-m-d H:i:s', $ts) : date('Y-m-d H:i:s');
        } else {
            $publishedAt = $status === 'published' ? date('Y-m-d H:i:s') : null;
        }

        $data = [
            'site_id'             => $siteId,
            'category_id'         => $categoryId,
            'author_id'           => $authorId,
            'slug'                => $slug,
            'title'               => mb_substr(trim((string)$meta['title']), 0, 255),
            'subtitle'            => nullable($meta['subtitle'] ?? null, 300),
            'excerpt'             => nullable($meta['excerpt'] ?? null, 500),
            'content'             => trim($body),
            'featured_image'      => nullable($meta['featured_image'] ?? null, 500),
            'article_type'        => $type,
            'related_product_ids' => $productIds ? json_encode($productIds) : null,
            'meta_title'          => nullable($meta['meta_title'] ?? null, 255),
            'meta_description'    => nullable($meta['meta_description'] ?? null, 500),
            'status'              => $status,
            'published_at'        => $publishedAt,
        ];

        $existing = $db->fetch(
            'SELECT id FROM articles WHERE site_id = :s AND slug = :sl LIMIT 1',
            ['s' => $siteId, 'sl' => $slug]
        );

        if ($dryRun) {
            printf("  [dry] %s %s (%s)\n", $existing ? 'UPDATE' : 'INSERT', $slug, $domain);
            $existing ? $stats['actualizados']++ : $stats['nuevos']++;
            continue;
        }

        if ($existing) {
            $articleId = (int)$existing['id'];
            $sets = [];
            foreach (array_keys($data) as $k) {
                if ($k === 'site_id') { continue; }
                $sets[] = "`$k` = :$k";
            }
            $params = $data;
            unset($params['site_id']);
            $params['id'] = $articleId;
            $db->query('UPDATE articles SET ' . implode(', ', $sets) . ' WHERE id = :id', $params);
            $stats['actualizados']++;
            printf("  ~ actualizado: %s\n", $slug);
        } else {
            $articleId = (int)$db->insert('articles', $data);
            $stats['nuevos']++;
            printf("  + nuevo: %s\n", $slug);
        }

        $db->query(
            'INSERT INTO content_files (site_id, path, content_hash, article_id)
             VALUES (:s, :p, :h, :a)
             ON DUPLICATE KEY UPDATE content_hash = VALUES(content_hash),
                                     article_id   = VALUES(article_id),
                                     imported_at  = CURRENT_TIMESTAMP',
            ['s' => $siteId, 'p' => $rel, 'h' => $hash, 'a' => $articleId]
        );

        // Avisar a Bing/Yandex del contenido nuevo o actualizado.
        if ($status === 'published') {
            $path = article_path($type, $slug);
            \Core\IndexNow::ping($siteId, ['https://' . $site['domain'] . $path]);
        }
    }
}

printf(
    "Import: %d nuevo(s), %d actualizado(s), %d sin cambios, %d error(es).%s\n",
    $stats['nuevos'], $stats['actualizados'], $stats['sin_cambios'], $stats['errores'],
    $dryRun ? ' [DRY RUN — no se escribio nada]' : ''
);
exit($stats['errores'] > 0 ? 1 : 0);

// -----------------------------------------------------------------------------

/**
 * Separa el front-matter (--- ... ---) del cuerpo markdown.
 *
 * Parser deliberadamente simple (key: value por linea, sin anidamiento) para
 * no depender de una libreria YAML — el proyecto no usa composer.
 *
 * @return array{0: array<string,string>, 1: string}
 */
function parse_front_matter(string $raw): array
{
    $raw = preg_replace('/^\xEF\xBB\xBF/', '', $raw) ?? $raw; // strip BOM
    $raw = str_replace("\r\n", "\n", $raw);

    if (strncmp($raw, "---\n", 4) !== 0) {
        throw new RuntimeException("falta el front-matter (debe arrancar con --- en la primera linea)");
    }
    $end = strpos($raw, "\n---", 3);
    if ($end === false) {
        throw new RuntimeException("front-matter sin cierre (falta el --- final)");
    }

    $head = substr($raw, 4, $end - 3);
    $body = ltrim(substr($raw, $end + 4), "\n");

    $meta = [];
    foreach (explode("\n", $head) as $line) {
        $line = rtrim($line);
        if ($line === '' || strncmp(ltrim($line), '#', 1) === 0) { continue; }
        $pos = strpos($line, ':');
        if ($pos === false) { continue; }
        $key = strtolower(trim(substr($line, 0, $pos)));
        $val = trim(substr($line, $pos + 1));
        // Quitar comillas envolventes si las hay.
        if (strlen($val) >= 2
            && (($val[0] === '"' && substr($val, -1) === '"')
             || ($val[0] === "'" && substr($val, -1) === "'"))) {
            $val = substr($val, 1, -1);
        }
        $meta[$key] = $val;
    }
    return [$meta, $body];
}

/**
 * Resuelve el id de una categoria/autor. Acepta el slug exacto o el nombre
 * visible ("Antivirus y EDR para empresas"), asi el .md no depende de conocer
 * los slugs internos. Avisa si no encuentra nada.
 */
function resolve_id(Database $db, string $table, int $siteId, ?string $ref, string $file, string $label): ?int
{
    $ref = trim((string)$ref);
    if ($ref === '') { return null; }

    // 1) slug exacto
    $row = $db->fetch(
        "SELECT id FROM `$table` WHERE site_id = :s AND slug = :r LIMIT 1",
        ['s' => $siteId, 'r' => $ref]
    );
    // 2) nombre visible exacto (case-insensitive por la collation ai_ci)
    if (!$row) {
        $row = $db->fetch(
            "SELECT id FROM `$table` WHERE site_id = :s AND name = :r LIMIT 1",
            ['s' => $siteId, 'r' => $ref]
        );
    }
    // 3) slug derivado del nombre que pasamos ("Gestión de contraseñas" -> gestion-de-contrasenas)
    if (!$row && function_exists('slugify')) {
        $row = $db->fetch(
            "SELECT id FROM `$table` WHERE site_id = :s AND slug = :r LIMIT 1",
            ['s' => $siteId, 'r' => slugify($ref)]
        );
    }

    if (!$row) {
        fwrite(STDERR, "  ~ $file: $label '$ref' no existe, queda sin asignar.\n");
        return null;
    }
    return (int)$row['id'];
}

/** @return array<int,string> */
function split_list(string $csv): array
{
    return array_values(array_filter(array_map('trim', explode(',', $csv)), fn($s) => $s !== ''));
}

function nullable(?string $v, int $max): ?string
{
    $v = trim((string)$v);
    return $v === '' ? null : mb_substr($v, 0, $max);
}

function article_path(string $type, string $slug): string
{
    return match ($type) {
        'review'     => '/resena/' . $slug,
        'comparison' => '/comparativa/' . $slug,
        'news'       => '/noticia/' . $slug,
        default      => '/guia/' . $slug,
    };
}
