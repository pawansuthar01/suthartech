<?php

namespace App\Http\Controllers;

use App\Domain\SEO\Services\SitemapGenerator;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Render dynamic XML sitemap for SEO search engines
     */
    public function sitemap(): Response
    {
        $content = SitemapGenerator::generate();
        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }

    /**
     * Render robots.txt file
     */
    public function robots(): Response
    {
        $robots = "User-agent: *\n";
        $robots .= "Allow: /\n\n";
        $robots .= "Disallow: /admin/\nDisallow: /login\n\n";
        $robots .= "Sitemap: " . url('/sitemap.xml') . "\n";

        return response($robots, 200)
            ->header('Content-Type', 'text/plain');
    }
}
