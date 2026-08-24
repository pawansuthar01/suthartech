<?php

namespace App\Domain\SEO\Services;

class SeoManager
{
    /**
     * Build SEO metadata array for views/controllers
     */
    public static function build(array $custom = []): array
    {
        $defaults = [
            'title' => 'SutharTech - Engineering Digital Products That Move Businesses Forward',
            'description' => 'SutharTech is a modern 2026 software engineering studio specializing in custom software, AI integrations, automation, and scalable cloud architecture.',
            'keywords' => 'SutharTech, Software Engineering, AI Integrations, Automation, Cloud Architecture, UX UI Design, Web Development',
            'canonical' => url()->current(),
            'robots' => 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1',
            'og_type' => 'website',
            'og_title' => 'SutharTech - Engineering Digital Products',
            'og_description' => 'We partner with startups and forward-thinking businesses to scale through robust software, intelligent AI integrations, and cloud infrastructure.',
            'og_image' => asset('images/hero-software-engineer.png'),
            'og_url' => url()->current(),
            'twitter_card' => 'summary_large_image',
            'twitter_site' => '@SutharTech',
            'twitter_title' => 'SutharTech - Engineering Digital Products',
            'twitter_description' => 'Engineering high-performance digital products for modern ambitious businesses.',
            'twitter_image' => asset('images/hero-software-engineer.png'),
            'schema_json' => SchemaGenerator::organization(),
        ];

        return array_merge($defaults, $custom);
    }
}
