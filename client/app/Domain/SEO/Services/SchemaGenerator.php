<?php

namespace App\Domain\SEO\Services;

use App\Models\Blog;
use App\Models\SiteSetting;

class SchemaGenerator
{
    /**
     * Generate Organization & ProfessionalService JSON-LD Schema
     */
    public static function organization(): string
    {
        $settings = SiteSetting::first();
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'ProfessionalService',
            'name' => $settings?->site_name ?: 'SutharTech',
            'url' => url('/'),
        ];
        if ($settings?->default_meta_description) {
            $schema['description'] = $settings->default_meta_description;
        }
        if ($settings?->location) {
            $schema['address'] = ['@type' => 'PostalAddress', 'addressLocality' => $settings->location];
        }
        if ($settings?->contact_email) {
            $schema['contactPoint'] = ['@type' => 'ContactPoint', 'email' => $settings->contact_email, 'contactType' => 'customer support'];
        }
        if ($settings?->social_links) {
            $schema['sameAs'] = array_values($settings->social_links);
        }

        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    /**
     * Generate Service Page JSON-LD Schema
     */
    public static function service(string $name, string $description): string
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $name,
            'description' => $description,
            'provider' => [
                '@type' => 'ProfessionalService',
                'name' => SiteSetting::first()?->site_name ?: 'SutharTech',
                'url' => url('/')
            ]
        ];

        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public static function article(Blog $blog): string
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'headline' => $blog->title,
            'description' => $blog->meta_description ?: $blog->excerpt,
            'datePublished' => $blog->published_at?->toAtomString(),
            'dateModified' => $blog->updated_at->toAtomString(),
            'mainEntityOfPage' => route('blog.show', $blog->slug),
        ];
        if ($blog->image_path) {
            $schema['image'] = $blog->image_url;
        }

        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public static function creativeWork(string $name, string $description): string
    {
        return json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'CreativeWork',
            'name' => $name,
            'description' => $description,
        ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
}
