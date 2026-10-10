@extends('layouts.site')

@section('main-pt', '')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/testimonials.css') }}">
@endpush

@section('content')
    <div class="agency-page testimonials-page">
        <!-- 1. TOP ANIMATED TESTIMONIAL CARDS MARQUEE (4 SCROLLING LINES, FULL WIDTH EDGE-TO-EDGE) -->
        <section class="testimonials-page-marquee">
            <!-- Ambient 3D Glass Orbs & Cubes -->
           
            <div class="marquee-viewport testimonials-viewport">
                <!-- Line 1: Leftward Scroll -->
                <div class="testimonial-track testimonial-track-left">
                    @foreach (array_merge($testimonials, $testimonials) as $item)
                        <article class="testimonial-card">
                            <h3>{{ $item['name'] }}</h3>
                            <span>{{ $item['role'] }}</span>
                            <p>“{{ $item['quote'] }}”</p>
                            <div class="testimonial-stars">★★★★★</div>
                        </article>
                    @endforeach
                </div>

                <!-- Line 2: Rightward Scroll -->
                <div class="testimonial-track-right testimonial-track-offset">
                    @foreach (array_merge(array_reverse($testimonials), array_reverse($testimonials)) as $item)
                        <article class="testimonial-card">
                            <h3>{{ $item['name'] }}</h3>
                            <span>{{ $item['role'] }}</span>
                            <p>“{{ $item['quote'] }}”</p>
                            <div class="testimonial-stars">★★★★★</div>
                        </article>
                    @endforeach
                </div>

                <!-- Line 3: Leftward Scroll (Offset) -->
                <div class="testimonial-track testimonial-track-left testimonial-track-offset">
                    @foreach (array_merge(array_slice($testimonials, 3), array_slice($testimonials, 0, 3), $testimonials) as $item)
                        <article class="testimonial-card">
                            <h3>{{ $item['name'] }}</h3>
                            <span>{{ $item['role'] }}</span>
                            <p>“{{ $item['quote'] }}”</p>
                            <div class="testimonial-stars">★★★★★</div>
                        </article>
                    @endforeach
                </div>

                <!-- Line 4: Rightward Scroll -->
                <div class="testimonial-track-right">
                    @foreach (array_merge($testimonials, $testimonials) as $item)
                        <article class="testimonial-card">
                            <h3>{{ $item['name'] }}</h3>
                            <span>{{ $item['role'] }}</span>
                            <p>“{{ $item['quote'] }}”</p>
                            <div class="testimonial-stars">★★★★★</div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- 2. FULL TESTIMONIALS GRID SECTION -->
        <section class="agency-section pt-4 pb-10 md:pt-6 md:pb-14 px-6 md:px-16">
            <div class="agency-page-container max-w-7xl mx-auto">
                <div class="agency-section-heading mb-6 md:mb-8">
                    <span class="agency-eyebrow">All Stories</span>
                    <h2 class="agency-section-title">Clarity, momentum, and systems teams can trust.</h2>
                </div>
                <div class="agency-testimonials-grid">
                    @foreach ($stories as $testimonial)
                        <x-testimonial-card :testimonial="$testimonial" />
                    @endforeach
                </div>
                <x-public-pagination :paginator="$stories" label="All Stories pages" />
            </div>
        </section>

        <!-- 3. QUOTE HIGHLIGHT & CTA BANNER -->
        <section class="agency-section agency-section-muted py-8 md:py-12 px-6 md:px-16">
            <div class="agency-page-container max-w-7xl mx-auto agency-quote-panel">
                <span class="material-symbols-outlined" aria-hidden="true">format_quote</span>
                <blockquote>“The right partner does not just add capacity. They make the whole product team sharper.”</blockquote>
                <a class="btn-slate-secondary" href="{{ route('contact') }}">Start a conversation</a>
            </div>
        </section>
    </div>
@endsection
