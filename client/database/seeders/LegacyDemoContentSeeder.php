<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\Certification;
use App\Models\Project;
use App\Models\Service;
use App\Models\SitePage;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use LogicException;

class LegacyDemoContentSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            throw new LogicException('Demo content cannot be seeded in production.');
        }

        foreach (config('agency.services', []) as $index => $service) {
            Service::firstOrCreate(['slug' => Str::slug($service['title'])], [
                'title' => $service['title'],
                'icon' => $service['icon'],
                'description' => $service['description'],
                'deliverables' => $service['deliverables'],
                'sort_order' => $index,
                'status' => 'published',
            ]);
        }

        foreach (config('agency.projects', []) as $index => $project) {
            Project::firstOrCreate(['slug' => Str::slug($project['title'])], [
                'title' => $project['title'],
                'category' => $project['category'],
                'summary' => $project['description'],
                'image_path' => $project['image'],
                'technologies' => $project['technologies'],
                'sort_order' => $index,
                'is_featured' => true,
                'status' => 'inactive',
            ]);
        }

        foreach (config('agency.testimonials', []) as $index => $testimonial) {
            Testimonial::firstOrCreate(['client_name' => $testimonial['name']], [
                'client_role' => $testimonial['role'],
                'quote' => $testimonial['quote'],
                'sort_order' => $index,
                'status' => 'inactive',
            ]);
        }

        $previewProjects = [
            'SaaS Operations Dashboard', 'Mobile Commerce Preview', 'AI Support Assistant',
            'Cloud Cost Monitor', 'Healthcare Booking Portal', 'Logistics Tracking Platform',
            'Learning Management Workspace', 'Customer Insights Console', 'Secure Document Hub',
            'Field Service Mobile App', 'Subscription Billing Portal', 'Inventory Forecasting Tool',
        ];
        foreach ($previewProjects as $index => $title) {
            $slug = Str::slug($title);
            Project::updateOrCreate(['slug' => $slug], [
                'title' => $title,
                'category' => ['SaaS Platform', 'Mobile Application', 'AI Product', 'Cloud Software'][$index % 4],
                'summary' => 'Preview project for local website and admin workflow review. Replace with approved client details before production.',
                'body' => "This preview entry demonstrates the complete project detail page for {$title}. It is sample content for local testing only and does not describe a verified client engagement.",
                'image_path' => ['images/project-telemetry-dashboard.png', 'images/project-mobile-commerce.png'][$index % 2],
                'technologies' => ['Laravel', 'PHP', 'MySQL', 'Responsive UI'],
                'project_url' => null,
                'is_featured' => $index < 4,
                'sort_order' => $index + 1,
                'status' => 'active',
                'meta_title' => $title.' | SutharTech Preview',
                'meta_description' => 'Local preview project entry for testing SutharTech project pages.',
            ]);
        }

        $previewBlogs = [
            'Planning a SaaS Product MVP', 'A Practical Guide to Laravel APIs',
            'Designing Better Admin Workflows', 'Building Reliable Cloud Deployments',
            'Where AI Automation Fits in Operations', 'Improving Core Web Performance',
            'Choosing a Scalable Data Model', 'Making Mobile Products Accessible',
            'Security Basics for Business Applications', 'A Checklist for Product Launches',
            'Measuring Useful Product Analytics', 'Maintaining Software After Launch',
        ];
        foreach ($previewBlogs as $index => $title) {
            $slug = Str::slug($title);
            Blog::updateOrCreate(['slug' => $slug], [
                'title' => '[Preview] '.$title,
                'excerpt' => 'Sample article prepared for local review of the blog listing and detail page.',
                'body' => "Preview article for local testing. This sample covers {$title} and exists to exercise the public blog layout and admin publishing workflow. Replace this copy with reviewed editorial content before production.",
                'status' => 'active',
                'published_at' => now()->subDays($index),
                'meta_title' => $title.' | SutharTech Preview',
                'meta_description' => 'Local preview article for testing SutharTech blog pages.',
            ]);
        }

        for ($index = 1; $index <= 36; $index++) {
            Testimonial::updateOrCreate(['client_name' => sprintf('Preview Client %02d', $index)], [
                'client_role' => ['Founder', 'Product Lead', 'Operations Manager', 'Engineering Manager'][$index % 4],
                'company' => sprintf('Preview Company %02d', $index),
                'quote' => 'Sample feedback for local testing only. This is not a real customer statement and must be replaced before production.',
                'sort_order' => $index,
                'status' => 'active',
            ]);
        }

        $issuers = ['Preview Technology Institute', 'Sample Cloud Academy', 'Demo Security Board'];
        for ($index = 1; $index <= 9; $index++) {
            $title = sprintf('Preview Certificate %02d', $index);
            Certification::updateOrCreate(['slug' => Str::slug($title)], [
                'title' => $title,
                'issuer' => $issuers[$index % count($issuers)],
                'credential_id' => sprintf('PREVIEW-CERT-%03d', $index),
                'credential_url' => null,
                'issued_at' => now()->subMonths($index)->toDateString(),
                'expires_at' => null,
                'description' => 'Sample credential for local admin and public page testing only. Not a real certification.',
                'status' => 'draft',
                'sort_order' => $index,
                'meta_title' => $title.' | SutharTech Preview',
                'meta_description' => 'Local preview certification entry for testing.',
            ]);
        }

        Project::where('slug', 'preview-project')->delete();
        Service::where('slug', 'preview-service')->delete();
        Testimonial::where('client_name', 'Preview feedback — replace before publishing')->delete();
        Blog::where('slug', 'preview-article')->delete();
        Certification::where('slug', 'preview-certification')->delete();
        SitePage::whereIn('slug', ['home', 'about', 'services', 'projects', 'testimonials', 'contact'])
            ->where('title', 'like', '%preview only')
            ->delete();
    }
}
