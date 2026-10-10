@extends('layouts.site')

@section('content')
    <div class="agency-page about-page">
        <section class="about-hero px-6 md:px-16">
            <div class="about-hero-shell max-w-7xl mx-auto">
                <div class="about-hero-copy">
                    <span class="agency-eyebrow">About SutharTech</span>
                    <h1 class="about-hero-title">We make complex digital products feel clear.</h1>
                    <p class="about-hero-lead">SutharTech is a senior product engineering partner for teams turning ambitious ideas into dependable software, AI systems, and cloud platforms.</p>
                    <div class="agency-page-actions">
                        <a class="btn-slate-primary" href="{{ route('contact') }}">Start a conversation <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></a>
                        <a class="btn-slate-secondary" href="{{ route('services') }}">Explore services</a>
                    </div>
                    <div class="about-hero-proof" aria-label="SutharTech at a glance">
                        <div><strong>50+</strong><span>Products shaped</span></div>
                        <div><strong>3+</strong><span>Years building</span></div>
                        <div><strong>20+</strong><span>Technologies used</span></div>
                    </div>
                </div>

                <div class="about-hero-team">
                    <div class="about-hero-team-image">
                        <img src="{{ asset('images/suthartech-team-cutout.png') }}" alt="SutharTech team members" fetchpriority="high">
                    </div>
                </div>
            </div>
        </section>

        <section class="agency-section px-6 md:px-16">
            <div class="agency-page-container max-w-7xl mx-auto about-story-grid">
                <div>
                    <span class="agency-eyebrow">Why we exist</span>
                    <h2 class="agency-section-title">Good engineering makes ambitious work feel possible.</h2>
                </div>
                <div class="about-story-copy">
                    <p>We work with founders, product leaders, and growing teams that need more than a quick prototype. Our role is to bring product judgment and technical depth together so every release creates a stronger foundation.</p>
                    <p>From the first conversation to production monitoring, we keep decisions visible, systems maintainable, and the user experience clear.</p>
                    <blockquote>“The best software partnership gives a team more clarity, not more complexity.”</blockquote>
                </div>
            </div>
        </section>

        <section class="agency-section agency-section-muted px-6 md:px-16">
            <div class="agency-page-container max-w-7xl mx-auto">
                <div class="agency-section-heading">
                    <span class="agency-eyebrow">Our principles</span>
                    <h2 class="agency-section-title">The way we build together.</h2>
                </div>
                <div class="agency-principles-grid">
                    @foreach ($principles as $principle)
                        <article class="agency-principle">
                            <span>{{ $principle['number'] }}</span>
                            <h3>{{ $principle['title'] }}</h3>
                            <p>{{ $principle['description'] }}</p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="agency-section agency-section-muted px-6 md:px-16">
            <div class="agency-page-container max-w-7xl mx-auto about-collaboration-layout">
                <div>
                    <span class="agency-eyebrow">How we work together</span>
                    <h2 class="agency-section-title">A partnership model designed to keep momentum visible.</h2>
                    <p class="agency-section-description">The engagement can start with a focused product sprint or grow into an embedded engineering partnership. The working rhythm stays clear either way.</p>
                </div>
                <ol class="about-collaboration-grid">
                    @foreach ($collaborationSteps as $step)
                        <li>
                            <span>{{ $step['number'] }}</span>
                            <div>
                                <h3>{{ $step['title'] }}</h3>
                                <p>{{ $step['description'] }}</p>
                            </div>
                        </li>
                    @endforeach
                </ol>
            </div>
        </section>

        <section class="agency-section px-6 md:px-16">
            <div class="agency-page-container max-w-7xl mx-auto agency-proof-strip">
                <div><strong>50+</strong><span>Projects shaped</span></div>
                <div><strong>3+</strong><span>Years building</span></div>
                <div><strong>20+</strong><span>Technologies used</span></div>
                <div><strong>Global</strong><span>Client partnerships</span></div>
            </div>
        </section>

        <section class="agency-section about-team-section px-6 md:px-16">
            <div class="agency-page-container max-w-7xl mx-auto">
                <div class="agency-section-heading">
                    <span class="agency-eyebrow">Meet the team</span>
                    <h2 class="agency-section-title">The people behind the products we build.</h2>
                    <p class="agency-section-description">A small senior team bringing product thinking, engineering depth, and production care to every engagement.</p>
                </div>
                <div class="agency-team-member-grid agency-team-member-grid-wide">
                    @foreach ($teamMembers as $member)
                        <x-team-card :member="$member" />
                    @endforeach
                </div>
            </div>
        </section>

        <section class="agency-cta-section px-6 md:px-16">
            <div class="agency-page-container max-w-7xl mx-auto agency-cta-panel">
                <div>
                    <span class="agency-eyebrow">Ready when you are</span>
                    <h2>Bring the next version of your product into focus.</h2>
                </div>
                <a class="btn-slate-primary" href="{{ route('contact') }}">Discuss your project <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></a>
            </div>
        </section>
    </div>
@endsection
