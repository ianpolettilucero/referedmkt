<?php
namespace Controllers;

use Core\Database;
use Core\Markdown;
use Core\Site;

/**
 * RSS 2.0 feed por sitio. Ultimos 30 articulos publicados.
 */
final class FeedController
{
    public function index(): void
    {
        $site = Site::current();

        $articles = Database::instance()->fetchAll(
            "SELECT a.*, au.name AS author_name
             FROM articles a
             LEFT JOIN authors au ON au.id = a.author_id
             WHERE a.site_id = :site AND a.status = 'published' AND a.published_at <= NOW()
             ORDER BY a.published_at DESC
             LIMIT 30",
            ['site' => $site->id]
        );

        header('Content-Type: application/rss+xml; charset=utf-8');
        $base = 'https://' . $site->domain;
        $now = date(DATE_RSS);

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:content="http://purl.org/rss/1.0/modules/content/">' . "\n";
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
            $html = Markdown::toHtml($a['content'] ?? '');
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
