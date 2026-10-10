<?php

namespace App\Domain\SEO\Services;

use App\Models\SiteSetting;
use App\Models\SitePage;

class SeoManager
{
    /**
     * Build SEO metadata array for views/controllers
     */
    public static function build(array $custom = []): array
    {
        $settings = SiteSetting::first();
        $fixedPage = match (request()->route()?->getName()) {
            'home' => 'home', 'about' => 'about', 'services' => 'services', 'projects' => 'projects',
            'testimonials' => 'testimonials', 'contact' => 'contact', 'blogs' => 'blogs', 'certifications' => 'certifications',
            default => null,
        };
        if ($fixedPage) {
            $page = SitePage::where('slug', $fixedPage)->whereIn('status', ['active', 'published'])->first();
            if ($page) {
                $custom['title'] = $page->meta_title ?: ($custom['title'] ?? $page->title);
                $custom['description'] = $page->meta_description ?: ($custom['description'] ?? $page->hero_description);
            }
        }
        $defaults = [
            'title' => $settings?->default_meta_title ?: 'SutharTech',
            'description' => $settings?->default_meta_description ?: '',
            'keywords' => '',
            'canonical' => url()->current(),
            'robots' => 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
            'og_type' => 'website',
            'og_title' => $custom['title'] ?? 'SutharTech',
            'og_description' => $custom['description'] ?? '',
            'og_image' => $settings?->default_og_image ? asset('storage/'.$settings->default_og_image) : '',
            'og_url' => url()->current(),
            'twitter_card' => 'summary',
            'twitter_site' => '',
            'twitter_title' => $custom['title'] ?? 'SutharTech',
            'twitter_description' => $custom['description'] ?? '',
            'twitter_image' => $settings?->default_og_image ? asset('storage/'.$settings->default_og_image) : '',
            'schema_json' => SchemaGenerator::organization(),
        ];

        $seo = array_merge($defaults, $custom);
        $seo['og_title'] = $custom['og_title'] ?? $seo['title'];
        $seo['og_description'] = $custom['og_description'] ?? $seo['description'];
        $seo['twitter_title'] = $custom['twitter_title'] ?? $seo['title'];
        $seo['twitter_description'] = $custom['twitter_description'] ?? $seo['description'];
        $seo['twitter_card'] = $seo['twitter_image'] ? 'summary_large_image' : 'summary';

        return $seo;
    }
}
