<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use LogicException;

class ProjectServiceDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (app()->environment('production')) {
            throw new LogicException('Project and service demo content cannot be seeded in production.');
        }

        foreach (config('agency.services', []) as $index => $service) {
            Service::updateOrCreate(['slug' => Str::slug($service['title'])], [
                'title' => $service['title'],
                'icon' => $service['icon'],
                'description' => $service['description'],
                'deliverables' => $service['deliverables'],
                'slug' => Str::slug($service['title']),
                'sort_order' => $index + 1,
                'status' => 'active',
            ]);
        }

        $projects = [
            ['SaaS Operations Dashboard', 'SaaS platform', 'An illustrative operations workspace concept for reviewing service health, incidents, and release activity in one place.', 'images/project-telemetry-dashboard.png', ['Laravel', 'PHP', 'MySQL', 'Dashboards']],
            ['Mobile Commerce Preview', 'Mobile commerce', 'A mobile commerce concept covering product discovery, a focused checkout flow, and order status screens.', 'images/project-mobile-commerce.png', ['React Native', 'Laravel', 'Commerce']],
            ['AI Support Assistant', 'AI product', 'An AI support concept that organizes conversation context and suggests reviewable responses for a human support team.', 'images/project-telemetry-dashboard.png', ['Python', 'LLM integration', 'Workflow design']],
            ['Cloud Cost Monitor', 'Cloud operations', 'A cloud operations concept for grouping infrastructure usage and surfacing cost trends for team review.', 'images/project-mobile-commerce.png', ['Cloud', 'Monitoring', 'Data visualization']],
            ['Healthcare Booking Portal', 'Booking platform', 'A healthcare booking concept for finding services, requesting appointments, and managing booking details.', 'images/project-telemetry-dashboard.png', ['Laravel', 'MySQL', 'Responsive UI']],
            ['Logistics Tracking Platform', 'Logistics software', 'A logistics tracking concept that brings shipment milestones and operational updates into a shared view.', 'images/project-mobile-commerce.png', ['Laravel', 'Maps integration', 'APIs']],
            ['Learning Management Workspace', 'Learning platform', 'A learning workspace concept for organizing courses, learner progress, and instructor updates.', 'images/project-telemetry-dashboard.png', ['PHP', 'MySQL', 'Learning tools']],
            ['Customer Insights Console', 'Analytics software', 'An analytics console concept for bringing customer activity and product signals into a reviewable workspace.', 'images/project-mobile-commerce.png', ['Data modeling', 'Dashboards', 'APIs']],
            ['Secure Document Hub', 'Business software', 'A document management concept with organized access, searchable records, and clear review states.', 'images/project-telemetry-dashboard.png', ['Laravel', 'Access control', 'Search']],
            ['Field Service Mobile App', 'Mobile application', 'A field service concept for assigning work, capturing visit notes, and keeping teams in sync on mobile.', 'images/project-mobile-commerce.png', ['React Native', 'APIs', 'Offline workflows']],
            ['Subscription Billing Portal', 'SaaS platform', 'A billing portal concept for presenting subscription plans, invoices, and account changes clearly.', 'images/project-telemetry-dashboard.png', ['Laravel', 'Billing workflows', 'MySQL']],
            ['Inventory Forecasting Tool', 'Operations software', 'An inventory planning concept for reviewing stock levels, demand signals, and replenishment tasks.', 'images/project-mobile-commerce.png', ['Data modeling', 'Forecasting workflow', 'Dashboards']],
        ];

        foreach ($projects as $index => [$title, $category, $summary, $image, $technologies]) {
            $slug = Str::slug($title);
            Project::updateOrCreate(['slug' => $slug], [
                'title' => $title,
                'category' => 'Concept · '.$category,
                'summary' => 'Concept project: '.$summary,
                'body' => $summary.' This is an illustrative SutharTech concept entry for reviewing the project pages and admin publishing workflow; it does not represent a verified client engagement or claim client results.',
                'image_path' => $image,
                'technologies' => $technologies,
                'project_url' => null,
                'is_featured' => $index < 4,
                'sort_order' => $index + 1,
                'status' => 'active',
                'meta_title' => $title.' Concept Project | SutharTech',
                'meta_description' => 'Illustrative '.$category.' concept by SutharTech. View the project approach and technology outline.',
            ]);
        }
    }
}
