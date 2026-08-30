<?php
/**
 * Que parte del sitio pide trabajo, ordenado por urgencia.
 *
 * Las tareas programadas de guias, comparativas, fichas y hubs arrancan cada
 * una en una sesion nueva, sin memoria. Sin esto elegirian a ojo y volverian
 * siempre sobre lo mismo. Aca la eleccion sale de datos del propio contenido.
 *
 * Uso:
 *   php bin/staleness.php                       resumen de todas las secciones
 *   php bin/staleness.php --seccion=guias       una sola, con detalle
 *   php bin/staleness.php --seccion=fichas --top=5
 *   php bin/staleness.php --json                salida para procesar
 *
 * Secciones: fichas, hubs, guias, comparativas, resenas, huecos
 *
 * `huecos` no mide decadencia sino ausencia: categorias con producto cargado
 * y sin comparativa, productos sin resena. Es de donde sale el contenido
 * nuevo.
 *
 * El puntaje no decide nada solo: es el orden en que conviene mirar. Una nota
 * vieja que sigue siendo exacta no necesita tocarse, y eso lo decide quien
 * corre la tarea, no este script.
 */

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("CLI solamente.\n");
}

require dirname(__DIR__) . '/core/bootstrap.php';

use Core\Content;

$argv    = $_SERVER['argv'] ?? [];
$jsonOut = in_array('--json', $argv, true);
$seccion = null;
$top     = 0;
foreach (array_slice($argv, 1) as $a) {
    if (strncmp($a, '--seccion=', 10) === 0) { $seccion = substr($a, 10); }
    if (strncmp($a, '--top=', 6) === 0)      { $top = max(0, (int)substr($a, 6)); }
}

$hoy = new DateTimeImmutable('today');

/** Dias entre una fecha del front matter y hoy. Sin fecha, se asume muy vieja. */
$dias = function (?string $fecha) use ($hoy): int {
    if (!$fecha) { return 9999; }
    try {
        $d = new DateTimeImmutable(substr($fecha, 0, 10));
    } catch (Exception $e) {
        return 9999;
    }
    return max(0, (int)$hoy->diff($d)->format('%a') * ($d > $hoy ? 0 : 1));
};

$articulos  = Content::publishedArticles();
$productos  = Content::products();
$categorias = Content::categories();

// Cuerpo de todos los articulos junto, para contar enlaces entrantes.
$corpus = '';
foreach ($articulos as $a) { $corpus .= "\n" . ($a['content'] ?? ''); }

/** Cuantos articulos enlazan a una URL interna. */
$entrantes = function (string $url) use ($corpus): int {
    return substr_count($corpus, '](' . $url . ')') + substr_count($corpus, '](' . $url . '#');
};

/**
 * Senales de un articulo. Cada una es un hecho comprobable, no una opinion:
 * o falta el diagrama o no falta.
 */
