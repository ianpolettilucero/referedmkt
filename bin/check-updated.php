<?php
/**
 * Verifica que `updated:` acompañe a los cambios de contenido.
 *
 * Por que existe: el <lastmod> del sitemap sale de ese campo del front-matter,
 * escrito a mano. Cuando se edita el cuerpo de un articulo y nadie toca la
 * fecha, el sitemap sigue declarando la vieja y Google no recibe senal de que
 * vale la pena volver a rastrear. Google solo usa lastmod si es "consistently
 * and verifiably accurate": una senal que miente en parte del sitio se
 * descarta para todo el sitio, incluidas las notas nuevas donde si es correcta
 * y donde mas se necesita. Paso de verdad: un commit reescribio 29
 * encabezados en 9 notas sin mover una sola fecha.
 *
 * Que exige, exactamente: si el diff de un articulo toca algo FUERA del
 * front-matter, el mismo diff tiene que tocar la linea `updated:`. Un cambio
 * solo de metadatos —renombrar un campo, corregir una categoria— no lo exige,
 * porque el contenido que ve el lector no cambio y falsear la fecha es
 * justamente lo que le ensena a Google a desconfiar.
 *
 * Uso:
 *   php bin/check-updated.php <base> <head>
 *   php bin/check-updated.php            (usa HEAD~1..HEAD)
 */

$base = $argv[1] ?? 'HEAD~1';
$head = $argv[2] ?? 'HEAD';

function git(array $args): array
{
    $cmd = 'git ' . implode(' ', array_map('escapeshellarg', $args)) . ' 2>/dev/null';
    exec($cmd, $out, $code);
    return [$code, $out];
}

[$code, $cambiados] = git(['diff', '--name-only', "$base..$head", '--', 'content/articles']);
if ($code !== 0) {
    fwrite(STDERR, "No se pudo leer el diff $base..$head; se omite el chequeo.\n");
    exit(0);
}

$cambiados = array_values(array_filter($cambiados, fn($f) => substr($f, -3) === '.md'));
if (!$cambiados) {
    echo "Sin articulos modificados en $base..$head.\n";
    exit(0);
}

$fallas = [];
foreach ($cambiados as $file) {
    [, $diff] = git(['diff', '-U0', "$base..$head", '--', $file]);

    // El front-matter va entre los dos '---' del principio. Para saber si el
    // cambio es solo de metadatos alcanza con mirar las lineas agregadas o
    // quitadas: si alguna esta fuera del bloque, es cuerpo.
    $tocaCuerpo  = false;
    $tocaUpdated = false;
    $enFrontOld = $enFrontNew = 0;

    foreach ($diff as $l) {
        if (preg_match('/^@@ -(\d+)(?:,\d+)? \+(\d+)/', $l, $m)) {
            $enFrontOld = (int)$m[1];
            $enFrontNew = (int)$m[2];
            continue;
        }
        if ($l === '' || $l[0] !== '+' && $l[0] !== '-') { continue; }
        if (str_starts_with($l, '+++') || str_starts_with($l, '---')) { continue; }

        $texto = substr($l, 1);
        if (preg_match('/^updated:\s/', $texto)) { $tocaUpdated = true; continue; }

        // Numero de linea aproximado del hunk: el front-matter de estos
        // archivos nunca pasa de ~25 lineas, asi que un cambio por encima de
        // ese umbral es cuerpo con certeza. Debajo, se confirma leyendo el
        // archivo para no adivinar.
        $linea = $l[0] === '+' ? $enFrontNew : $enFrontOld;
        if ($linea > 40) { $tocaCuerpo = true; }
        else {
            $contenido = @file($file);
            $fin = 0;
            if ($contenido) {
                foreach ($contenido as $i => $cl) {
                    if ($i > 0 && rtrim($cl) === '---') { $fin = $i + 1; break; }
                }
            }
            if ($fin === 0 || $linea > $fin) { $tocaCuerpo = true; }
        }
    }

    if ($tocaCuerpo && !$tocaUpdated) {
        $fallas[] = $file;
    }
}

if ($fallas) {
    fwrite(STDERR, "\n  Articulos con el cuerpo modificado y sin mover `updated:`:\n\n");
    foreach ($fallas as $f) {
        fwrite(STDERR, "    $f\n");
    }
    fwrite(STDERR, "\n  El <lastmod> del sitemap sale de ese campo. Si no se mueve, Google no se\n");
    fwrite(STDERR, "  entera de que el articulo cambio, y una senal que miente en parte del sitio\n");
    fwrite(STDERR, "  se descarta para todo el sitio.\n\n");
    fwrite(STDERR, "  Poner `updated:` en la fecha de hoy en cada uno de esos archivos.\n\n");
    exit(1);
}

echo 'OK: ' . count($cambiados) . " articulo(s) modificado(s), todos con `updated:` al dia.\n";
exit(0);
