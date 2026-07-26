<?php
/**
 * Lista todas las URLs publicas del sitio, una por linea.
 *
 * Lo usa el smoke test de CI para verificar que cada pagina renderice, y sirve
 * para chequear a mano que un articulo nuevo quedo donde esperabas.
 *
 * Uso:
 *   php bin/list-urls.php
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("CLI solamente.\n");
}

require dirname(__DIR__) . '/core/bootstrap.php';

use Core\Content;

foreach (Content::publishedArticles() as $a) {
    echo article_url($a) . "\n";
}
foreach (Content::products() as $p) {
    echo product_url($p) . "\n";
}
foreach (Content::categories() as $c) {
    echo category_url($c) . "\n";
}
foreach (Content::authors() as $au) {
    echo '/autor/' . $au['slug'] . "\n";
}
