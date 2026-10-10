<?php

namespace App\Domain\SEO\Services;

use App\Models\Blog;
use App\Models\Certification;
use App\Models\Project;
use App\Models\Service;
use App\Models\SitePage;
use App\Models\Testimonial;

class SitemapGenerator
{
    public static function generate(): string
    {
        $baseUrl = url('/');
        $today = now()->toDateString();
        $urls = [];
        $urls[] = ['loc' => $baseUrl, 'priority' => '1.0', 'changefreq' => 'weekly'];

        foreach (['about', 'contact'] as $slug) {
            if (SitePage::published()->where('slug', $slug)->exists()) {
                $urls[] = ['loc' => $baseUrl.'/'.$slug, 'priority' => '0.8', 'changefreq' => 'monthly'];
            }
        }

        foreach (SitePage::published()->whereNotIn('slug', ['home', 'about', 'contact', 'services', 'projects', 'testimonials', 'blogs', 'certifications'])->get(['slug', 'updated_at']) as $page) {
            $urls[] = ['loc' => $baseUrl.'/'.$page->slug, 'priority' => '0.6', 'changefreq' => 'monthly', 'lastmod' => $page->updated_at->toDateString()];
        }

        foreach ([
            [Service::class, '/services', 0.9, 'monthly'],
            [Project::class, '/projects', 0.9, 'monthly'],
            [Blog::class, '/blogs', 0.8, 'weekly'],
            [Certification::class, '/certifications', 0.6, 'monthly'],
        ] as [$model, $indexPath, $priority, $frequency]) {
            $records = $model::published()->get(['slug', 'updated_at']);
            if ($records->isNotEmpty()) {
                $urls[] = ['loc' => $baseUrl.$indexPath, 'priority' => (string) $priority, 'changefreq' => $frequency];
            }
            foreach ($records as $record) {
                $prefix = match ($model) {
                    Service::class => '/services/',
                    Project::class => '/projects/',
                    Blog::class => '/blog/',
                    default => '/certifications/',
                };
                $urls[] = ['loc' => $baseUrl.$prefix.$record->slug, 'priority' => '0.7', 'changefreq' => $frequency, 'lastmod' => $record->updated_at->toDateString()];
            }
        }

        if (Testimonial::published()->exists()) {
            $urls[] = ['loc' => $baseUrl.'/testimonials', 'priority' => '0.7', 'changefreq' => 'monthly'];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n<urlset xmlns=\"https://www.sitemaps.org/schemas/sitemap/0.9\">\n";
        foreach ($urls as $item) {
            $location = htmlspecialchars($item['loc'], ENT_XML1 | ENT_COMPAT, 'UTF-8');
            $xml .= "  <url>\n    <loc>{$location}</loc>\n    <lastmod>".($item['lastmod'] ?? $today)."</lastmod>\n";
            $xml .= "    <changefreq>{$item['changefreq']}</changefreq>\n    <priority>{$item['priority']}</priority>\n  </url>\n";
        }

        return $xml.'</urlset>';
    }
}
