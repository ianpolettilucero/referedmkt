<?php
namespace Core;

/**
 * Parser de front-matter para los archivos de content/.
 *
 * Formato: un bloque delimitado por --- al inicio del archivo, seguido del
 * cuerpo en Markdown.
 *
 *     ---
 *     title: Guia de ciberseguridad
 *     type: guide
 *     products: [bitdefender, 1password]
 *     pros:
 *       - Deteccion alta
 *       - Bajo consumo
 *     verdict: |
 *       Texto multilinea que preserva
 *       los saltos de linea.
 *     ---
 *     ## Cuerpo markdown
 *
 * Subconjunto de YAML soportado, deliberadamente chico para que el parseo
 * sea predecible (no dependemos de ext-yaml ni de Composer):
 *
 *   - Escalares: `key: value`
 *   - Comillas: `key: "a: b"` / `key: 'texto'` (permite : y # adentro)
 *   - Listas inline: `key: [a, b, c]` y `key: []`
 *   - Listas en bloque: `key:` + lineas `  - item`
 *   - Texto multilinea: `key: |` (preserva saltos) y `key: >` (los colapsa)
 *   - Comentarios: `# ...` en linea propia, o al final de un valor sin comillas
 *   - Tipos: true/false/yes/no -> bool, null/~/vacio -> null, numeros -> int/float
 *
 * NO soporta: mapas anidados, anclas, multi-documento, listas de objetos.
 * Si necesitas algo de eso, el dato probablemente no va en front-matter.
 *
 * Ante una linea que no matchea ninguna forma conocida se lanza
 * InvalidArgumentException con el numero de linea: preferimos romper el build
 * antes que publicar un articulo con metadata silenciosamente perdida.
 */
final class FrontMatter
{
    private const DELIMITER = '---';

    /**
     * Separa front-matter y cuerpo.
     *
     * @return array{data: array<string, mixed>, body: string}
     * @throws \InvalidArgumentException si el bloque esta abierto o mal formado
     */
    public static function parse(string $raw): array
    {
        $text = str_replace(["\r\n", "\r"], "\n", $raw);

        // Sin front-matter: todo es cuerpo.
        if (strncmp(ltrim($text, "\xEF\xBB\xBF"), self::DELIMITER . "\n", 4) !== 0) {
            return ['data' => [], 'body' => trim($text)];
        }

        $text  = ltrim($text, "\xEF\xBB\xBF");
        $lines = explode("\n", $text);
        array_shift($lines); // el --- de apertura

        $block = [];
        $closed = false;
        while ($lines) {
            $line = array_shift($lines);
            if (rtrim($line) === self::DELIMITER) {
                $closed = true;
                break;
            }
            $block[] = $line;
        }

        if (!$closed) {
            throw new \InvalidArgumentException('Front-matter sin cerrar: falta el --- final.');
        }

        return [
            'data' => self::parseBlock($block),
            'body' => trim(implode("\n", $lines)),
        ];
    }

    /**
     * @param string[] $lines
     * @return array<string, mixed>
     */
    private static function parseBlock(array $lines): array
    {
        $data = [];
        $n = count($lines);

        for ($i = 0; $i < $n; $i++) {
            $line = $lines[$i];

            // Lineas vacias y comentarios.
            if (trim($line) === '' || preg_match('/^\s*#/', $line)) {
                continue;
            }

            if (!preg_match('/^([A-Za-z_][A-Za-z0-9_-]*)\s*:(.*)$/', $line, $m)) {
                // +2: el indice arranca en 0 y la linea 1 es el --- de apertura.
                throw new \InvalidArgumentException(
                    'Front-matter invalido en la linea ' . ($i + 2) . ': ' . trim($line)
                );
            }

            $key = $m[1];
            $rest = trim($m[2]);

            // Bloque literal (|) o plegado (>).
            if ($rest === '|' || $rest === '>' || $rest === '|-' || $rest === '>-') {
                $fold  = ($rest[0] === '>');
                $chomp = str_ends_with($rest, '-');
                [$value, $i] = self::readIndentedBlock($lines, $i, $fold, $chomp);
                $data[$key] = $value;
                continue;
            }

            // Clave sin valor: puede abrir una lista en bloque ("  - item") o
            // un mapa de un nivel ("  clave: valor"). Si no es ninguno, es null.
            if ($rest === '') {
                [$items, $next] = self::readBlockList($lines, $i);
                if ($items !== null) {
                    $data[$key] = $items;
                    $i = $next;
                    continue;
                }
                [$map, $next] = self::readBlockMap($lines, $i);
                if ($map !== null) {
                    $data[$key] = $map;
                    $i = $next;
                    continue;
                }
                $data[$key] = null;
                continue;
            }

            $data[$key] = self::scalar($rest);
        }

        return $data;
    }

