<?php
/**
 * Auditoria de SEO, GEO y arquitectura sobre el HTML realmente servido.
 *
 * No es un linter de codigo: levanta cada URL publica y mide lo que ve un
 * crawler. Reporta hallazgos con severidad y sale con codigo != 0 si hay
 * errores, asi puede correr en CI.
 *
 * Uso:
 *   php bin/audit.php                      contra el sitio local
 *   php bin/audit.php https://capacero.online
 *   php bin/audit.php --json               salida para procesar
 *
 * Que mide:
 *   SEO        title, meta description, H1, jerarquia de headings, canonical,
 *              og/twitter, alt de imagenes, longitud de contenido
 *   GEO        respuesta directa temprana, densidad de datos, FAQ, listas y
 *              tablas (lo que hace un pasaje citable por un LLM)
 *   Arquitectura  profundidad de click, huerfanos, enlazado interno, clusters
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("CLI solamente.\n");
}

require dirname(__DIR__) . '/core/bootstrap.php';

use Core\Content;

$argv = $_SERVER['argv'] ?? [];
$jsonOut = in_array('--json', $argv, true);
$base = null;
foreach (array_slice($argv, 1) as $a) {
    if (strncmp($a, 'http', 4) === 0) { $base = rtrim($a, '/'); }
}

// Sin base explicita: levantamos el servidor embebido nosotros.
$serverProc = null;
if ($base === null) {
    $port = 8899;
    $base = "http://127.0.0.1:$port";
    $domain = Content::site()['domain'] ?? 'localhost';
    $cmd = sprintf(
        'DEV_SITE_DOMAIN=%s php -S 127.0.0.1:%d -t %s %s > /dev/null 2>&1 & echo $!',
        escapeshellarg($domain), $port,
        escapeshellarg(APP_ROOT . '/public'),
        escapeshellarg(APP_ROOT . '/bin/dev-server.php')
    );
    $serverProc = (int)shell_exec($cmd);
    for ($i = 0; $i < 40; $i++) {
        usleep(150000);
        if (@file_get_contents("$base/healthz") !== false) { break; }
    }
}

/** @var array<int, array{sev:string, area:string, url:string, msg:string}> */
$findings = [];
$add = function (string $sev, string $area, string $url, string $msg) use (&$findings): void {
    $findings[] = ['sev' => $sev, 'area' => $area, 'url' => $url, 'msg' => $msg];
};

// -----------------------------------------------------------------------------
// Recolectar
// -----------------------------------------------------------------------------

$urls = ['/', '/productos'];
foreach (active_sections() as $sec) { $urls[] = $sec['path']; }
foreach (Content::publishedArticles() as $a) { $urls[] = article_url($a); }
foreach (Content::products()   as $p) { $urls[] = product_url($p); }
foreach (Content::categories() as $c) { $urls[] = category_url($c); }
foreach (Content::authors()    as $x) { $urls[] = '/autor/' . $x['slug']; }
$urls = array_values(array_unique($urls));

$pages = [];
foreach ($urls as $u) {
    $html = @file_get_contents($base . $u);
    if ($html === false) {
        $add('error', 'arquitectura', $u, 'No se pudo cargar la pagina.');
        continue;
    }
    $pages[$u] = $html;
}

// -----------------------------------------------------------------------------
// Helpers de parseo
// -----------------------------------------------------------------------------

$tag = function (string $html, string $re): ?string {
    return preg_match($re, $html, $m) ? html_entity_decode(trim($m[1]), ENT_QUOTES, 'UTF-8') : null;
};
$title    = fn($h) => $tag($h, '#<title>(.*?)</title>#si');
$metaDesc = fn($h) => $tag($h, '#<meta\s+name="description"\s+content="([^"]*)"#i');
$canonical= fn($h) => $tag($h, '#<link\s+rel="canonical"\s+href="([^"]*)"#i');
$robots   = fn($h) => $tag($h, '#<meta\s+name="robots"\s+content="([^"]*)"#i');

/** Texto visible del <main>, sin nav/footer/scripts. */
$mainText = function (string $html): string {
    if (preg_match('#<main[^>]*>(.*?)</main>#si', $html, $m)) { $html = $m[1]; }
    $html = preg_replace('#<(script|style|nav|noscript)[^>]*>.*?</\1>#si', ' ', $html);
    return trim(preg_replace('/\s+/u', ' ', strip_tags($html)));
};

