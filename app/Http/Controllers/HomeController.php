<?php

namespace App\Http\Controllers;

use App\Domain\SEO\Services\SeoManager;
use Illuminate\Http\Request;

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

        $capabilities = [
            [
                'icon' => 'code_blocks',
                'title' => 'Custom Software Engineering',
                'accent' => 'accent-blue',
                'description' => 'Scalable backend architectures and responsive frontend applications built for high performance and high concurrency.'
            ],
            [
                'icon' => 'smart_toy',
                'title' => 'AI Integrations & Automation',
                'accent' => 'accent-pink',
                'description' => 'Custom AI agents, LLM workflows, automated data pipelines, and intelligent business process automation.'
            ],
            [
                'icon' => 'cloud_sync',
                'title' => 'Cloud Infrastructure & DevOps',
                'accent' => 'tertiary-fixed-dim',
                'description' => 'Secure, resilient cloud deployments, Kubernetes orchestration, CI/CD pipelines, and zero-downtime scalability.'
            ],
            [
                'icon' => 'design_services',
                'title' => 'Product Design (UX/UI)',
                'accent' => 'accent-blue',
                'description' => 'Data-driven user experiences and minimal, refined editorial interfaces that enhance usability and brand authority.'
            ]
        ];

        return view('home', compact('seo', 'capabilities'));
    }
}
