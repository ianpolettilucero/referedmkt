<?php
namespace Controllers;

use Core\Content;
use Core\Site;

/**
 * RSS 2.0 feed por sitio. Ultimos 30 articulos publicados.
 */
final class FeedController
{
    public function index(): void
    {
        $site = Site::current();

        $articles = array_slice(Content::publishedArticles(), 0, 30);

        header('Content-Type: application/rss+xml; charset=utf-8');
        $base = 'https://' . $site->domain;
        $now = date(DATE_RSS);

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        // dc: hace falta declararlo o <dc:creator> deja el feed invalido y los
        // lectores lo rechazan entero.
        echo '<rss version="2.0"' . "\n";
        echo '     xmlns:atom="http://www.w3.org/2005/Atom"' . "\n";
        echo '     xmlns:content="http://purl.org/rss/1.0/modules/content/"' . "\n";
        echo '     xmlns:dc="http://purl.org/dc/elements/1.1/">' . "\n";
        echo "  <channel>\n";
        echo "    <title>" . self::xml($site->name) . "</title>\n";
        echo "    <link>" . self::xml($base . '/') . "</link>\n";
        echo "    <atom:link href=\"" . self::xml($base . '/feed.xml') . "\" rel=\"self\" type=\"application/rss+xml\"/>\n";
        echo "    <description>" . self::xml($site->metaDescriptionTemplate ?? $site->name) . "</description>\n";
        echo "    <language>" . self::xml($site->defaultLanguage) . "</language>\n";
        echo "    <lastBuildDate>$now</lastBuildDate>\n";

        foreach ($articles as $a) {
            $url = $base . article_url($a);
            $pub = !empty($a['published_at']) ? date(DATE_RSS, strtotime($a['published_at'])) : $now;
            $html = (string)($a['content_html'] ?? '');
            echo "    <item>\n";
            echo "      <title>" . self::xml($a['title']) . "</title>\n";
            echo "      <link>" . self::xml($url) . "</link>\n";
            echo "      <guid isPermaLink=\"true\">" . self::xml($url) . "</guid>\n";
            echo "      <pubDate>$pub</pubDate>\n";
            if (!empty($a['author_name'])) {
                echo "      <dc:creator><![CDATA[" . $a['author_name'] . "]]></dc:creator>\n";
            }
            if (!empty($a['excerpt'])) {
                echo "      <description>" . self::xml($a['excerpt']) . "</description>\n";
            }
            // Los agregadores usan <category> para agrupar y filtrar. Sin esto
            // todo el feed llega como una sola bolsa, y un lector que solo
            // quiere lo de correo o lo de backup no puede separarlo.
            if (!empty($a['category_name'])) {
                echo "      <category>" . self::xml($a['category_name']) . "</category>\n";
            }
            echo "      <content:encoded><![CDATA[" . str_replace(']]>', ']]&gt;', $html) . "]]></content:encoded>\n";
            echo "    </item>\n";
        }

        echo "  </channel>\n";
        echo "</rss>\n";
    }

    private static function xml(?string $s): string
    {
        return htmlspecialchars((string)$s, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }
}
