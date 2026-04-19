<?php
namespace Controllers;

use Core\Site;

/**
 * robots.txt dinamico por sitio. Permite override via setting `robots_txt`.
 */
final class RobotsController
{
    public function index(): void
    {
        $site = Site::current();
        header('Content-Type: text/plain; charset=utf-8');

        echo "User-agent: *\n";
        echo "Disallow: /admin/\n";
        echo "Disallow: /go/\n";
        echo "\n";
        echo "Sitemap: https://{$site->domain}/sitemap.xml\n";
    }
}
