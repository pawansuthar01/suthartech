<?php

namespace App\Http\Controllers;

use App\Domain\SEO\Services\SchemaGenerator;
use App\Domain\SEO\Services\SeoManager;

class AgencyPageController extends Controller
{
    public function about()
    {
        return view('pages.about', [
            'seo' => $this->seo(
                'About SutharTech | Product Engineering Partners',
                'Meet SutharTech, a product engineering agency helping ambitious teams build reliable software, AI systems, and cloud platforms.',
                'About SutharTech, product engineering agency, software development partner, AI engineering team'
            ),
            'principles' => config('agency.principles'),
            'teamMembers' => config('agency.team_members'),
            'collaborationSteps' => config('agency.collaboration_steps'),
        ]);
    }

    public function services()
    {
        $services = $this->servicesData();

        return view('pages.services', [
            'seo' => $this->seo(
                'Software Development Services | SutharTech',
                'Full-stack product engineering, AI automation, cloud infrastructure, and product design for teams building their next stage of growth.',
                'software development services, full-stack engineering, AI automation, cloud infrastructure, product design',
                SchemaGenerator::service('Full-stack product engineering', 'Full-stack software, AI automation, cloud infrastructure, and product design services for ambitious businesses.')
            ),
            'services' => $services,
        ]);
    }

    public function projects()
    {
        return view('pages.projects', [
            'seo' => $this->seo(
                'Selected Projects | SutharTech',
                'Explore selected SutharTech work across SaaS, mobile products, AI platforms, cloud operations, and data-heavy business systems.',
                'SutharTech projects, SaaS case studies, mobile applications, AI platforms, cloud dashboards'
            ),
            'projects' => $this->projectsData(),
        ]);
    }

    public function testimonials()
    {
        return view('pages.testimonials', [
            'seo' => $this->seo(
                'Client Testimonials | SutharTech',
                'Read how founders and product teams use SutharTech to bring clarity, speed, and reliable engineering to their digital products.',
                'SutharTech testimonials, software agency reviews, product engineering client stories'
            ),
            'testimonials' => $this->testimonialsData(),
        ]);
    }

    public function contact()
    {
        return view('pages.contact', [
            'seo' => $this->seo(
                'Contact SutharTech | Start a Project',
                'Tell SutharTech what you are building. We will help you shape the right product, engineering, AI, or cloud path for the next stage.',
                'contact SutharTech, start software project, hire product engineering agency, request project estimate'
            ),
        ]);
    }

    public function servicesData(): array
    {
        return config('agency.services', []);
    }

    public function projectsData(): array
    {
        return config('agency.projects', []);
    }

    public function testimonialsData(): array
    {
        return config('agency.testimonials', []);
    }

    private function seo(string $title, string $description, string $keywords, ?string $schema = null): array
    {
        return SeoManager::build([
            'title' => $title,
            'description' => $description,
            'keywords' => $keywords,
            'og_title' => $title,
            'og_description' => $description,
            'twitter_title' => $title,
            'twitter_description' => $description,
            'schema_json' => $schema ?? SchemaGenerator::organization(),
        ]);
    }
}