$senalesArticulo = function (array $a) use ($dias): array {
    $body  = $a['content'] ?? '';
    $s     = [];
    $edad  = $dias($a['updated_at'] ?? $a['published_at'] ?? null);
    $peso  = 0;

    $svg = substr_count($body, '<svg');
    if ($svg === 0)      { $s[] = 'sin diagrama';            $peso += 40; }
    elseif ($svg === 1)  { $s[] = 'un solo diagrama';        $peso += 12; }

    $links = preg_match_all('#\]\(/[a-z]#', $body);
    if ($links < 6)      { $s[] = "solo $links enlaces internos"; $peso += 20; }

    $palabras = str_word_count(strip_tags($body));
    $rango = ['guide' => [1800, 2600], 'comparison' => [1500, 2200], 'review' => [1800, 2500]];
    [$min, $max] = $rango[$a['article_type']] ?? [1100, 1900];
    if ($palabras < $min) { $s[] = "$palabras palabras (minimo $min)"; $peso += 18; }
    if ($palabras > $max * 1.35) { $s[] = "$palabras palabras (objetivo $max)"; $peso += 6; }

    $mt = mb_strlen((string)($a['meta_title'] ?: $a['title']));
    if ($mt > 60)        { $s[] = "meta_title $mt caracteres";  $peso += 15; }
    $md = mb_strlen((string)($a['meta_description'] ?: ''));
    if ($md === 0)       { $s[] = 'sin meta_description';       $peso += 25; }
    elseif ($md > 158)   { $s[] = "meta_description $md caracteres"; $peso += 15; }
    elseif ($md < 110)   { $s[] = "meta_description corta ($md)";    $peso += 6; }

    // Encabezados que no se sostienen leidos solos. Misma regla que audit.php.
    preg_match_all('/^##\s+(.+)$/m', $body, $h);
    $anaf = [];
    foreach ($h[1] ?? [] as $t) {
        if (preg_match('/\b(est[ae]|est[ao]s|dich[ao]s?)\b/iu', $t)) { $anaf[] = trim($t); }
    }
    if ($anaf) { $s[] = count($anaf) . ' encabezado(s) anaforico(s)'; $peso += 30; }

    $peso += min(60, intdiv($edad, 7) * 3);

    return ['edad' => $edad, 'peso' => $peso, 'senales' => $s, 'anaforicos' => $anaf];
};

$secciones = [];

// ---------------------------------------------------------------- articulos
foreach (['guias' => 'guide', 'comparativas' => 'comparison', 'resenas' => 'review'] as $nombre => $tipo) {
    $filas = [];
    foreach ($articulos as $a) {
        if (($a['article_type'] ?? '') !== $tipo) { continue; }
        $r = $senalesArticulo($a);
        $filas[] = [
            'url'     => article_url($a),
            'titulo'  => $a['title'],
            'dias'    => $r['edad'],
            'puntaje' => $r['peso'],
            'senales' => $r['senales'],
        ];
    }
    usort($filas, fn($x, $y) => $y['puntaje'] <=> $x['puntaje']);
    $secciones[$nombre] = $filas;
}

// ------------------------------------------------------------------ fichas
$filas = [];
foreach ($productos as $p) {
    $s = [];
    $peso = 0;
    $edad = $dias($p['updated_at'] ?? null);

    // Una ficha con precio es la que mas rapido se vuelve falsa.
    if (!empty($p['price_from'])) {
        $peso += min(90, intdiv($edad, 7) * 6);
        $s[] = 'precio publicado: ' . $p['price_from'] . ' ' . ($p['price_currency'] ?: 'USD');
    } else {
        $peso += min(30, intdiv($edad, 7) * 2);
        $s[] = 'sin precio cargado';
    }

    $in = $entrantes(product_url($p));
    if ($in === 0)      { $s[] = 'huerfana: ningun articulo la enlaza'; $peso += 35; }
    elseif ($in === 1)  { $s[] = 'un solo enlace entrante';             $peso += 15; }

    $largo = mb_strlen((string)($p['description_long'] ?? ''));
    if ($largo < 1500)  { $s[] = "descripcion de $largo caracteres";    $peso += 20; }

    $md = mb_strlen((string)($p['meta_description'] ?? ''));
    if ($md === 0)      { $s[] = 'sin meta_description';                $peso += 25; }
    elseif ($md > 158)  { $s[] = "meta_description $md caracteres";     $peso += 15; }

    $filas[] = [
        'url' => product_url($p), 'titulo' => $p['name'],
        'dias' => $edad, 'puntaje' => $peso, 'senales' => $s,
    ];
}
usort($filas, fn($x, $y) => $y['puntaje'] <=> $x['puntaje']);
$secciones['fichas'] = $filas;

