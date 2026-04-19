<?php
/**
 * Slugify ASCII-safe para URLs. Stateless, idempotente.
 */

if (!function_exists('slugify')) {
    function slugify(string $text): string
    {
        $text = mb_strtolower(trim($text), 'UTF-8');
        // Transliteracion basica manual (sin dependencia de intl).
        $map = [
            'á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','ü'=>'u','ñ'=>'n',
            'à'=>'a','è'=>'e','ì'=>'i','ò'=>'o','ù'=>'u',
            'â'=>'a','ê'=>'e','î'=>'i','ô'=>'o','û'=>'u',
            'ä'=>'a','ë'=>'e','ï'=>'i','ö'=>'o','ç'=>'c','ß'=>'ss',
        ];
        $text = strtr($text, $map);
        $text = preg_replace('/[^a-z0-9]+/u', '-', $text);
        $text = trim($text, '-');
        return $text === '' ? 'item' : $text;
    }
}
