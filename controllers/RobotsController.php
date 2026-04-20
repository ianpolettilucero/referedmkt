<?php
namespace Controllers;

use Core\Site;

/**
 * robots.txt dinamico por sitio.
 */
final class RobotsController
{
    public function index(): void
    {
        $site = Site::current();
        header('Content-Type: text/plain; charset=utf-8');

        $base = 'https://' . $site->domain;

        echo "User-agent: *\n";
        echo "Disallow: /admin/\n";
        echo "Disallow: /go/\n";
        echo "Disallow: /install.php\n";
        echo "\n";
        echo "Sitemap: {$base}/sitemap.xml\n";
        // Hint no-estandar para crawlers de LLMs que miran este campo.
        echo "LLMs-Txt: {$base}/llms.txt\n";
    }
}