// -------------------------------------------------------------------- hubs
$filas = [];
foreach ($categorias as $c) {
    $s = [];
    $peso = 0;
    $cuerpo = mb_strlen(trim((string)($c['description'] ?? '')));
    $nProd = 0;
    foreach ($productos as $p) { if (($p['category_slug'] ?? '') === $c['slug']) { $nProd++; } }
    $nArt = 0;
    foreach ($articulos as $a) { if (($a['category_slug'] ?? '') === $c['slug']) { $nArt++; } }

    if ($cuerpo < 1200) { $s[] = "cuerpo de $cuerpo caracteres"; $peso += 60 - min(50, intdiv($cuerpo, 25)); }
    if ($nProd === 0)   { $s[] = 'sin productos cargados';       $peso += 10; }
    if ($nArt === 0)    { $s[] = 'sin articulos en la categoria'; $peso += 20; }

    $md = mb_strlen((string)($c['meta_description'] ?? ''));
    if ($md === 0)      { $s[] = 'sin meta_description';         $peso += 25; }
    elseif ($md > 158)  { $s[] = "meta_description $md caracteres"; $peso += 15; }

    $peso += $nProd * 3 + $nArt * 2;  // un hub con material rinde mas que uno vacio
    $s[] = "$nProd productos, $nArt articulos";

    $filas[] = [
        'url' => category_url($c), 'titulo' => $c['name'],
        'dias' => $dias($c['updated_at'] ?? null), 'puntaje' => $peso, 'senales' => $s,
    ];
}
usort($filas, fn($x, $y) => $y['puntaje'] <=> $x['puntaje']);
$secciones['hubs'] = $filas;

// ------------------------------------------------------------------ huecos
// Donde falta una pagina que el catalogo ya justifica.
$filas = [];
foreach ($categorias as $c) {
    $prods = [];
    foreach ($productos as $p) { if (($p['category_slug'] ?? '') === $c['slug']) { $prods[] = $p['name']; } }
    if (count($prods) < 3) { continue; }

    $cubierta = false;
    foreach ($articulos as $a) {
        if (($a['article_type'] ?? '') !== 'comparison') { continue; }
        if (($a['category_slug'] ?? '') === $c['slug']) { $cubierta = true; break; }
    }
    if ($cubierta) { continue; }

    $filas[] = [
        'url'     => category_url($c),
        'titulo'  => 'Comparativa que falta: ' . $c['name'],
        'dias'    => 0,
        'puntaje' => 50 + count($prods) * 6,
        'senales' => [count($prods) . ' productos sin comparativa: ' . implode(', ', $prods)],
    ];
}
// A proposito no se listan resenas faltantes. Una resena del sitio vale
// porque el dueño uso el producto, y eso no se genera: se vive. Las resenas
// existentes si aparecen arriba, como mantenimiento.
usort($filas, fn($x, $y) => $y['puntaje'] <=> $x['puntaje']);
$secciones['huecos'] = $filas;

// ------------------------------------------------------------------ salida
if ($seccion !== null) {
    if (!isset($secciones[$seccion])) {
        fwrite(STDERR, "Seccion desconocida: $seccion\n");
        fwrite(STDERR, 'Validas: ' . implode(', ', array_keys($secciones)) . "\n");
        exit(2);
    }
    $secciones = [$seccion => $secciones[$seccion]];
}

if ($jsonOut) {
    if ($top > 0) {
        foreach ($secciones as $k => $v) { $secciones[$k] = array_slice($v, 0, $top); }
    }
    echo json_encode($secciones, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), "\n";
    exit(0);
}

foreach ($secciones as $nombre => $filas) {
    echo "\n", strtoupper($nombre), ' — ', count($filas), " candidato(s)\n";
    echo str_repeat('-', 74), "\n";
    if (!$filas) { echo "  nada pendiente\n"; continue; }
    $muestra = $top > 0 ? array_slice($filas, 0, $top) : $filas;
    foreach ($muestra as $i => $f) {
        printf("%2d. [%3d] %s\n", $i + 1, $f['puntaje'], $f['titulo']);
        printf("        %s — %s\n", $f['url'], $f['dias'] === 9999 ? 'sin fecha' : $f['dias'] . ' dias sin tocar');
        foreach ($f['senales'] as $s) { echo "        · $s\n"; }
    }
    if ($top > 0 && count($filas) > $top) {
        echo '        (', count($filas) - $top, " mas, se muestran los $top primeros)\n";
    }
}
echo "\n";
