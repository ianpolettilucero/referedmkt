<?php
namespace Core;

/**
 * Parser Markdown minimal XSS-safe. Cobertura suficiente para articulos
 * de review/guia/comparativa sin depender de Composer.
 *
 * Soporta:
 *   - Headings ATX (# .. ######)
 *   - Parrafos
 *   - Bold **x**, italic *x*, inline code `x`
 *   - Links [text](url), imagenes ![alt](src)
 *   - Fenced code blocks ```lang
 *   - Blockquotes >
 *   - Listas ul (- / *) y ol (1.)
 *   - Horizontal rule (---)
 *   - Escape de HTML de input (no permite HTML raw).
 *
 * Para features avanzadas (tablas, footnotes, etc) drop-in Parsedown.
 * Este parser esta pensado para ser un default seguro sin dependencias.
 */
final class Markdown
{
    public static function toHtml(string $markdown): string
    {
        $text = str_replace(["\r\n", "\r"], "\n", $markdown);
        $lines = explode("\n", $text);

        $html = [];
        $i = 0;
        $n = count($lines);

        while ($i < $n) {
            $line = $lines[$i];

            // Fenced code block
            if (preg_match('/^```\s*([a-zA-Z0-9+\-]*)\s*$/', $line, $m)) {
                $lang = $m[1];
                $buf = [];
                $i++;
                while ($i < $n && !preg_match('/^```\s*$/', $lines[$i])) {
                    $buf[] = $lines[$i];
                    $i++;
                }
                $i++; // skip closing fence
                $code = self::escape(implode("\n", $buf));
                $class = $lang ? ' class="language-' . self::escape($lang) . '"' : '';
                $html[] = '<pre><code' . $class . '>' . $code . '</code></pre>';
                continue;
            }

            // Horizontal rule
            if (preg_match('/^(-{3,}|\*{3,}|_{3,})\s*$/', $line)) {
                $html[] = '<hr>';
                $i++;
                continue;
            }

            // Heading
            if (preg_match('/^(#{1,6})\s+(.+?)\s*#*\s*$/', $line, $m)) {
                $level = strlen($m[1]);
                $html[] = "<h$level>" . self::inline($m[2]) . "</h$level>";
                $i++;
                continue;
            }

            // Blockquote (consume lineas consecutivas)
            if (preg_match('/^>\s?(.*)$/', $line, $m)) {
                $buf = [$m[1]];
                $i++;
                while ($i < $n && preg_match('/^>\s?(.*)$/', $lines[$i], $mm)) {
                    $buf[] = $mm[1];
                    $i++;
                }
                $html[] = '<blockquote><p>' . self::inline(implode(' ', $buf)) . '</p></blockquote>';
                continue;
            }

            // Unordered list
            if (preg_match('/^\s*[-*]\s+(.+)$/', $line, $m)) {
                $items = [$m[1]];
                $i++;
                while ($i < $n && preg_match('/^\s*[-*]\s+(.+)$/', $lines[$i], $mm)) {
                    $items[] = $mm[1];
                    $i++;
                }
                $html[] = '<ul>' . implode('', array_map(
                    fn($it) => '<li>' . self::inline($it) . '</li>',
                    $items
                )) . '</ul>';
                continue;
            }

            // Ordered list
            if (preg_match('/^\s*\d+\.\s+(.+)$/', $line, $m)) {
                $items = [$m[1]];
                $i++;
                while ($i < $n && preg_match('/^\s*\d+\.\s+(.+)$/', $lines[$i], $mm)) {
                    $items[] = $mm[1];
                    $i++;
                }
                $html[] = '<ol>' . implode('', array_map(
                    fn($it) => '<li>' . self::inline($it) . '</li>',
                    $items
                )) . '</ol>';
                continue;
            }

            // Blank line
            if (trim($line) === '') {
                $i++;
                continue;
            }

            // Paragraph (junta lineas hasta una blank line o bloque reconocible)
            $buf = [$line];
            $i++;
            while ($i < $n) {
                $next = $lines[$i];
                if (trim($next) === '') { break; }
                if (preg_match('/^(#{1,6}\s|```|>\s?|\s*[-*]\s+|\s*\d+\.\s+|(-{3,}|\*{3,}|_{3,})\s*$)/', $next)) {
                    break;
                }
                $buf[] = $next;
                $i++;
            }
            $html[] = '<p>' . self::inline(implode(' ', $buf)) . '</p>';
        }

        return implode("\n", $html);
    }

    /**
     * Parsing inline: escape primero, luego re-inyectar formatos soportados.
     */
    private static function inline(string $text): string
    {
        // Escape HTML del input crudo.
        $s = self::escape($text);

        // Imagenes ![alt](src) -- antes que links.
        $s = preg_replace_callback(
            '/!\[([^\]]*)\]\(([^)\s]+)(?:\s+"([^"]*)")?\)/',
            function ($m) {
                $alt  = $m[1];
                $src  = self::safeUrl($m[2]);
                $title = isset($m[3]) ? ' title="' . $m[3] . '"' : '';
                return '<img src="' . $src . '" alt="' . $alt . '"' . $title . ' loading="lazy">';
            },
            $s
        );

        // Links [text](url)
        $s = preg_replace_callback(
            '/\[([^\]]+)\]\(([^)\s]+)(?:\s+"([^"]*)")?\)/',
            function ($m) {
                $txt  = $m[1];
                $href = self::safeUrl($m[2]);
                $title = isset($m[3]) ? ' title="' . $m[3] . '"' : '';
                $rel = str_starts_with($m[2], 'http') ? ' rel="nofollow noopener" target="_blank"' : '';
                return '<a href="' . $href . '"' . $title . $rel . '>' . $txt . '</a>';
            },
            $s
        );

        // Bold **x**
        $s = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $s);
        // Italic *x* (no-greedy)
        $s = preg_replace('/(?<!\*)\*(?!\s)([^*\n]+?)(?<!\s)\*(?!\*)/', '<em>$1</em>', $s);
        // Inline code `x`
        $s = preg_replace('/`([^`]+)`/', '<code>$1</code>', $s);

        return $s;
    }

    private static function escape(string $s): string
    {
        return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Permite solo schemes seguros para href/src. javascript: y data: bloqueados.
     */
    private static function safeUrl(string $url): string
    {
        $url = trim($url);
        if (preg_match('/^(javascript|data|vbscript):/i', $url)) {
            return '#';
        }
        return self::escape($url);
    }
}
