<?php

namespace App\Http\Controllers;

use App\Domain\SEO\Services\SchemaGenerator;
use App\Domain\SEO\Services\SeoManager;
use App\Models\Project;
use App\Models\Faq;
use App\Models\Service;
use App\Models\Testimonial;

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
        return view('pages.services', [
            'seo' => $this->seo(
                'Software Development Services | SutharTech',
                'Full-stack product engineering, AI automation, cloud infrastructure, and product design for teams building their next stage of growth.',
                'software development services, full-stack engineering, AI automation, cloud infrastructure, product design',
                SchemaGenerator::service('Full-stack product engineering', 'Full-stack software, AI automation, cloud infrastructure, and product design services for ambitious businesses.')
            ),
            'services' => $this->servicesData(),
        ]);
    }

    public function projects()
    {
        $projects = $this->projectsData();
        return view('pages.projects', [
            'seo' => $this->seo(
                'Selected Projects | SutharTech',
                'Explore selected SutharTech work across SaaS, mobile products, AI platforms, cloud operations, and data-heavy business systems.',
                'SutharTech projects, SaaS case studies, mobile applications, AI platforms, cloud dashboards'
            ),
            'projects' => $projects,
        ]);
    }

    public function testimonials()
    {
        $testimonials = $this->testimonialsData();
        $stories = Testimonial::published()->orderBy('sort_order')->paginate(6)->withQueryString();
        $stories->through(fn (Testimonial $testimonial) => [
            'name' => $testimonial->client_name,
            'role' => collect([$testimonial->client_role, $testimonial->company])->filter()->join(', '),
            'quote' => $testimonial->quote,
        ]);

        return view('pages.testimonials', [
            'seo' => $this->seo(
                'Client Testimonials | SutharTech',
                'Read how founders and product teams use SutharTech to bring clarity, speed, and reliable engineering to their digital products.',
                'SutharTech testimonials, software agency reviews, product engineering client stories'
            ),
            'testimonials' => $testimonials,
            'stories' => $stories,
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
            'faqs' => Faq::published('contact')->get(),
        ]);
    }

    public function service(string $slug)
    {
        $service = Service::published()->where('slug', $slug)->firstOrFail();
        return view('pages.service', [
            'service' => $service,
            'seo' => $this->seo(
                $service->meta_title ?: $service->title.' | SutharTech',
                $service->meta_description ?: $service->description,
                $service->title.', SutharTech services',
                SchemaGenerator::service($service->title, $service->description)
            ),
        ]);
    }

    public function servicesData(): array
    {
        $accents = ['agency-icon-blue', 'agency-icon-pink', 'agency-icon-sky', 'agency-icon-slate'];

        return Service::published()->orderBy('sort_order')->get()->values()->map(fn (Service $service, int $index) => [
            'icon' => $service->icon ?: 'design_services',
            'accent' => $accents[$index % count($accents)],
            'title' => $service->title,
            'description' => $service->description,
            'deliverables' => $service->deliverables ?? [],
        ])->all();
    }

    public function projectsData(): array
    {
        return Project::published()->orderBy('sort_order')->get()->map(fn (Project $project) => [
            'slug' => $project->slug,
            'image' => !$project->image_path
                ? 'images/hero-software-engineer.png'
                : (str_starts_with($project->image_path, 'images/') ? $project->image_path : 'storage/'.$project->image_path),
            'category' => $project->category,
            'title' => $project->title,
            'description' => $project->summary,
            'technologies' => $project->technologies ?? [],
        ])->all();
    }

    public function testimonialsData(): array
    {
        return Testimonial::published()->orderBy('sort_order')->get()->map(fn (Testimonial $testimonial) => [
            'name' => $testimonial->client_name,
            'role' => collect([$testimonial->client_role, $testimonial->company])->filter()->join(', '),
            'quote' => $testimonial->quote,
        ])->all();
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
