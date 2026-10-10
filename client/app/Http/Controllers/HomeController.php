<?php

namespace App\Http\Controllers;

use App\Domain\SEO\Services\SeoManager;
use App\Models\Project;
use App\Models\Faq;
use App\Models\Service;

class HomeController extends Controller
{
    /**
     * Display SutharTech Agency Homepage
     */
    public function index()
    {
        $seo = SeoManager::build([
            'title' => 'SutharTech - Engineering Digital Products That Move Businesses Forward',
            'description' => 'We partner with startups and forward-thinking businesses to scale through robust software, intelligent AI integrations, seamless automation, and scalable cloud infrastructure.'
        ]);

        $projects = Project::published()
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->get();
        $faqs = Faq::published()->get();

        $iconAccents = ['agency-icon-blue', 'agency-icon-pink', 'agency-icon-sky', 'agency-icon-slate'];
        $capabilities = Service::published()->orderBy('sort_order')->get()->values()->map(
            fn (Service $service, int $index) => [
                'icon' => $service->icon ?: 'design_services',
                'accent' => $iconAccents[$index % count($iconAccents)],
                'title' => $service->title,
                'slug' => $service->slug,
                'description' => $service->description,
            ]
        );

        return view('home', compact('seo', 'capabilities', 'projects', 'faqs'));
    }
}