$headings = function (string $html): array {
    $out = [];
    if (preg_match('#<main[^>]*>(.*?)</main>#si', $html, $mm)) { $html = $mm[1]; }
    if (preg_match_all('#<(h[1-4])[^>]*>(.*?)</\1>#si', $html, $m, PREG_SET_ORDER)) {
        foreach ($m as $h) {
            $out[] = ['level' => (int)substr($h[1], 1), 'text' => trim(strip_tags($h[2]))];
        }
    }
    return $out;
};

$jsonLd = function (string $html): array {
    $blocks = [];
    if (preg_match_all('#<script type="application/ld\+json">(.*?)</script>#si', $html, $m)) {
        foreach ($m[1] as $raw) {
            $d = json_decode(str_replace('<\\/', '</', $raw), true);
            if (is_array($d)) { $blocks[] = $d; }
        }
    }
    return $blocks;
};

/**
 * Enlaces internos. $onlyMain distingue dos cosas que no son lo mismo:
 *   - todo el documento: lo que sigue un crawler, incluida la navegacion.
 *     Sirve para alcanzabilidad y profundidad de click.
 *   - solo <main>: enlaces editoriales dentro del contenido. Son los que
 *     construyen clusters tematicos; los del menu no aportan contexto.
 */
$internalLinks = function (string $html, bool $onlyMain = false): array {
    if ($onlyMain && preg_match('~<main[^>]*>(.*?)</main>~si', $html, $mm)) {
        $html = $mm[1];
    }
    $out = [];
    if (preg_match_all('~<a[^>]+href="(/[^"?#]*)"~i', $html, $m)) {
        foreach ($m[1] as $href) {
            $href = rtrim($href, '/') ?: '/';
            $out[$href] = true;
        }
    }
    return array_keys($out);
};

// -----------------------------------------------------------------------------
// SEO por pagina
// -----------------------------------------------------------------------------

$titles = [];
$descs  = [];

