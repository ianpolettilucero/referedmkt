<?php
namespace Core;

/**
 * Extrae pares pregunta/respuesta de la seccion FAQ del markdown de un
 * articulo, para emitir schema.org FAQPage sin pedirle al autor que cargue
 * los datos dos veces.
 *
 * Convencion de autoria (cero campos nuevos que llenar):
 *
 *     ## Preguntas frecuentes
 *     ### ¿Sirve para una empresa de 10 personas?
 *     Si, el plan base cubre hasta 25 endpoints.
 *     ### ¿Cuanto cuesta?
 *     Desde USD 49/ano por equipo.
 *
 * Reglas:
 *   - La seccion arranca en un h2 cuyo texto matchea HEADING_PATTERN y
 *     termina en el proximo h2 (o al final del documento).
 *   - Cada h3 dentro de la seccion es una pregunta; el texto hasta el
 *     proximo h3/h2 es la respuesta.
 *   - Las preguntas sin respuesta se descartan: Google invalida el FAQPage
 *     entero si un item viene vacio.
 *   - El contenido de bloques ``` se ignora (no es prosa de respuesta).
 *
 * Nota: desde agosto 2023 Google muestra rich results de FAQ solo a sitios
 * de gobierno y salud. El markup sigue valiendo para Bing, para los
 * crawlers de LLMs y como estructura semantica — no para un snippet
 * expandido en Google.
 */
final class Faq
{
    /** Titulos de h2 que abren una seccion de FAQ. */
    private const HEADING_PATTERN = '/^(preguntas\s+frecuentes|preguntas\s+comunes|faqs?)\b/iu';

    /** Recorte de respuesta. Google ignora respuestas larguisimas igual. */
    private const MAX_ANSWER_CHARS = 1000;

    /**
     * @return array<int, array{question:string, answer:string}>
     */
    public static function extract(string $markdown): array
    {
        if (trim($markdown) === '') {
            return [];
        }

        $lines = explode("\n", str_replace(["\r\n", "\r"], "\n", $markdown));

        $items    = [];
        $inFaq    = false;
        $inFence  = false;
        $question = null;
        $buffer   = [];

        $flush = static function () use (&$items, &$question, &$buffer): void {
            if ($question !== null) {
                $answer = self::plainText(implode("\n", $buffer));
                if ($answer !== '') {
                    $items[] = ['question' => $question, 'answer' => $answer];
                }
            }
            $question = null;
            $buffer   = [];
        };

        foreach ($lines as $line) {
            // Los fences alternan estado; adentro no interpretamos headings.
            if (preg_match('/^```/', $line)) {
                $inFence = !$inFence;
                if ($inFaq && $question !== null) {
                    $buffer[] = $line;
                }
                continue;
            }
            if ($inFence) {
                if ($inFaq && $question !== null) {
                    $buffer[] = $line;
                }
                continue;
            }

            // h2 -> abre o cierra la seccion FAQ.
            if (preg_match('/^##[ \t]+(.+?)[ \t#]*$/u', $line, $m)) {
                $flush();
                $inFaq = (bool)preg_match(self::HEADING_PATTERN, self::stripInline($m[1]));
                continue;
            }

            if (!$inFaq) {
                continue;
            }

            // h3 -> nueva pregunta.
            if (preg_match('/^###[ \t]+(.+?)[ \t#]*$/u', $line, $m)) {
                $flush();
                $q = self::stripInline($m[1]);
                $question = $q !== '' ? $q : null;
                continue;
            }

            if ($question !== null) {
                $buffer[] = $line;
            }
        }

        $flush();

        return $items;
    }

    /**
     * Quita sintaxis inline de markdown y normaliza espacios.
     */
    private static function stripInline(string $s): string
    {
        $s = preg_replace('/!\[([^\]]*)\]\([^)]*\)/u', '$1', $s); // imagenes -> alt
        $s = preg_replace('/\[([^\]]*)\]\([^)]*\)/u', '$1', $s);  // links -> texto
        $s = preg_replace('/[*_`~]+/u', '', $s);                  // enfasis / code
        return trim(preg_replace('/\s+/u', ' ', $s));
    }

    /**
     * Aplana un bloque markdown a texto plano apto para el campo `text` de
     * una Answer (schema.org acepta texto o HTML limitado; texto es mas seguro).
     */
    private static function plainText(string $md): string
    {
        $flat = [];
        foreach (explode("\n", $md) as $l) {
            $l = preg_replace('/^[ \t]{0,3}(?:[-*+]|\d+[.)])[ \t]+/u', '', $l); // bullets
            $l = preg_replace('/^[ \t]{0,3}>[ \t]?/u', '', $l);                 // blockquote
            $l = preg_replace('/^[ \t]{0,3}\|.*$/u', '', $l);                   // filas de tabla
            $flat[] = $l;
        }

        $text = self::stripInline(implode(' ', $flat));
        if (mb_strlen($text) > self::MAX_ANSWER_CHARS) {
            $text = rtrim(mb_substr($text, 0, self::MAX_ANSWER_CHARS - 1)) . '…';
        }
        return $text;
    }
}
