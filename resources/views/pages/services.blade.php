@extends('layouts.site')

@section('content')
    <div class="agency-page">
        <x-page-hero
            eyebrow="Services"
            title="Full-stack product engineering for the next stage."
            description="A focused team for product strategy, software delivery, AI systems, cloud operations, and the details that make production dependable."
        >
            <a class="btn-slate-primary" href="{{ route('contact') }}">Plan your build <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></a>
        </x-page-hero>

        <section class="agency-section px-6 md:px-16">
            <div class="agency-page-container max-w-7xl mx-auto">
                <div class="agency-section-heading">
                    <span class="agency-eyebrow">What we build</span>
                    <h2 class="agency-section-title">The right capability for each product moment.</h2>
                </div>
                <div class="agency-services-grid">
                    @foreach ($services as $service)
                        <x-service-card :service="$service" />
                    @endforeach
                </div>
            </div>
        </section>

        <section class="agency-section agency-section-muted px-6 md:px-16">
            <div class="agency-page-container max-w-7xl mx-auto agency-split-section">
                <div>
                    <span class="agency-eyebrow">A practical engagement</span>
                    <h2 class="agency-section-title">From first brief to production confidence.</h2>
                </div>
                <ol class="agency-process-list">
                    <li><span>01</span><div><h3>Discover</h3><p>Align on users, outcomes, constraints, and the smallest valuable first release.</p></div></li>
                    <li><span>02</span><div><h3>Design</h3><p>Shape the information architecture, experience, and technical plan before the build accelerates.</p></div></li>
                    <li><span>03</span><div><h3>Build</h3><p>Ship in small, testable increments with clear communication and production-minded decisions.</p></div></li>
                    <li><span>04</span><div><h3>Scale</h3><p>Improve performance, observability, security, and team confidence after launch.</p></div></li>
                </ol>
            </div>
        </section>

        <section class="agency-section px-6 md:px-16">
            <div class="agency-page-container max-w-7xl mx-auto agency-technology-panel">
                <div>
                    <span class="agency-eyebrow">Technology across the stack</span>
                    <h2 class="agency-section-title">Modern tools, chosen for the work.</h2>
                </div>
                <div class="agency-tag-list agency-tag-list-large">
                    @foreach (config('agency.technologies', []) as $technology)
                        <span>{{ $technology }}</span>
                    @endforeach
                </div>
            </div>
        </section>
    </div>
@endsection
