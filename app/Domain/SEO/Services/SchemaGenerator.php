<?php

namespace App\Domain\SEO\Services;

class SchemaGenerator
{
    /**
     * Generate Organization & ProfessionalService JSON-LD Schema
     */
    public static function organization(): string
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'ProfessionalService',
            'name' => 'SutharTech',
            'alternateName' => 'SutharTech Agency',
            'url' => url('/'),
            'logo' => asset('images/hero-software-engineer.png'),
            'image' => asset('images/hero-software-engineer.png'),
            'description' => 'Engineering high-performance digital products, custom software, AI solutions, and scalable cloud systems for modern ambitious enterprises.',
            'address' => [
                '@type' => 'PostalAddress',
                'addressCountry' => 'IN'
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'email' => 'hello@suthartech.com',
                'contactType' => 'customer support',
                'availableLanguage' => ['English', 'Hindi']
            ],
            'sameAs' => [
                'https://linkedin.com/company/suthartech',
                'https://github.com/suthartech',
                'https://x.com/suthartech'
            ],
            'knowsAbout' => [
                'Custom Software Engineering',
                'Artificial Intelligence',
                'Cloud Architecture & DevOps',
                'UX/UI Product Design',
                'Automation Systems'
            ]
        ];

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
                'name' => 'SutharTech',
                'url' => url('/')
            ]
        ];

        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
}
