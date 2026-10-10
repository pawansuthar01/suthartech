@extends('layouts.site')

@push('head')
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
@endpush

@section('content')
    <div class="agency-page contact-page">
        <!-- 1. IMMERSIVE CENTERED CONTACT HERO -->
        <section class="contact-hero-immersive px-6 md:px-16">
            <!-- Ambient 3D Glass Orbs & Cubes -->
            <div class="pointer-events-none absolute top-10 left-12 md:top-14 md:left-24 w-10 h-10 md:w-14 md:h-14 rounded-full glass-orb opacity-40 animate-float-slow z-0"></div>
            <div class="pointer-events-none absolute top-12 right-12 md:top-16 md:right-28 w-10 h-10 md:w-16 md:h-16 glass-cube opacity-30 animate-float-delayed rotate-12 z-0"></div>
            <div class="pointer-events-none absolute bottom-8 left-16 md:bottom-12 md:left-36 w-8 h-8 md:w-10 md:h-10 rounded-full glass-orb opacity-35 animate-float-reverse z-0"></div>

            <div class="contact-hero-wrapper">
                <span class="contact-hero-pill-badge">
                    <span class="material-symbols-outlined text-sm text-slate-700" aria-hidden="true">mail</span>
                    <span>Get in Touch</span>
                </span>

                <h1 class="contact-hero-title-main">
                    Let’s build something <span class="text-highlight">remarkable</span>.
                </h1>

                <p class="contact-hero-subtitle-main">
                    Have an ambitious product in mind, need senior engineering capacity, or want to discuss an AI or cloud architecture? We’d love to hear from you.
                </p>

                <div class="contact-hero-quick-actions">
                    <a class="btn-slate-primary" href="#contact-form-section">
                        <span>Send a Project Brief</span>
                        <span class="material-symbols-outlined text-sm" aria-hidden="true">arrow_downward</span>
                    </a>
                    <a class="btn-slate-secondary" href="mailto:hello@suthartech.com">
                        <span>Email hello@suthartech.com</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- 2. CONTACT FORM & BRIEF SECTION -->
        <section id="contact-form-section" class="agency-section px-6 md:px-16 pt-12 pb-16 md:pb-24">
            <div class="agency-page-container max-w-7xl mx-auto agency-contact-layout">
                <!-- Left Column: Contact Details -->
                <div class="agency-contact-intro">
                    <span class="agency-eyebrow">Start a project</span>
                    <h2 class="agency-section-title">A clear brief is the best place to begin.</h2>
                    <p>Tell us about your product, team, and goals. We work across full-stack engineering, AI automation, cloud systems, and product experience.</p>
                    <div class="agency-contact-details">
                        <a href="mailto:hello@suthartech.com"><span class="material-symbols-outlined" aria-hidden="true">mail</span>hello@suthartech.com</a>
                        <span><span class="material-symbols-outlined" aria-hidden="true">schedule</span>Direct response from engineering leads</span>
                        <span><span class="material-symbols-outlined" aria-hidden="true">public</span>Working with teams globally</span>
                    </div>
                </div>

                <!-- Right Column: Form Panel -->
                <div class="agency-form-panel">
                    @if (session('success'))
                        <div class="agency-form-success" role="status">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="agency-form-errors" role="alert">
                            <strong>Please check the highlighted details.</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact.store') }}" enctype="multipart/form-data" class="agency-contact-form">
                        @csrf
                        <div class="agency-form-grid">
                            <label><span>Full name *</span><input type="text" name="name" value="{{ old('name') }}" required autocomplete="name"></label>
                            <label><span>Work email *</span><input type="email" name="email" value="{{ old('email') }}" required autocomplete="email"></label>
                            <label><span>Phone</span><input type="tel" name="phone" value="{{ old('phone') }}" autocomplete="tel"></label>
                            <label><span>Company</span><input type="text" name="company" value="{{ old('company') }}" autocomplete="organization"></label>
                            <label class="col-span-full"><span>Project type *</span><select name="project_type" required><option value="">Choose one</option><option value="Web platform" @selected(old('project_type') === 'Web platform')>Web platform</option><option value="Mobile product" @selected(old('project_type') === 'Mobile product')>Mobile product</option><option value="AI or automation" @selected(old('project_type') === 'AI or automation')>AI or automation</option><option value="Cloud or DevOps" @selected(old('project_type') === 'Cloud or DevOps')>Cloud or DevOps</option><option value="Product design" @selected(old('project_type') === 'Product design')>Product design</option></select></label>
                        </div>
                        <label><span>What are you building? *</span><textarea name="message" rows="5" required placeholder="Tell us about the product, current stage, and what you need next.">{{ old('message') }}</textarea></label>
                        <label><span>Attach project brief / specification (Optional)</span><input type="file" name="attachment" accept=".pdf,.doc,.docx,.zip,.png,.jpg,.jpeg,.txt" class="contact-file-input"></label>
                        @include('partials.form-bot-protection')
                        <button type="submit" class="btn-slate-primary">Send project brief <span class="material-symbols-outlined" aria-hidden="true">arrow_forward</span></button>
                    </form>
                </div>
            </div>
        </section>

        @if ($faqs->isNotEmpty())
            @include('partials.faq-section', [
                'faqs' => $faqs,
                'id' => 'contact-faq-title',
                'title' => 'Project questions, answered.',
                'description' => 'Useful details to help you prepare your project brief.',
            ])
        @endif
    </div>
@endsection
