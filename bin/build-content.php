<?php
/**
 * Compila content/ a var/content.php y reporta que se genero.
 *
 * En produccion no hace falta correrlo: Content::load() detecta que el cache
 * quedo viejo y recompila solo. Este CLI existe para dos cosas:
 *   - Validar el contenido antes de commitear (lo corre el CI en cada PR).
 *   - Ver los numeros de un vistazo mientras escribis.
 *
 * Uso:
 *   php bin/build-content.php            compila y muestra el resumen
 *   php bin/build-content.php --check    solo valida, no escribe (para CI)
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("CLI solamente.\n");
}

require dirname(__DIR__) . '/core/bootstrap.php';

use Core\Content;
use Core\ContentBuilder;

$checkOnly = in_array('--check', $_SERVER['argv'] ?? [], true);

$t0 = microtime(true);

try {
    $data = ContentBuilder::build();
} catch (\Throwable $e) {
    fwrite(STDERR, "\n  ✗ " . $e->getMessage() . "\n\n");
    exit(1);
}

$ms = round((microtime(true) - $t0) * 1000);

$published = 0;
$drafts    = 0;
$byType    = [];
foreach ($data['articles'] as $a) {
    if ($a['status'] === 'published') {
        $published++;
        $byType[$a['article_type']] = ($byType[$a['article_type']] ?? 0) + 1;
    } else {
        $drafts++;
    }
}

echo "\n  Contenido compilado en {$ms}ms\n\n";
printf("    %-18s %s\n", 'Sitio',      $data['site']['name'] . ' (' . $data['site']['domain'] . ')');
printf("    %-18s %d publicados, %d borradores\n", 'Articulos', $published, $drafts);
foreach ($byType as $type => $n) {
    printf("      %-16s %d\n", $type, $n);
}
printf("    %-18s %d\n", 'Productos',  count($data['products']));
printf("    %-18s %d\n", 'Categorias', count($data['categories']));
printf("    %-18s %d\n", 'Autores',    count($data['authors']));
printf("    %-18s %d\n", 'Afiliados',  count($data['affiliate_links']));
printf("    %-18s %d\n", 'Redirects',  count($data['redirects']));

if ($checkOnly) {
    echo "\n  ✓ Contenido valido (--check: no se escribio el cache)\n\n";
    exit(0);
}

// Escribir el cache reutilizando el camino real de Content.
Content::reset();
Content::load();

$cache = APP_ROOT . Content::CACHE_FILE;
$size = is_file($cache) ? round(filesize($cache) / 1024) : 0;
echo "\n  ✓ Escrito " . ltrim(Content::CACHE_FILE, '/') . " ({$size} KB)\n\n";
exit(0);