foreach ($pages as $url => $html) {
    $t = $title($html);
    $d = $metaDesc($html);
    $c = $canonical($html);
    $r = $robots($html);
    $hs = $headings($html);
    $text = $mainText($html);
    $words = str_word_count(strip_tags($text), 0, 'áéíóúñÁÉÍÓÚÑüÜ');

    // --- Title ---
    if ($t === null || $t === '') {
        $add('error', 'seo', $url, 'Sin <title>.');
    } else {
        $titles[$t][] = $url;
        $len = mb_strlen($t);
        if ($len > 60) {
            $add('warn', 'seo', $url, "Title de $len caracteres: Google trunca cerca de 60. \"$t\"");
        } elseif ($len < 25) {
            $add('warn', 'seo', $url, "Title de solo $len caracteres, desaprovecha espacio. \"$t\"");
        }
    }

    // --- Meta description ---
    if ($d === null || $d === '') {
        $add('error', 'seo', $url, 'Sin meta description: Google inventa el snippet.');
    } else {
        $descs[$d][] = $url;
        $len = mb_strlen($d);
        if ($len > 160)     { $add('warn', 'seo', $url, "Meta description de $len caracteres, se trunca cerca de 160."); }
        elseif ($len < 70)  { $add('warn', 'seo', $url, "Meta description de solo $len caracteres."); }
    }

    // --- H1 ---
    $h1s = array_values(array_filter($hs, fn($h) => $h['level'] === 1));
    if (count($h1s) === 0)      { $add('error', 'seo', $url, 'Sin H1.'); }
    elseif (count($h1s) > 1)    { $add('error', 'seo', $url, 'Mas de un H1 (' . count($h1s) . ').'); }

    // --- Jerarquia de headings ---
    $prev = 0;
    foreach ($hs as $h) {
        if ($prev > 0 && $h['level'] > $prev + 1) {
            $add('warn', 'seo', $url, "Salto de H$prev a H{$h['level']} (\"{$h['text']}\"): rompe la jerarquia.");
            break;
        }
        $prev = $h['level'];
    }

    // --- Canonical ---
    if ($c === null) { $add('error', 'seo', $url, 'Sin canonical.'); }

    // --- Imagenes sin alt ---
    if (preg_match_all('#<img[^>]*>#i', $html, $imgs)) {
        $sinAlt = 0;
        foreach ($imgs[0] as $img) {
            if (!preg_match('#\balt=("[^"]*"|\'[^\']*\')#i', $img)) { $sinAlt++; }
        }
        if ($sinAlt > 0) { $add('warn', 'seo', $url, "$sinAlt imagen(es) sin atributo alt."); }
    }

    // --- Longitud ---
    $isArticle = preg_match('~^/(guia|resena|comparativa|noticia)/~', $url);
    if ($isArticle && $words < 900) {
        $add('warn', 'seo', $url, "Solo $words palabras: corto para competir en una query informacional.");
    }

    // --- JSON-LD ---
    $types = [];
    foreach ($jsonLd($html) as $b) { $types[] = $b['@type'] ?? '?'; }
    if (!$types && $r === null) {
        $add('warn', 'seo', $url, 'Sin JSON-LD.');
    }
    if ($isArticle && !array_intersect($types, ['Article', 'NewsArticle', 'Review'])) {
        $add('error', 'seo', $url, 'Articulo sin schema Article/Review. Tipos: ' . implode(', ', $types));
    }

    // -------------------------------------------------------------------------
    // GEO: que hace a un pasaje citable por un asistente
    // -------------------------------------------------------------------------
    if ($isArticle) {
        // Respuesta directa temprana: las primeras ~50 palabras deben responder,
        // no introducir. Los LLMs citan el pasaje que responde solo.
        $first = implode(' ', array_slice(explode(' ', $text), 0, 60));
        $tieneDato = (bool)preg_match('/\d/', $first);
        if (!$tieneDato) {
            $add('info', 'geo', $url, 'La entrada no trae ningun dato concreto en las primeras 60 palabras.');
        }

        // Densidad de datos: numeros, porcentajes, precios. Un LLM cita lo
        // verificable antes que lo generico.
        //
        // Dos formas, porque en castellano la moneda va adelante ("USD 58") tanto
        // como atras ("58 USD"), y contar solo una subestimaba articulos que estan
        // llenos de cifras. Las ramas son excluyentes: la primera arranca en digito
        // y la segunda en letra, asi que ninguna cifra se cuenta dos veces.
        preg_match_all(
            '/\b\d+([.,]\d+)?\s*(%|USD|EUR|ARS|GB|TB|MB|d[ií]as?|semanas?|meses|años?|horas?|min)\b'
            . '|\b(USD|EUR|ARS)\s*\d+([.,]\d+)?/iu',
            $text,
            $datos
        );
        $nDatos = count($datos[0]);
        if ($nDatos < 5) {
            $add('warn', 'geo', $url, "Solo $nDatos datos cuantificados. Los asistentes citan cifras concretas.");
        }

        // FAQ: bloque directamente extraible como par pregunta/respuesta.
        $tieneFaq = false;
        foreach ($jsonLd($html) as $b) { if (($b['@type'] ?? '') === 'FAQPage') { $tieneFaq = true; } }
        if (!$tieneFaq) {
            $add('warn', 'geo', $url, 'Sin seccion "Preguntas frecuentes": es el formato mas facil de citar.');
        }

        // Listas y tablas: estructura que se extrae limpia.
        $nLi = preg_match_all('#<li[^>]*>#i', $html);
        $nTable = preg_match_all('#<table#i', $html);
        if ($nLi < 8 && $nTable === 0) {
            $add('info', 'geo', $url, 'Casi sin listas ni tablas: el contenido en prosa corrida se cita peor.');
        }

        // Fecha visible de actualizacion.
        if (!preg_match('/Actualizado el/i', $html)) {
            $add('info', 'geo', $url, 'Sin fecha de actualizacion visible. La frescura pesa en los motores de respuesta.');
        }
    }
}

// --- Duplicados globales ---
foreach ($titles as $t => $us) {
    if (count($us) > 1) {
        $add('error', 'seo', implode(', ', array_slice($us, 0, 3)), 'Title duplicado en ' . count($us) . ' paginas: "' . $t . '"');
    }
}
foreach ($descs as $d => $us) {
    if (count($us) > 1) {
        $add('warn', 'seo', implode(', ', array_slice($us, 0, 3)), 'Meta description duplicada en ' . count($us) . ' paginas.');
    }
}

