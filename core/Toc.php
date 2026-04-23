<?php
namespace Core;

/**
 * Post-procesa HTML renderizado de Markdown para:
 *   1. Agregar id="..." a cada h2/h3 (slug del texto) → anchors scrollables.
 *   2. Extraer la estructura de headings para renderizar un TOC nav.
 *
 * Diseñado para ser stateless. No toca Markdown.php (separation of concerns:
 * el parser hace markdown, este hace el enriquecimiento DOM).
 */
final class Toc
{
    /** Umbral minimo de headings para que valga la pena mostrar el TOC. */
    public const MIN_HEADINGS = 3;

    /**
     * Procesa HTML, agrega IDs a h2/h3 y devuelve [html_modificado, toc_items].
     *
     * @return array{html:string, items:array<int,array{level:int,id:string,text:string}>}
     */
    public static function process(string $html): array
    {
        if ($html === '') {
            return ['html' => $html, 'items' => []];
        }

        $items = [];
        $usedIds = [];

        // Procesamos solo h2 y h3 (h1 lo usa el titulo del articulo, no debe
        // duplicarse; h4+ es demasiado granular para el TOC).
        $html = preg_replace_callback(
            '#<(h[23])([^>]*)>(.*?)</\1>#is',
            static function ($m) use (&$items, &$usedIds) {
                $tag   = strtolower($m[1]);              // h2 | h3
                $attrs = $m[2];
                $inner = $m[3];

                // Si ya tiene id manual, respetarlo
                $id = null;
                if (preg_match('/\bid=["\']([^"\']+)["\']/i', $attrs, $idm)) {
                    $id = $idm[1];
                } else {
                    // Slug del texto (strip de tags inline)
                    $text = trim(strip_tags($inner));
                    if ($text === '') { return $m[0]; }
                    $base = slugify($text);
                    $id   = $base;
                    // Desambiguacion: si ya usamos este id, agregar -2, -3, etc.
                    $n = 2;
                    while (isset($usedIds[$id])) {
                        $id = $base . '-' . $n++;
                    }
                    // Inyectar id al tag
                    $attrs = rtrim($attrs) . ' id="' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '"';
                }
                $usedIds[$id] = true;

                $items[] = [
                    'level' => $tag === 'h2' ? 2 : 3,
                    'id'    => $id,
                    'text'  => trim(strip_tags($inner)),
                ];

                return '<' . $tag . $attrs . '>' . $inner . '</' . $tag . '>';
            },
            $html
        );

        return ['html' => (string)$html, 'items' => $items];
    }

    /**
     * Conviene mostrar el TOC? Umbral: al menos MIN_HEADINGS.
     *
     * @param array<int,array<string,mixed>> $items
     */
    public static function shouldShow(array $items): bool
    {
        return count($items) >= self::MIN_HEADINGS;
    }
}
