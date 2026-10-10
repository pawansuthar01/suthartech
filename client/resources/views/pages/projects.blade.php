@extends('layouts.site')

@section('content')
    <div class="agency-page">
        <section class="agency-page-hero projects-page-hero px-6 md:px-16 py-16 md:py-24">
            <div class="agency-page-container max-w-7xl mx-auto projects-page-hero-grid">
                <div class="projects-page-hero-copy">
                    <span class="agency-eyebrow">Projects</span>
                    <h1 class="agency-page-title">Selected work built to move businesses forward.</h1>
                    <p class="agency-page-lead">A sample of the products, platforms, and operating systems we help teams bring to life across commerce, SaaS, AI, and cloud operations.</p>
                    <div class="agency-page-actions">
                        <a class="btn-slate-primary" href="{{ route('contact') }}">Start a project <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></a>
                    </div>
                </div>
            </div>
        </section>

        <section class="agency-section px-6 md:px-16">
            <div class="agency-page-container max-w-7xl mx-auto">
                <div class="agency-section-heading">
                    <span class="agency-eyebrow">Selected work</span>
                    <h2 class="agency-section-title">Different problems. The same production standard.</h2>
                </div>
                <div class="agency-projects-grid">
                    @foreach ($projects as $project)
                        <x-project-card :project="$project" />
                    @endforeach
                </div>
            </div>
        </section>

        <section class="agency-cta-section px-6 md:px-16">
            <div class="agency-page-container max-w-7xl mx-auto agency-cta-panel">
                <div>
                    <span class="agency-eyebrow">Your product is next</span>
                    <h2>Let us turn the hard part into a clear plan.</h2>
                </div>
                <a class="btn-slate-primary" href="{{ route('contact') }}">Talk to the team <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></a>
            </div>
        </section>
    </div>
@endsection