// -----------------------------------------------------------------------------
// Arquitectura: grafo de enlaces internos
// -----------------------------------------------------------------------------

$graph  = [];   // todos los enlaces: alcanzabilidad y profundidad
$edito  = [];   // solo los de <main>: senal editorial
foreach ($pages as $url => $html) {
    $key = rtrim($url, '/') ?: '/';
    $graph[$key] = $internalLinks($html, false);
    $edito[$key] = $internalLinks($html, true);
}

// BFS desde la home para calcular profundidad de click.
$depth = ['/' => 0];
$queue = ['/'];
while ($queue) {
    $cur = array_shift($queue);
    foreach ($graph[$cur] ?? [] as $next) {
        if (!isset($graph[$next]) || isset($depth[$next])) { continue; }
        $depth[$next] = $depth[$cur] + 1;
        $queue[] = $next;
    }
}

// Enlaces entrantes por pagina.
$inbound = array_fill_keys(array_keys($graph), 0);
foreach ($edito as $from => $tos) {
    foreach ($tos as $to) {
        if (isset($inbound[$to]) && $to !== $from) { $inbound[$to]++; }
    }
}

foreach ($graph as $url => $_) {
    if (!isset($depth[$url])) {
        $add('error', 'arquitectura', $url, 'Huerfana: no se llega desde la home por ningun enlace.');
    } elseif ($depth[$url] > 3) {
        $add('warn', 'arquitectura', $url, "A {$depth[$url]} clicks de la home. Mas de 3 diluye autoridad.");
    }
    if ($inbound[$url] === 0 && $url !== '/' && !preg_match('~^/(guias|resenas|comparativas|noticias|productos)$~', $url)) {
        $add('warn', 'arquitectura', $url, 'Cero enlaces entrantes desde el contenido: solo se llega por el menu.');
    } elseif ($inbound[$url] === 1 && preg_match('~^/(guia|resena|comparativa)/~', $url)) {
        $add('info', 'arquitectura', $url, 'Un solo enlace entrante desde contenido.');
    }
}

// Enlaces salientes desde articulos: sin ellos no hay cluster.
foreach ($graph as $url => $tos) {
    if (!preg_match('~^/(guia|resena|comparativa|noticia)/~', $url)) { continue; }
    $aArticulos = array_filter($edito[$url] ?? [], fn($t) => preg_match('~^/(guia|resena|comparativa|noticia)/~', $t));
    if (count($aArticulos) < 2) {
        $add('warn', 'arquitectura', $url, 'Enlaza a menos de 2 articulos: no forma cluster tematico.');
    }
}

// -----------------------------------------------------------------------------
// Salida
// -----------------------------------------------------------------------------

if ($serverProc) { @shell_exec("kill $serverProc 2>/dev/null"); }

$order = ['error' => 0, 'warn' => 1, 'info' => 2];
usort($findings, fn($a, $b) => [$order[$a['sev']], $a['area'], $a['url']] <=> [$order[$b['sev']], $b['area'], $b['url']]);

if ($jsonOut) {
    echo json_encode(['pages' => count($pages), 'findings' => $findings], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), "\n";
    exit(count(array_filter($findings, fn($f) => $f['sev'] === 'error')) > 0 ? 1 : 0);
}

$counts = ['error' => 0, 'warn' => 0, 'info' => 0];
foreach ($findings as $f) { $counts[$f['sev']]++; }

$icon = ['error' => "\033[31m✗\033[0m", 'warn' => "\033[33m!\033[0m", 'info' => "\033[36mi\033[0m"];

echo "\n  Auditoria de " . count($pages) . " paginas en $base\n";
echo "  " . $counts['error'] . " errores, " . $counts['warn'] . " advertencias, " . $counts['info'] . " notas\n";

$lastArea = null;
foreach ($findings as $f) {
    if ($f['area'] !== $lastArea) {
        echo "\n  " . strtoupper($f['area']) . "\n";
        $lastArea = $f['area'];
    }
    printf("   %s %-46s %s\n", $icon[$f['sev']], mb_strimwidth($f['url'], 0, 46, '…'), $f['msg']);
}
echo "\n";

exit($counts['error'] > 0 ? 1 : 0);