    /**
     * Lee las lineas indentadas que siguen a `key: |` o `key: >`.
     *
     * @param string[] $lines
     * @return array{0:string, 1:int} [valor, indice de la ultima linea consumida]
     */
    private static function readIndentedBlock(array $lines, int $start, bool $fold, bool $chomp): array
    {
        $n = count($lines);
        $buf = [];
        $indent = null;
        $i = $start;

        while ($i + 1 < $n) {
            $next = $lines[$i + 1];

            if (trim($next) === '') {
                // Una linea vacia solo corta el bloque si lo que sigue no esta indentado.
                $lookahead = $i + 2;
                while ($lookahead < $n && trim($lines[$lookahead]) === '') { $lookahead++; }
                if ($lookahead >= $n || !preg_match('/^\s+\S/', $lines[$lookahead])) {
                    break;
                }
                $buf[] = '';
                $i++;
                continue;
            }

            if (!preg_match('/^(\s+)\S/', $next, $im)) {
                break;
            }
            if ($indent === null) {
                $indent = strlen($im[1]);
            }
            $buf[] = substr($next, min($indent, strlen($im[1])));
            $i++;
        }

        $value = $fold
            ? trim(preg_replace('/\s*\n\s*/', ' ', implode("\n", $buf)) ?? '')
            : implode("\n", $buf);

        if ($chomp || $fold) {
            $value = rtrim($value);
        } else {
            $value = rtrim($value, "\n");
        }

        return [$value, $i];
    }

    /**
     * Lee una lista en bloque ("  - item") que sigue a una clave sin valor.
     *
     * @param string[] $lines
     * @return array{0:array<int,mixed>|null, 1:int} [items o null, ultimo indice consumido]
     */
    private static function readBlockList(array $lines, int $start): array
    {
        $n = count($lines);
        $items = [];
        $i = $start;

        while ($i + 1 < $n) {
            $next = $lines[$i + 1];
            if (trim($next) === '' || preg_match('/^\s*#/', $next)) {
                // Blancos y comentarios intercalados no cortan la lista, pero
                // tampoco la abren.
                if ($items === []) { break; }
                $i++;
                continue;
            }
            if (!preg_match('/^\s*-\s+(.*)$/', $next, $m)) {
                break;
            }
            $items[] = self::scalar(trim($m[1]));
            $i++;
        }

        return [$items === [] ? null : $items, $i];
    }

    /**
     * Lee un mapa de un nivel ("  clave: valor") que sigue a una clave sin
     * valor. Es lo que usa `specs:` en los productos. Un solo nivel: si hace
     * falta anidar mas, el dato no va en front-matter.
     *
     * Las claves del mapa son libres (pueden llevar espacios y acentos: son
     * etiquetas que se muestran tal cual en la ficha del producto).
     *
     * @param string[] $lines
     * @return array{0:array<string,mixed>|null, 1:int}
     */
    private static function readBlockMap(array $lines, int $start): array
    {
        $n = count($lines);
        $map = [];
        $i = $start;

        while ($i + 1 < $n) {
            $next = $lines[$i + 1];
            if (trim($next) === '' || preg_match('/^\s*#/', $next)) {
                if ($map === []) { break; }
                $i++;
                continue;
            }
            if (!preg_match('/^\s+([^:#][^:]*?)\s*:\s*(.*)$/u', $next, $m)) {
                break;
            }
            $map[trim($m[1])] = self::scalar(trim($m[2]));
            $i++;
        }

        return [$map === [] ? null : $map, $i];
    }

    /**
     * Convierte un escalar de texto al tipo PHP correspondiente.
     *
     * @return mixed
     */
    private static function scalar(string $raw)
    {
        $raw = trim($raw);

        if ($raw === '' || $raw === '~' || strcasecmp($raw, 'null') === 0) {
            return null;
        }

        // Strings entre comillas: se toman literales (sin strip de comentarios).
        $len = strlen($raw);
        if ($len >= 2) {
            $q = $raw[0];
            if (($q === '"' || $q === "'") && $raw[$len - 1] === $q) {
                $inner = substr($raw, 1, -1);
                return $q === '"'
                    ? str_replace(['\\"', '\\n', '\\\\'], ['"', "\n", '\\'], $inner)
                    : str_replace("''", "'", $inner);
            }
        }

        // Lista inline [a, b, c]
        if ($raw[0] === '[' && substr($raw, -1) === ']') {
            $inner = trim(substr($raw, 1, -1));
            if ($inner === '') { return []; }
            return array_map(
                fn($p) => self::scalar(trim($p)),
                self::splitInline($inner)
            );
        }

        // Comentario al final de un valor sin comillas: " valor # nota".
        if (($hash = strpos($raw, ' #')) !== false) {
            $raw = rtrim(substr($raw, 0, $hash));
        }

        if (in_array(strtolower($raw), ['true', 'yes', 'on'], true))  { return true; }
        if (in_array(strtolower($raw), ['false', 'no', 'off'], true)) { return false; }

        if (preg_match('/^-?\d+$/', $raw)) {
            // Fuera del rango de int nos quedamos con el string (ej. IDs largos).
            $asInt = (int)$raw;
            return (string)$asInt === $raw ? $asInt : $raw;
        }
        if (preg_match('/^-?\d*\.\d+$/', $raw)) {
            return (float)$raw;
        }

        return $raw;
    }

    /**
     * Corta una lista inline por comas respetando las comillas.
     *
     * @return string[]
     */
    private static function splitInline(string $s): array
    {
        $out = [];
        $buf = '';
        $quote = null;
        $len = strlen($s);

        for ($i = 0; $i < $len; $i++) {
            $c = $s[$i];
            if ($quote !== null) {
                $buf .= $c;
                if ($c === $quote) { $quote = null; }
                continue;
            }
            if ($c === '"' || $c === "'") {
                $quote = $c;
                $buf .= $c;
                continue;
            }
            if ($c === ',') {
                $out[] = $buf;
                $buf = '';
                continue;
            }
            $buf .= $c;
        }
        if (trim($buf) !== '') { $out[] = $buf; }

        return $out;
    }
}
