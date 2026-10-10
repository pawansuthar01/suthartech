<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>

    <title>{{ $seo['title'] }}</title>
    @include('partials.seo', ['seo' => $seo])
    @vite('resources/js/app.js')

    <!-- Tailwind CSS & External Stylesheet -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#0F172A",
                        "on-primary": "#FFFFFF",
                        "secondary": "#64748B",
                        "background": "#FAFAF9",
                        "border-subtle": "#E2E8F0",
                        "accent-pink": "#F4C7D7",
                        "accent-blue": "#C7DDF4"
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="{{ asset('css/home.css') }}"/>
</head>
<body class="bg-background text-primary antialiased overflow-x-hidden relative">

    <x-site-background />

    <!-- 1. NAVBAR -->
    <x-site-navbar />

    <main class="pt-20">
        <!-- 2. HERO SECTION (2-COLUMN DESKTOP STYLE: TEXT LEFT, IMAGE RIGHT WITH FLOATING 3D GLASS ORBS & CUBES) -->
        <section class="relative py-12 md:py-24 px-6 md:px-16 max-w-7xl mx-auto">
            <!-- Ambient 3D Glass Orbs & Cubes matching plan/image.png -->
            <div class="pointer-events-none absolute top-8 left-10 md:top-10 md:left-20 w-8 h-8 md:w-10 md:h-10 rounded-full glass-orb opacity-40 animate-float-slow z-0"></div>
            <div class="pointer-events-none absolute top-8 right-10 md:top-10 md:right-20 w-9 h-9 md:w-12 md:h-12 glass-cube opacity-30 animate-float-delayed rotate-12 z-0"></div>
            <div class="pointer-events-none absolute bottom-8 left-2 md:bottom-10 md:left-8 w-14 h-14 md:w-16 md:h-16 rounded-full glass-orb opacity-50 animate-float-reverse z-0"></div>
            <div class="pointer-events-none absolute bottom-8 right-10 md:bottom-12 md:right-24 w-6 h-6 md:w-8 md:h-8 rounded-full glass-orb opacity-30 animate-float-slow z-0"></div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center relative z-10">
                <!-- Left Column: Text & CTAs -->
                <div class="lg:col-span-7 flex flex-col items-start text-left">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-[1.15] mb-6">
                        Bespoke Digital Engineering for Ambitious Enterprises.
                    </h1>

                    <p class="text-base md:text-lg text-slate-600 max-w-xl mb-8 leading-relaxed">
                        We engineer high-performance web platforms, custom AI models, and scalable cloud infrastructure for ambitious startups and industry leaders.
                    </p>

                    <div class="flex flex-wrap items-center gap-4">
                        <a class="btn-slate-primary" href="#contact">
                            Start a Project
                        </a>
                        <a class="btn-slate-secondary" href="#projects">
                            Explore Our Work
                        </a>
                    </div>
                </div>

                <!-- Right Column: Pure Real High-Res Screenshot Image (No Outer Container/Background) -->
                <div class="lg:col-span-5 flex justify-center items-center relative z-10 mt-2 md:mt-0">
                    <img src="{{ asset('images/hero-engineer-cutout.png') }}" 
                         alt="Software engineer building digital products" 
                         class="w-full h-auto max-h-[460px] object-contain object-center hover:scale-[1.01] transition-transform duration-500"/>
                </div>
            </div>
        </section>

        <!-- 3. DUAL LINE LOGO TICKER MARQUEE (BORDERLESS WITH EDGE BLUR MASKS) -->
        <section class="tech-ticker relative py-8 bg-transparent overflow-hidden flex flex-col gap-5">
            <!-- Left & Right Blur Fade Edge Masks -->
            <div class="ticker-edge-mask ticker-edge-left pointer-events-none absolute inset-y-0 left-0 w-24 md:w-44 z-10"></div>
            <div class="ticker-edge-mask ticker-edge-right pointer-events-none absolute inset-y-0 right-0 w-24 md:w-44 z-10"></div>

            <!-- Row 1: Left to Right (Full Stack & Cloud Architecture) -->
            <div class="flex whitespace-nowrap animate-scroll-left items-center gap-10 text-slate-700 font-bold text-xs md:text-sm tracking-wider">
                <div class="flex items-center gap-10 min-w-full justify-around opacity-80 hover:opacity-100 transition-opacity">
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-blue-600">dataset</span> Next.js 14</span>
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-rose-500">code_blocks</span> Laravel 12</span>
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-amber-500">cloud</span> AWS Cloud</span>
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-cyan-600">deployed_code</span> Docker</span>
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-red-500">memory</span> Redis</span>
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-indigo-600">storage</span> PostgreSQL</span>
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-sky-500">smartphone</span> React Native</span>
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-teal-500">palette</span> Tailwind CSS</span>
                </div>
                <div class="flex items-center gap-10 min-w-full justify-around opacity-80 hover:opacity-100 transition-opacity">
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-blue-600">dataset</span> Next.js 14</span>
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-rose-500">code_blocks</span> Laravel 12</span>
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-amber-500">cloud</span> AWS Cloud</span>
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-cyan-600">deployed_code</span> Docker</span>
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-red-500">memory</span> Redis</span>
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-indigo-600">storage</span> PostgreSQL</span>
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-sky-500">smartphone</span> React Native</span>
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-teal-500">palette</span> Tailwind CSS</span>
                </div>
            </div>

            <!-- Row 2: Right to Left (AI/ML, SaaS, Automation & DevOps) -->
            <div class="flex whitespace-nowrap animate-scroll-right items-center gap-10 text-slate-700 font-bold text-xs md:text-sm tracking-wider">
                <div class="flex items-center gap-10 min-w-full justify-around opacity-80 hover:opacity-100 transition-opacity">
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-amber-500">terminal</span> Python FastAPI</span>
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-emerald-500">psychology</span> OpenAI & LLMs</span>
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-purple-500">auto_awesome</span> AI Automation</span>
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-blue-500">rocket_launch</span> SaaS Platforms</span>
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-pink-500">hub</span> GraphQL APIs</span>
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-blue-600">view_in_ar</span> Kubernetes</span>
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-emerald-600">database</span> MongoDB</span>
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-cyan-500">sync</span> CI/CD Pipeline</span>
                </div>
                <div class="flex items-center gap-10 min-w-full justify-around opacity-80 hover:opacity-100 transition-opacity">
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-amber-500">terminal</span> Python FastAPI</span>
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-emerald-500">psychology</span> OpenAI & LLMs</span>
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-purple-500">auto_awesome</span> AI Automation</span>
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-blue-500">rocket_launch</span> SaaS Platforms</span>
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-pink-500">hub</span> GraphQL APIs</span>
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-blue-600">view_in_ar</span> Kubernetes</span>
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-emerald-600">database</span> MongoDB</span>
                    <span class="flex items-center gap-2"><span class="material-symbols-outlined text-cyan-500">sync</span> CI/CD Pipeline</span>
                </div>
            </div>
        </section>

        <!-- 4. SERVICES GRID: "What We Build" (COMPACT CARDS & POINTER CURSOR) -->
        <section id="services" class="py-16 md:py-20 px-6 md:px-16 max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-2">SERVICES</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">What We Build</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($capabilities as $capability)
                    <div class="interactive-card glass-panel p-6 rounded-2xl flex flex-col justify-between">
                        <div>
                            <div class="w-10 h-10 rounded-lg {{ $capability['accent'] }} flex items-center justify-center mb-4">
                                <span class="material-symbols-outlined text-xl">{{ $capability['icon'] }}</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 mb-2">{{ $capability['title'] }}</h3>
                            <p class="text-slate-600 text-xs leading-relaxed mb-4">{{ $capability['description'] }}</p>
                        </div>
                        <a class="text-xs font-bold uppercase tracking-wider text-slate-900 flex items-center gap-1.5" href="{{ route('service.show', $capability['slug']) }}">
                            <span>Learn more</span> <span class="material-symbols-outlined text-xs" aria-hidden="true">arrow_forward</span>
                        </a>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- 5. WHY SUTHARTECH: "Built for Ambition" (SPLIT INTERACTIVE SHOWCASE PANEL) -->
        <section id="about" class="py-16 md:py-20 px-6 md:px-16 max-w-7xl mx-auto border-t border-slate-200/80">
            <div class="mb-12">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-2">WHY SUTHARTECH</span>
                <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 tracking-tight">Built for Ambition</h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                <!-- Left Column: Interactive 01 - 04 Feature List -->
                <div class="lg:col-span-5 flex flex-col gap-3.5">
                    <!-- 01 -->
                    <div id="ambition-item-1" 
                         class="ambition-showcase-item active p-5 rounded-2xl border border-slate-200/90 bg-slate-50/90 shadow-xs cursor-pointer flex items-center justify-between transition-all duration-300 group" 
                         onmouseenter="selectAmbitionShowcase(1)"
                         onmouseleave="resumeAmbitionAutoCycle()">
                        <div class="flex items-center gap-4">
                            <span class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 shadow-2xs group-hover:scale-105 transition-transform">
                                <span class="material-symbols-outlined text-lg">code_blocks</span>
                            </span>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="ambition-num text-xs font-black text-blue-600 uppercase tracking-wider">01</span>
                                    <h3 class="text-base font-bold text-slate-900 group-hover:text-blue-600 transition-colors">Product-focused engineering</h3>
                                </div>
                                <p class="text-slate-500 text-xs mt-0.5">User-centric product market fit</p>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-blue-600 ambition-icon text-lg group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </div>

                    <!-- 02 -->
                    <div id="ambition-item-2" 
                         class="ambition-showcase-item p-5 rounded-2xl border border-slate-200/70 bg-white hover:bg-slate-50/50 cursor-pointer flex items-center justify-between transition-all duration-300 group" 
                         onmouseenter="selectAmbitionShowcase(2)"
                         onmouseleave="resumeAmbitionAutoCycle()">
                        <div class="flex items-center gap-4">
                            <span class="w-9 h-9 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center shrink-0 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">
                                <span class="material-symbols-outlined text-lg">layers</span>
                            </span>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="ambition-num text-xs font-black text-slate-400 uppercase tracking-wider">02</span>
                                    <h3 class="text-base font-bold text-slate-900 group-hover:text-blue-600 transition-colors">Modern tech stack</h3>
                                </div>
                                <p class="text-slate-500 text-xs mt-0.5">Next.js, Laravel, Python & AWS</p>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-slate-300 ambition-icon text-lg group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </div>

                    <!-- 03 -->
                    <div id="ambition-item-3" 
                         class="ambition-showcase-item p-5 rounded-2xl border border-slate-200/70 bg-white hover:bg-slate-50/50 cursor-pointer flex items-center justify-between transition-all duration-300 group" 
                         onmouseenter="selectAmbitionShowcase(3)"
                         onmouseleave="resumeAmbitionAutoCycle()">
                        <div class="flex items-center gap-4">
                            <span class="w-9 h-9 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center shrink-0 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">
                                <span class="material-symbols-outlined text-lg">cloud_sync</span>
                            </span>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="ambition-num text-xs font-black text-slate-400 uppercase tracking-wider">03</span>
                                    <h3 class="text-base font-bold text-slate-900 group-hover:text-blue-600 transition-colors">Scalable architecture</h3>
                                </div>
                                <p class="text-slate-500 text-xs mt-0.5">High availability & 99.99% uptime</p>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-slate-300 ambition-icon text-lg group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </div>

                    <!-- 04 -->
                    <div id="ambition-item-4" 
                         class="ambition-showcase-item p-5 rounded-2xl border border-slate-200/70 bg-white hover:bg-slate-50/50 cursor-pointer flex items-center justify-between transition-all duration-300 group" 
                         onmouseenter="selectAmbitionShowcase(4)"
                         onmouseleave="resumeAmbitionAutoCycle()">
                        <div class="flex items-center gap-4">
                            <span class="w-9 h-9 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center shrink-0 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">
                                <span class="material-symbols-outlined text-lg">handshake</span>
                            </span>
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="ambition-num text-xs font-black text-slate-400 uppercase tracking-wider">04</span>
                                    <h3 class="text-base font-bold text-slate-900 group-hover:text-blue-600 transition-colors">Long-term partnership</h3>
                                </div>
                                <p class="text-slate-500 text-xs mt-0.5">24/7 SLA & continuous upgrades</p>
                            </div>
                        </div>
                        <span class="material-symbols-outlined text-slate-300 ambition-icon text-lg group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </div>
                </div>

                <!-- Right Column: Clean Showcase Box (Fixed Static Outer Container - Only Inner Content Animates) -->
                <div class="lg:col-span-7">
                    <div class="glass-card bg-white p-8 rounded-3xl border border-slate-200/90 shadow-sm h-[380px] max-h-[380px] flex flex-col justify-between relative overflow-hidden">
                        
                        <!-- Panel 1: Product-focused Engineering -->
                        <div id="ambition-card-panel-1" class="ambition-display-panel ambition-panel-active transition-all duration-300 flex flex-col justify-between h-full">
                            <div>
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-5xl font-black text-blue-600">01</span>
                                    <span class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-blue-600 text-sm">verified</span>
                                        Engineering First
                                    </span>
                                </div>
                                <h3 class="text-2xl font-extrabold text-slate-900 mb-2">Product-Focused Engineering</h3>
                                <p class="text-slate-600 text-sm leading-relaxed mb-5">
                                    We build solutions centered on product-market fit, user conversion, and long-term scalability. Transparent benchmarks ensure every release delivers clear business metrics.
                                </p>
                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center gap-2.5 text-xs font-semibold text-slate-800">
                                        <span class="material-symbols-outlined text-blue-600 text-base">check_circle</span>
                                        <span>Codebase performance benchmarks & automated test suites</span>
                                    </div>
                                    <div class="flex items-center gap-2.5 text-xs font-semibold text-slate-800">
                                        <span class="material-symbols-outlined text-blue-600 text-base">check_circle</span>
                                        <span>Conversion-focused user journey optimization</span>
                                    </div>
                                    <div class="flex items-center gap-2.5 text-xs font-semibold text-slate-800">
                                        <span class="material-symbols-outlined text-blue-600 text-base">check_circle</span>
                                        <span>Domain-Driven Architecture (DDD) for modular expansion</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-x-5 gap-y-2.5 pt-3.5 border-t border-slate-100">
                                <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-600">
                                    <span class="material-symbols-outlined text-blue-600 text-sm">person</span> User Centric
                                </span>
                                <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-600">
                                    <span class="material-symbols-outlined text-blue-600 text-sm">verified</span> Code Quality
                                </span>
                                <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-600">
                                    <span class="material-symbols-outlined text-blue-600 text-sm">account_tree</span> Architecture
                                </span>
                                <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-600">
                                    <span class="material-symbols-outlined text-blue-600 text-sm">show_chart</span> Conversion ROI
                                </span>
                                <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-600">
                                    <span class="material-symbols-outlined text-blue-600 text-sm">speed</span> Sub-Second Speed
                                </span>
                            </div>
                        </div>

                        <!-- Panel 2: Modern Tech Stack -->
                        <div id="ambition-card-panel-2" class="ambition-display-panel hidden transition-all duration-300 flex flex-col justify-between h-full">
                            <div>
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-5xl font-black text-blue-600">02</span>
                                    <span class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-blue-600 text-sm">bolt</span>
                                        Cutting Edge Stack
                                    </span>
                                </div>
                                <h3 class="text-2xl font-extrabold text-slate-900 mb-2">Modern Tech Stack</h3>
                                <p class="text-slate-600 text-sm leading-relaxed mb-5">
                                    State-of-the-art framework stack featuring Next.js 14, Laravel 12, Python FastAPI, Docker microservices, AWS Lambda serverless, PostgreSQL, and Redis caching.
                                </p>
                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center gap-2.5 text-xs font-semibold text-slate-800">
                                        <span class="material-symbols-outlined text-blue-600 text-base">bolt</span>
                                        <span>Sub-second page rendering & server-side optimization</span>
                                    </div>
                                    <div class="flex items-center gap-2.5 text-xs font-semibold text-slate-800">
                                        <span class="material-symbols-outlined text-blue-600 text-base">bolt</span>
                                        <span>Containerized Docker microservices architecture</span>
                                    </div>
                                    <div class="flex items-center gap-2.5 text-xs font-semibold text-slate-800">
                                        <span class="material-symbols-outlined text-blue-600 text-base">bolt</span>
                                        <span>Custom AI model & LLM API integrations</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-x-5 gap-y-2.5 pt-3.5 border-t border-slate-100">
                                <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-600">
                                    <span class="material-symbols-outlined text-blue-600 text-sm">bolt</span> Next.js 14
                                </span>
                                <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-600">
                                    <span class="material-symbols-outlined text-blue-600 text-sm">code</span> Laravel 12
                                </span>
                                <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-600">
                                    <span class="material-symbols-outlined text-blue-600 text-sm">cloud</span> AWS Cloud
                                </span>
                                <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-600">
                                    <span class="material-symbols-outlined text-blue-600 text-sm">terminal</span> Python FastAPI
                                </span>
                                <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-600">
                                    <span class="material-symbols-outlined text-blue-600 text-sm">view_in_ar</span> Docker
                                </span>
                                <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-600">
                                    <span class="material-symbols-outlined text-blue-600 text-sm">memory</span> Redis
                                </span>
                            </div>
                        </div>

                        <!-- Panel 3: Scalable Architecture -->
                        <div id="ambition-card-panel-3" class="ambition-display-panel hidden transition-all duration-300 flex flex-col justify-between h-full">
                            <div>
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-5xl font-black text-blue-600">03</span>
                                    <span class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-blue-600 text-sm">security</span>
                                        High Availability
                                    </span>
                                </div>
                                <h3 class="text-2xl font-extrabold text-slate-900 mb-2">Scalable Architecture</h3>
                                <p class="text-slate-600 text-sm leading-relaxed mb-5">
                                    Future-proof system designs that scale effortlessly under heavy traffic spikes, backed by multi-region CDN distribution and connection pooling.
                                </p>
                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center gap-2.5 text-xs font-semibold text-slate-800">
                                        <span class="material-symbols-outlined text-blue-600 text-base">security</span>
                                        <span>99.99% uptime SLA with automated failover handling</span>
                                    </div>
                                    <div class="flex items-center gap-2.5 text-xs font-semibold text-slate-800">
                                        <span class="material-symbols-outlined text-blue-600 text-base">security</span>
                                        <span>Zero-downtime CI/CD continuous deployment pipeline</span>
                                    </div>
                                    <div class="flex items-center gap-2.5 text-xs font-semibold text-slate-800">
                                        <span class="material-symbols-outlined text-blue-600 text-base">security</span>
                                        <span>Redis caching layers & PostgreSQL sharding</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-x-5 gap-y-2.5 pt-3.5 border-t border-slate-100">
                                <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-600">
                                    <span class="material-symbols-outlined text-blue-600 text-sm">memory</span> Redis
                                </span>
                                <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-600">
                                    <span class="material-symbols-outlined text-blue-600 text-sm">database</span> PostgreSQL
                                </span>
                                <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-600">
                                    <span class="material-symbols-outlined text-blue-600 text-sm">trending_up</span> Auto Scaling
                                </span>
                                <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-600">
                                    <span class="material-symbols-outlined text-blue-600 text-sm">globe</span> Multi-Region CDN
                                </span>
                                <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-600">
                                    <span class="material-symbols-outlined text-blue-600 text-sm">lock</span> Zero-Trust SSL
                                </span>
                            </div>
                        </div>

                        <!-- Panel 4: Long-term Partnership -->
                        <div id="ambition-card-panel-4" class="ambition-display-panel hidden transition-all duration-300 flex flex-col justify-between h-full">
                            <div>
                                <div class="flex justify-between items-center mb-4">
                                    <span class="text-5xl font-black text-blue-600">04</span>
                                    <span class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-blue-600 text-sm">handshake</span>
                                        Reliable Partner
                                    </span>
                                </div>
                                <h3 class="text-2xl font-extrabold text-slate-900 mb-2">Long-Term Partnership</h3>
                                <p class="text-slate-600 text-sm leading-relaxed mb-5">
                                    Dedicated long-term engineering support, 24/7 SLA uptime monitoring, continuous security patches, and ongoing CTO technical advisories.
                                </p>
                                <div class="space-y-2 mb-4">
                                    <div class="flex items-center gap-2.5 text-xs font-semibold text-slate-800">
                                        <span class="material-symbols-outlined text-blue-600 text-base">handshake</span>
                                        <span>24/7 priority SLA monitoring & rapid response support</span>
                                    </div>
                                    <div class="flex items-center gap-2.5 text-xs font-semibold text-slate-800">
                                        <span class="material-symbols-outlined text-blue-600 text-base">handshake</span>
                                        <span>Continuous security patches & feature upgrades</span>
                                    </div>
                                    <div class="flex items-center gap-2.5 text-xs font-semibold text-slate-800">
                                        <span class="material-symbols-outlined text-blue-600 text-base">handshake</span>
                                        <span>Dedicated lead software engineers & CTO consultation</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-wrap items-center gap-x-5 gap-y-2.5 pt-3.5 border-t border-slate-100">
                                <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-600">
                                    <span class="material-symbols-outlined text-blue-600 text-sm">schedule</span> 24/7 SLA
                                </span>
                                <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-600">
                                    <span class="material-symbols-outlined text-blue-600 text-sm">shield</span> Security Updates
                                </span>
                                <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-600">
                                    <span class="material-symbols-outlined text-blue-600 text-sm">support_agent</span> CTO Advisory
                                </span>
                                <span class="flex items-center gap-1.5 text-xs font-semibold text-slate-600">
                                    <span class="material-symbols-outlined text-blue-600 text-sm">groups</span> Dedicated Team
                                </span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>

        <script>
            let ambitionCurrentIndex = 1;
            let ambitionTimer = null;

            function startAmbitionAutoCycle() {
                if (ambitionTimer) clearInterval(ambitionTimer);
                ambitionTimer = setInterval(() => {
                    ambitionCurrentIndex = (ambitionCurrentIndex % 4) + 1;
                    selectAmbitionShowcase(ambitionCurrentIndex, false);
                }, 12000);
            }

            function resumeAmbitionAutoCycle() {
                startAmbitionAutoCycle();
            }

            function selectAmbitionShowcase(index, stopAuto = true) {
                if (stopAuto && ambitionTimer) {
                    clearInterval(ambitionTimer);
                    ambitionTimer = null;
                }
                ambitionCurrentIndex = index;

                for (let i = 1; i <= 4; i++) {
                    const item = document.getElementById('ambition-item-' + i);
                    const panel = document.getElementById('ambition-card-panel-' + i);
                    if (item && panel) {
                        const iconBox = item.querySelector('span:first-child');
                        if (i === index) {
                            item.className = 'ambition-showcase-item active p-5 rounded-2xl border border-slate-200/90 bg-slate-50/90 shadow-xs cursor-pointer flex items-center justify-between transition-all duration-300 group';
                            item.querySelector('.ambition-num').className = 'ambition-num text-xs font-black text-blue-600 uppercase tracking-wider';
                            item.querySelector('.ambition-icon').className = 'material-symbols-outlined text-blue-600 ambition-icon text-lg group-hover:translate-x-1 transition-transform';
                            if (iconBox) iconBox.className = 'w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 shadow-2xs group-hover:scale-105 transition-transform';
                            
                            panel.classList.remove('hidden', 'panel-anim-1', 'panel-anim-2', 'panel-anim-3', 'panel-anim-4');
                            void panel.offsetWidth;
                            panel.classList.add('panel-anim-' + i);
                        } else {
                            item.className = 'ambition-showcase-item p-5 rounded-2xl border border-slate-200/70 bg-white hover:bg-slate-50/50 cursor-pointer flex items-center justify-between transition-all duration-300 group';
                            item.querySelector('.ambition-num').className = 'ambition-num text-xs font-black text-slate-400 uppercase tracking-wider';
                            item.querySelector('.ambition-icon').className = 'material-symbols-outlined text-slate-300 ambition-icon text-lg group-hover:translate-x-1 transition-transform';
                            if (iconBox) iconBox.className = 'w-9 h-9 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center shrink-0 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors';
                            
                            panel.classList.add('hidden');
                            panel.classList.remove('panel-anim-1', 'panel-anim-2', 'panel-anim-3', 'panel-anim-4');
                        }
                    }
                }
            }

            document.addEventListener('DOMContentLoaded', () => {
                startAmbitionAutoCycle();
            });
        </script>

@if ($projects->isNotEmpty())
        <!-- 6. SELECTED WORK -->
        <section id="projects" class="selected-work-section w-full max-w-none py-10 md:py-16 mx-0">
            <div class="selected-work-heading text-center mb-6 md:mb-9 px-4 md:px-16">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-2">SELECTED WORK</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">Selected Work</h2>
            </div>

            <div class="selected-work-carousel">
                <div class="selected-work-track">
                    @foreach ($projects as $project)
                        @php($projectImage = !$project->image_path ? null : (str_starts_with($project->image_path, 'images/') ? asset($project->image_path) : asset('storage/'.$project->image_path)))
                        <a class="interactive-card glass-panel rounded-2xl overflow-hidden shadow-sm text-left no-underline" href="{{ route('project.show', $project->slug) }}" aria-label="View {{ $project->title }} project">
                            <div class="h-56 bg-slate-100 overflow-hidden relative flex items-center justify-center">
                                @if ($projectImage)
                                    <img class="w-full h-full object-cover transition-transform duration-500" src="{{ $projectImage }}" alt="{{ $project->title }} project preview" loading="lazy" />
                                @else
                                    <span class="material-symbols-outlined text-4xl text-slate-400" aria-hidden="true">work</span>
                                @endif
                            </div>
                            <div class="p-6">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-600 bg-slate-100 px-2.5 py-1 rounded border border-slate-200 mb-2 inline-block">{{ $project->category }}</span>
                                <h3 class="text-xl font-bold text-slate-900 mb-2">{{ $project->title }}</h3>
                                <p class="text-slate-600 text-xs leading-relaxed mb-4">{{ $project->summary }}</p>
                                @if ($project->technologies)
                                    <div class="flex gap-2 flex-wrap">
                                        @foreach ($project->technologies as $technology)
                                            <span class="text-[10px] font-bold uppercase tracking-wider bg-white border border-slate-200 px-2.5 py-1 rounded text-slate-600">{{ $technology }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="selected-work-dots" aria-label="Selected work projects">
                @foreach ($projects as $projectIndex => $project)
                    <button type="button" class="selected-work-dot{{ $projectIndex === 0 ? ' active' : '' }}" data-selected-work-dot="{{ $projectIndex }}" aria-label="Show project {{ $projectIndex + 1 }}" title="Project {{ $projectIndex + 1 }}" aria-current="{{ $projectIndex === 0 ? 'true' : 'false' }}"></button>
                @endforeach
            </div>
        </section>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const track = document.querySelector('.selected-work-track');
                const sourceCards = Array.from(track?.querySelectorAll('.interactive-card') || []);
                const dots = Array.from(document.querySelectorAll('[data-selected-work-dot]'));

                if (!track || sourceCards.length === 0 || sourceCards.length !== dots.length) return;

                sourceCards.forEach((card, index) => {
                    card.dataset.projectIndex = String(index);
                    card.dataset.carouselSet = 'middle';
                });

                const cloneCardSet = (setName, insertAtStart) => {
                    const clones = sourceCards.map((sourceCard) => {
                        const clone = sourceCard.cloneNode(true);
                        clone.dataset.projectIndex = sourceCard.dataset.projectIndex;
                        clone.dataset.carouselSet = setName;
                        clone.classList.remove('is-center');
                        return clone;
                    });

                    const fragment = document.createDocumentFragment();
                    clones.forEach((clone) => fragment.appendChild(clone));
                    if (insertAtStart) track.insertBefore(fragment, track.firstChild);
                    else track.appendChild(fragment);
                    return clones;
                };

                const leftCards = [];
                const rightCards = [];
                for (let copy = 0; copy < 3; copy++) {
                    leftCards.unshift(...cloneCardSet(`left-${copy}`, true));
                    rightCards.push(...cloneCardSet(`right-${copy}`, false));
                }
                const cards = [...leftCards, ...sourceCards, ...rightCards];

                const normalizeInfinitePosition = () => {
                    const middleStart = sourceCards[0].offsetLeft;
                    const rightStart = rightCards[0].offsetLeft;
                    const setWidth = rightStart - middleStart;
                    if (setWidth <= 0) return;

                    const leftBuffer = middleStart - setWidth * 2;
                    const rightBuffer = middleStart + setWidth * 3;
                    if (track.scrollLeft < leftBuffer) {
                        track.style.scrollBehavior = 'auto';
                        track.scrollLeft += setWidth * 3;
                        track.style.scrollBehavior = '';
                    } else if (track.scrollLeft > rightBuffer) {
                        track.style.scrollBehavior = 'auto';
                        track.scrollLeft -= setWidth * 3;
                        track.style.scrollBehavior = '';
                    }
                };

                const updateActiveProject = () => {
                    normalizeInfinitePosition();
                    const trackCenter = track.scrollLeft + track.clientWidth / 2;
                    let activeCard = cards[0];
                    let closestDistance = Number.POSITIVE_INFINITY;

                    cards.forEach((card) => {
                        const cardCenter = card.offsetLeft + card.offsetWidth / 2;
                        const distance = Math.abs(cardCenter - trackCenter);
                        if (distance < closestDistance) {
                            closestDistance = distance;
                            activeCard = card;
                        }
                    });

                    const activeIndex = Number(activeCard.dataset.projectIndex);

                    dots.forEach((dot, index) => {
                        const isActive = index === activeIndex;
                        dot.classList.toggle('active', isActive);
                        dot.setAttribute('aria-current', isActive ? 'true' : 'false');
                    });

                    cards.forEach((card) => card.classList.toggle('is-center', Number(card.dataset.projectIndex) === activeIndex));
                };

                let frameId = 0;
                track.addEventListener('scroll', () => {
                    if (frameId) return;
                    frameId = requestAnimationFrame(() => {
                        frameId = 0;
                        updateActiveProject();
                    });
                }, { passive: true });
                window.addEventListener('resize', updateActiveProject);
                dots.forEach((dot, index) => {
                    dot.addEventListener('click', () => {
                        const targetCard = sourceCards[index];
                        track.scrollTo({
                            left: targetCard.offsetLeft - (track.clientWidth - targetCard.offsetWidth) / 2,
                            behavior: 'smooth'
                        });
                    });
                });

                sourceCards[0].classList.add('is-center');
                requestAnimationFrame(() => {
                    track.scrollLeft = sourceCards[0].offsetLeft - (track.clientWidth - sourceCards[0].offsetWidth) / 2;
                    updateActiveProject();
                });
            });
        </script>

@endif

        <!-- 7. PROCESS: "How We Build" -->
        <section id="process" class="process-section py-10 md:py-16 px-4 md:px-16 max-w-7xl mx-auto">
            <div class="text-center mb-8 md:mb-10">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-2">PROCESS</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">How We Build</h2>
            </div>

            <div class="process-steps grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Step 1 -->
                <div class="interactive-card glass-card p-6 rounded-2xl border border-slate-200/80 flex flex-col gap-3 group">
                    <div class="flex items-center gap-3">
                        <span class="text-4xl font-black text-slate-900 group-hover:text-slate-700 transition-colors">01</span>
                        <div class="h-0.5 flex-1 bg-slate-200 group-hover:bg-slate-400 transition-colors relative">
                            <div class="absolute right-0 -top-1 w-2.5 h-2.5 rounded-full bg-slate-900 group-hover:bg-slate-500 transition-colors"></div>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Discover</h3>
                    <p class="text-slate-600 text-xs leading-relaxed">Requirements gathering, user research, roadmap planning, and technical scope.</p>
                </div>

                <!-- Step 2 -->
                <div class="interactive-card glass-card p-6 rounded-2xl border border-slate-200/80 flex flex-col gap-3 group">
                    <div class="flex items-center gap-3">
                        <span class="text-4xl font-black text-slate-900 group-hover:text-slate-700 transition-colors">02</span>
                        <div class="h-0.5 flex-1 bg-slate-200 group-hover:bg-slate-400 transition-colors relative">
                            <div class="absolute right-0 -top-1 w-2.5 h-2.5 rounded-full bg-slate-900 group-hover:bg-slate-500 transition-colors"></div>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Design</h3>
                    <p class="text-slate-600 text-xs leading-relaxed">UI component systems, interactive prototypes, and user experience flows.</p>
                </div>

                <!-- Step 3 -->
                <div class="interactive-card glass-card p-6 rounded-2xl border border-slate-200/80 flex flex-col gap-3 group">
                    <div class="flex items-center gap-3">
                        <span class="text-4xl font-black text-slate-900 group-hover:text-slate-700 transition-colors">03</span>
                        <div class="h-0.5 flex-1 bg-slate-200 group-hover:bg-slate-400 transition-colors relative">
                            <div class="absolute right-0 -top-1 w-2.5 h-2.5 rounded-full bg-slate-900 group-hover:bg-slate-500 transition-colors"></div>
                        </div>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Build</h3>
                    <p class="text-slate-600 text-xs leading-relaxed">Clean modular code, CI/CD pipelines, automated testing, and secure APIs.</p>
                </div>

                <!-- Step 4 -->
                <div class="interactive-card glass-card p-6 rounded-2xl border border-slate-200/80 flex flex-col gap-3 group">
                    <div class="flex items-center gap-3">
                        <span class="text-4xl font-black text-slate-900 group-hover:text-slate-700 transition-colors">04</span>
                        <div class="h-0.5 flex-1 bg-slate-200"></div>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Scale</h3>
                    <p class="text-slate-600 text-xs leading-relaxed">Cloud deployment, performance monitoring, scaling, and ongoing support.</p>
                </div>
            </div>
        </section>

        <!-- 8. INDUSTRIES -->
        <section class="industries-section py-10 md:py-16 max-w-7xl mx-auto">
            <div class="text-center px-4 md:px-16 mb-7 md:mb-10">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-2">INDUSTRIES</span>
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">Technology Across Industries</h2>
            </div>

            <div class="marquee-viewport industries-viewport">
                <div class="industry-track industry-track-left" data-industry-loop>
                    <div class="industry-track-group">
                    <div class="industry-card"><span class="material-symbols-outlined">cloud</span><span>SaaS</span></div>
                    <div class="industry-card"><span class="material-symbols-outlined">school</span><span>Education</span></div>
                    <div class="industry-card"><span class="material-symbols-outlined">health_and_safety</span><span>Healthcare</span></div>
                    <div class="industry-card"><span class="material-symbols-outlined">shopping_bag</span><span>E-commerce</span></div>
                    <div class="industry-card"><span class="material-symbols-outlined">payments</span><span>Finance</span></div>
                    <div class="industry-card"><span class="material-symbols-outlined">settings</span><span>Operations</span></div>
                    </div>
                    <div class="industry-track-group" aria-hidden="true">
                    <div class="industry-card"><span class="material-symbols-outlined">cloud</span><span>SaaS</span></div>
                    <div class="industry-card"><span class="material-symbols-outlined">school</span><span>Education</span></div>
                    <div class="industry-card"><span class="material-symbols-outlined">health_and_safety</span><span>Healthcare</span></div>
                    <div class="industry-card"><span class="material-symbols-outlined">shopping_bag</span><span>E-commerce</span></div>
                    <div class="industry-card"><span class="material-symbols-outlined">payments</span><span>Finance</span></div>
                    <div class="industry-card"><span class="material-symbols-outlined">settings</span><span>Operations</span></div>
                    </div>
                </div>
                <div class="industry-track industry-track-left industry-track-offset" data-industry-loop>
                    <div class="industry-track-group">
                    <div class="industry-card"><span class="material-symbols-outlined">api</span><span>Web Platforms</span></div>
                    <div class="industry-card"><span class="material-symbols-outlined">smartphone</span><span>Mobile</span></div>
                    <div class="industry-card"><span class="material-symbols-outlined">psychology</span><span>AI Products</span></div>
                    <div class="industry-card"><span class="material-symbols-outlined">factory</span><span>Manufacturing</span></div>
                    <div class="industry-card"><span class="material-symbols-outlined">local_shipping</span><span>Logistics</span></div>
                    <div class="industry-card"><span class="material-symbols-outlined">real_estate_agent</span><span>Real Estate</span></div>
                    </div>
                    <div class="industry-track-group" aria-hidden="true">
                    <div class="industry-card"><span class="material-symbols-outlined">api</span><span>Web Platforms</span></div>
                    <div class="industry-card"><span class="material-symbols-outlined">smartphone</span><span>Mobile</span></div>
                    <div class="industry-card"><span class="material-symbols-outlined">psychology</span><span>AI Products</span></div>
                    <div class="industry-card"><span class="material-symbols-outlined">factory</span><span>Manufacturing</span></div>
                    <div class="industry-card"><span class="material-symbols-outlined">local_shipping</span><span>Logistics</span></div>
                    <div class="industry-card"><span class="material-symbols-outlined">real_estate_agent</span><span>Real Estate</span></div>
                    </div>
                </div>
            </div>
        </section>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const fillIndustryTracks = () => {
                    document.querySelectorAll('[data-industry-loop]').forEach((track) => {
                        const viewport = track.closest('.industries-viewport');
                        const group = track.querySelector('.industry-track-group');
                        if (!viewport || !group) return;

                        const groupWidth = group.getBoundingClientRect().width;
                        if (!groupWidth) return;

                        const groupsNeeded = Math.max(2, Math.ceil((viewport.clientWidth * 2 / groupWidth) / 2) * 2);
                        const baseDuration = parseFloat(getComputedStyle(track).getPropertyValue('--industry-loop-duration')) || 42;
                        track.style.animationDuration = `${baseDuration * (groupsNeeded / 2)}s`;
                        while (track.children.length > groupsNeeded) track.lastElementChild.remove();
                        while (track.children.length < groupsNeeded) {
                            const clone = group.cloneNode(true);
                            clone.setAttribute('aria-hidden', 'true');
                            track.appendChild(clone);
                        }
                    });
                };

                fillIndustryTracks();
                if ('ResizeObserver' in window) {
                    const observer = new ResizeObserver(fillIndustryTracks);
                    document.querySelectorAll('[data-industry-loop]').forEach((track) => {
                        observer.observe(track.closest('.industries-viewport'));
                        observer.observe(track.querySelector('.industry-track-group'));
                    });
                } else {
                    window.addEventListener('resize', fillIndustryTracks);
                }
            });
        </script>

        <!-- 8. RESULTS -->
        <section class="results-section py-10 md:py-14 px-6 md:px-16 max-w-7xl mx-auto">
            <div class="text-center mb-7">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">RESULTS</span>
            </div>
            <div class="results-grid grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div class="results-item interactive-card glass-panel p-6 rounded-2xl">
                    <div class="text-4xl md:text-5xl font-black text-slate-900 mb-1">50+</div>
                    <div class="text-xs font-bold uppercase tracking-wider text-slate-500">Projects</div>
                </div>
                <div class="results-item interactive-card glass-panel p-6 rounded-2xl">
                    <div class="text-4xl md:text-5xl font-black text-slate-900 mb-1">3+</div>
                    <div class="text-xs font-bold uppercase tracking-wider text-slate-500">Years</div>
                </div>
                <div class="results-item interactive-card glass-panel p-6 rounded-2xl">
                    <div class="text-4xl md:text-5xl font-black text-slate-900 mb-1">20+</div>
                    <div class="text-xs font-bold uppercase tracking-wider text-slate-500">Techs</div>
                </div>
                <div class="results-item interactive-card glass-panel p-6 rounded-2xl">
                    <div class="text-4xl md:text-5xl font-black text-slate-900 mb-1">Global</div>
                    <div class="text-xs font-bold uppercase tracking-wider text-slate-500">Clients</div>
                </div>
            </div>
        </section>

        <!-- 10. WORDS FROM THOSE WE SCALE -->
        <section class="testimonials-section py-10 md:py-16 max-w-7xl mx-auto">
            <div class="text-center px-4 md:px-16 mb-7 md:mb-10">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-2">TESTIMONIALS</span>
                <h2 class="testimonial-heading text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">Words from those<br class="sm:hidden"/> we scale</h2>
            </div>

            <div class="marquee-viewport testimonials-viewport">
                <div class="testimonial-track testimonial-track-left">
                    <article class="testimonial-card"><h3>Rohan Mehta</h3><span>Founder, Atlas Labs</span><p>“SutharTech turned a complex product idea into a clear, scalable platform our team could confidently grow.”</p><div class="testimonial-stars">★★★★★</div></article>
                    <article class="testimonial-card"><h3>Maryam Sahem</h3><span>Co-founder, Fine Jewelry</span><p>“Their expertise in crafting a strong digital experience gave our brand the clarity and momentum it needed.”</p><div class="testimonial-stars">★★★★★</div></article>
                    <article class="testimonial-card"><h3>Meryeme Hallab</h3><span>Founder, Northstar</span><p>“The team took time to understand our goals and delivered an engineering system that scales with our business.”</p><div class="testimonial-stars">★★★★★</div></article>
                    <article class="testimonial-card"><h3>Charlie Christie</h3><span>Business Owner</span><p>“A thoughtful partner from the first conversation through launch, with reliable communication at every step.”</p><div class="testimonial-stars">★★★★★</div></article>
                    <article class="testimonial-card"><h3>Jim Fernández</h3><span>Co-owner, Meridian</span><p>“They brought structure to our digital growth and helped our internal team move faster with confidence.”</p><div class="testimonial-stars">★★★★★</div></article>
                    <article class="testimonial-card"><h3>Farah Y.</h3><span>Partner, Orbit</span><p>“A dependable engineering partner with the product judgment and technical depth to help us scale.”</p><div class="testimonial-stars">★★★★★</div></article>
                    <article class="testimonial-card"><h3>Elisa Mirzoeva</h3><span>Co-founder, Vale</span><p>“SutharTech understood our customers and translated that insight into a smooth, high-performing experience.”</p><div class="testimonial-stars">★★★★★</div></article>
                    <article class="testimonial-card"><h3>Rohan Mehta</h3><span>Founder, Atlas Labs</span><p>“SutharTech turned a complex product idea into a clear, scalable platform our team could confidently grow.”</p><div class="testimonial-stars">★★★★★</div></article>
                    <article class="testimonial-card"><h3>Maryam Sahem</h3><span>Co-founder, Fine Jewelry</span><p>“Their expertise in crafting a strong digital experience gave our brand the clarity and momentum it needed.”</p><div class="testimonial-stars">★★★★★</div></article>
                    <article class="testimonial-card"><h3>Meryeme Hallab</h3><span>Founder, Northstar</span><p>“The team took time to understand our goals and delivered an engineering system that scales with our business.”</p><div class="testimonial-stars">★★★★★</div></article>
                    <article class="testimonial-card"><h3>Charlie Christie</h3><span>Business Owner</span><p>“A thoughtful partner from the first conversation through launch, with reliable communication at every step.”</p><div class="testimonial-stars">★★★★★</div></article>
                    <article class="testimonial-card"><h3>Jim Fernández</h3><span>Co-owner, Meridian</span><p>“They brought structure to our digital growth and helped our internal team move faster with confidence.”</p><div class="testimonial-stars">★★★★★</div></article>
                    <article class="testimonial-card"><h3>Farah Y.</h3><span>Partner, Orbit</span><p>“A dependable engineering partner with the product judgment and technical depth to help us scale.”</p><div class="testimonial-stars">★★★★★</div></article>
                    <article class="testimonial-card"><h3>Elisa Mirzoeva</h3><span>Co-founder, Vale</span><p>“SutharTech understood our customers and translated that insight into a smooth, high-performing experience.”</p><div class="testimonial-stars">★★★★★</div></article>
                </div>
                <div class="testimonial-track testimonial-track-left testimonial-track-offset">
                    <article class="testimonial-card"><h3>Jim Fernández</h3><span>Co-owner, Meridian</span><p>“They brought structure to our digital growth and helped our internal team move faster with confidence.”</p><div class="testimonial-stars">★★★★★</div></article>
                    <article class="testimonial-card"><h3>Farah Y.</h3><span>Partner, Orbit</span><p>“A dependable engineering partner with the product judgment and technical depth to help us scale.”</p><div class="testimonial-stars">★★★★★</div></article>
                    <article class="testimonial-card"><h3>Elisa Mirzoeva</h3><span>Co-founder, Vale</span><p>“SutharTech understood our customers and translated that insight into a smooth, high-performing experience.”</p><div class="testimonial-stars">★★★★★</div></article>
                    <article class="testimonial-card"><h3>Rohan Mehta</h3><span>Founder, Atlas Labs</span><p>“SutharTech turned a complex product idea into a clear, scalable platform our team could confidently grow.”</p><div class="testimonial-stars">★★★★★</div></article>
                    <article class="testimonial-card"><h3>Maryam Sahem</h3><span>Co-founder, Fine Jewelry</span><p>“Their expertise in crafting a strong digital experience gave our brand the clarity and momentum it needed.”</p><div class="testimonial-stars">★★★★★</div></article>
                    <article class="testimonial-card"><h3>Meryeme Hallab</h3><span>Founder, Northstar</span><p>“The team took time to understand our goals and delivered an engineering system that scales with our business.”</p><div class="testimonial-stars">★★★★★</div></article>
                    <article class="testimonial-card"><h3>Charlie Christie</h3><span>Business Owner</span><p>“A thoughtful partner from the first conversation through launch, with reliable communication at every step.”</p><div class="testimonial-stars">★★★★★</div></article>
                    <article class="testimonial-card"><h3>Jim Fernández</h3><span>Co-owner, Meridian</span><p>“They brought structure to our digital growth and helped our internal team move faster with confidence.”</p><div class="testimonial-stars">★★★★★</div></article>
                    <article class="testimonial-card"><h3>Farah Y.</h3><span>Partner, Orbit</span><p>“A dependable engineering partner with the product judgment and technical depth to help us scale.”</p><div class="testimonial-stars">★★★★★</div></article>
                    <article class="testimonial-card"><h3>Elisa Mirzoeva</h3><span>Co-founder, Vale</span><p>“SutharTech understood our customers and translated that insight into a smooth, high-performing experience.”</p><div class="testimonial-stars">★★★★★</div></article>
                </div>
            </div>

            <div class="testimonial-cta mx-4 md:mx-16 mt-8 md:mt-10">
                <h3>Ready to scale your idea?</h3>
                <div class="testimonial-cta-actions">
                    <a class="testimonial-cta-outline" href="{{ route('testimonials') }}">
                        <span>Explore more testimonials</span>
                        <span class="testimonial-cta-icon material-symbols-outlined" aria-hidden="true">arrow_forward</span>
                    </a>
                    <a class="testimonial-cta-primary" href="{{ route('contact') }}">
                        <span>Talk to our team</span>
                        <span class="testimonial-cta-icon" aria-hidden="true">↗</span>
                    </a>
                </div>
            </div>
        </section>

        

<section id="contact" class="home-contact-section px-5 md:px-8">
            <div class="home-contact-panel cta-banner-gradient rounded-3xl p-3 sm:p-4 md:p-4 shadow-xl border border-white/60 grid grid-cols-1 lg:grid-cols-12 gap-4 lg:gap-6 items-center">
                <!-- Left Content -->
                <div class="home-contact-copy lg:col-span-6 flex flex-col gap-6">
                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-900 tracking-tight leading-tight">
                        Have an Idea<br/>Worth Building?
                    </h2>
                    <p class="text-slate-800 text-sm md:text-base leading-relaxed max-w-md">
                        Partner with SutharTech to build custom software, AI models, and scalable cloud architecture. Schedule a direct call with our lead engineering team.
                    </p>
                    <div class="flex flex-wrap items-center gap-4 pt-2">
                        <a class="btn-slate-primary inline-flex items-center gap-2" href="mailto:hello@suthartech.com">
                            <span class="material-symbols-outlined text-sm">mail</span>
                            <span>Start a Conversation</span>
                        </a>
                        <span class="text-xs text-slate-700 font-semibold flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>Direct response within 2 hours</span>
                        </span>
                    </div>
                </div>

                <!-- Right Interactive Booking Card (Name, Phone, Calendar) -->
                <div class="home-contact-form-column lg:col-span-6">
                    <div class="glass-card bg-white/95 rounded-2xl p-3 md:p-3 shadow-2xl border border-white/80">
                        <div class="flex justify-between items-center mb-2 pb-2 border-b border-slate-100">
                            <div>
                                <h3 class="font-extrabold text-slate-900 text-base md:text-lg">Schedule a Call</h3>
                                <p class="text-slate-500 text-xs">Select your preferred date and time</p>
                            </div>
                            <span class="text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1 rounded-full">Available Today</span>
                        </div>

                        <form id="homeCallScheduler" method="POST" action="{{ route('contact.store') }}" class="home-call-scheduler space-y-1">
                            @csrf
                            <input type="hidden" name="form_type" value="call_request">
                            <input type="hidden" name="project_type" value="Scheduled call">
                            <input type="hidden" name="message" value="Please contact me about a scheduled call request.">
                            <p id="callFormError" class="text-sm text-red-700" role="alert" hidden></p>
                            <!-- Name Input -->
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3.5 top-3.5 text-slate-400 text-sm">person</span>
                                <input type="text" name="name" required autocomplete="name" placeholder="Full Name *" class="w-full text-xs pl-10 pr-4 py-1.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 font-medium text-slate-900 transition-all"/>
                            </div>

                            <!-- Phone Input -->
                            <div class="grid grid-cols-1 gap-3">
                                <div class="relative">
                                    <span class="material-symbols-outlined absolute left-3.5 top-3.5 text-slate-400 text-sm">call</span>
                                    <input type="tel" name="phone" required autocomplete="tel" placeholder="Phone Number *" class="w-full text-xs pl-10 pr-4 py-1.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 font-medium text-slate-900 transition-all"/>
                                </div>
                            </div>

                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-3.5 top-3.5 text-slate-400 text-sm">mail</span>
                                <input type="email" name="email" required autocomplete="email" placeholder="Work Email *" class="w-full text-xs pl-10 pr-4 py-1.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-slate-900 font-medium text-slate-900 transition-all"/>
                            </div>

                            <!-- Calendar 2026 Date Picker Widget -->
                            <div class="bg-slate-50 p-2 rounded-xl border border-slate-200">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-sm text-slate-900">calendar_month</span>
                                        <span>Select a date</span>
                                    </span>
                                    <div class="flex items-center gap-1">
                                        <button type="button" id="callCalendarPrevious" aria-label="Previous month" class="rounded-md p-1 text-slate-600 hover:bg-slate-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-slate-700">
                                            <span class="material-symbols-outlined text-base" aria-hidden="true">chevron_left</span>
                                        </button>
                                        <span id="callCalendarMonth" class="min-w-24 text-center text-[11px] font-semibold text-slate-600" aria-live="polite"></span>
                                        <button type="button" id="callCalendarNext" aria-label="Next month" class="rounded-md p-1 text-slate-600 hover:bg-slate-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-slate-700">
                                            <span class="material-symbols-outlined text-base" aria-hidden="true">chevron_right</span>
                                        </button>
                                    </div>
                                </div>

                                <!-- Weekdays Header -->
                                <div class="grid grid-cols-7 gap-1 text-center text-[10px] text-slate-400 font-bold uppercase">
                                    <span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span><span>Su</span>
                                </div>

                                <!-- Calendar Days Grid -->
                                <div id="callCalendarDays" class="grid grid-cols-7 gap-1 text-center text-xs font-semibold text-slate-700" role="group" aria-label="Choose a call date"></div>
                                <input type="hidden" name="call_date" id="callDateInput" required>

                                <!-- Preferred Call Range -->
                                <div class="time-range-picker mt-1 pt-1 border-t border-slate-200">
                                    <div class="text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">Preferred Call Range</div>
                                    <div class="grid grid-cols-2 gap-2">
                                        <label class="time-range-field">
                                            <span>From</span>
                                        <input type="time" name="call_start" min="09:00" max="18:00" value="13:00" required/>
                                        </label>
                                        <label class="time-range-field">
                                            <span>To</span>
                                            <input type="time" name="call_end" min="09:00" max="18:00" value="14:00" required/>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            @include('partials.form-bot-protection')
                            <button type="submit" class="btn-slate-primary w-full py-2.5 flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-sm">schedule</span>
                                <span>Schedule a call</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <div id="callThankYouModal" class="call-thank-you-modal" hidden>
            <div class="call-thank-you-backdrop" data-modal-close></div>
            <section class="call-thank-you-dialog" role="dialog" aria-modal="true" aria-labelledby="callThankYouTitle" aria-describedby="callThankYouMessage" tabindex="-1">
                <span class="material-symbols-outlined call-thank-you-icon" aria-hidden="true">check_circle</span>
                <h2 id="callThankYouTitle">Thanks for reaching out.</h2>
                <p id="callThankYouMessage">Your preferred call time has been sent to our team. We’ll be in touch soon.</p>
                <button type="button" id="callThankYouClose" class="btn-slate-primary">Done</button>
            </section>
        </div>

        <script>
            (() => {
                const calendarDays = document.getElementById('callCalendarDays');
                const monthLabel = document.getElementById('callCalendarMonth');
                const dateInput = document.getElementById('callDateInput');
                const previousButton = document.getElementById('callCalendarPrevious');
                const nextButton = document.getElementById('callCalendarNext');
                const form = document.getElementById('homeCallScheduler');
                const formError = document.getElementById('callFormError');
                const modal = document.getElementById('callThankYouModal');
                const dialog = modal.querySelector('[role="dialog"]');
                const closeButton = document.getElementById('callThankYouClose');
                const today = new Date();
                const firstAllowedMonth = new Date(today.getFullYear(), today.getMonth(), 1);
                let visibleMonth = new Date(firstAllowedMonth);
                let selectedDate = new Date(today.getFullYear(), today.getMonth(), today.getDate());

                const dateValue = (date) => [
                    date.getFullYear(),
                    String(date.getMonth() + 1).padStart(2, '0'),
                    String(date.getDate()).padStart(2, '0'),
                ].join('-');

                function renderCalendar() {
                    monthLabel.textContent = visibleMonth.toLocaleDateString(undefined, { month: 'long', year: 'numeric' });
                    calendarDays.replaceChildren();
                    previousButton.disabled = visibleMonth <= firstAllowedMonth;
                    previousButton.classList.toggle('opacity-40', previousButton.disabled);
                    previousButton.classList.toggle('cursor-not-allowed', previousButton.disabled);

                    const firstWeekday = (new Date(visibleMonth.getFullYear(), visibleMonth.getMonth(), 1).getDay() + 6) % 7;
                    const daysInMonth = new Date(visibleMonth.getFullYear(), visibleMonth.getMonth() + 1, 0).getDate();
                    for (let blank = 0; blank < firstWeekday; blank++) {
                        const spacer = document.createElement('span');
                        spacer.setAttribute('aria-hidden', 'true');
                        calendarDays.append(spacer);
                    }

                    for (let day = 1; day <= daysInMonth; day++) {
                        const date = new Date(visibleMonth.getFullYear(), visibleMonth.getMonth(), day);
                        const button = document.createElement('button');
                        button.type = 'button';
                        button.className = 'calendar-day-btn';
                        button.textContent = String(day);
                        button.disabled = date < new Date(today.getFullYear(), today.getMonth(), today.getDate());
                        button.setAttribute('aria-label', date.toLocaleDateString(undefined, { dateStyle: 'full' }));
                        button.setAttribute('aria-pressed', dateValue(date) === dateValue(selectedDate) ? 'true' : 'false');

                        if (dateValue(date) === dateValue(selectedDate)) {
                            button.classList.add('selected');
                        }

                        button.addEventListener('click', () => {
                            selectedDate = date;
                            dateInput.value = dateValue(date);
                            renderCalendar();
                        });
                        calendarDays.append(button);
                    }

                    dateInput.value = dateValue(selectedDate);
                }

                previousButton.addEventListener('click', () => {
                    if (visibleMonth > firstAllowedMonth) {
                        visibleMonth = new Date(visibleMonth.getFullYear(), visibleMonth.getMonth() - 1, 1);
                        renderCalendar();
                    }
                });
                nextButton.addEventListener('click', () => {
                    visibleMonth = new Date(visibleMonth.getFullYear(), visibleMonth.getMonth() + 1, 1);
                    renderCalendar();
                });

                function closeThankYou() {
                    modal.hidden = true;
                    document.body.classList.remove('modal-open');
                    form.querySelector('[type="submit"]').focus();
                }

                closeButton.addEventListener('click', closeThankYou);
                modal.addEventListener('click', (event) => {
                    if (event.target.matches('[data-modal-close]')) closeThankYou();
                });
                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape' && !modal.hidden) closeThankYou();
                });

                form.addEventListener('submit', async (event) => {
                    event.preventDefault();
                    formError.hidden = true;
                    const submitButton = form.querySelector('[type="submit"]');
                    const submitLabel = submitButton.querySelector('span:last-child');
                    submitButton.disabled = true;
                    submitLabel.textContent = 'Sending…';

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                            body: new FormData(form),
                        });
                        const result = await response.json();
                        if (!response.ok || !result.success) {
                            throw new Error(result.message || 'Please check your details and try again.');
                        }
                        modal.hidden = false;
                        document.body.classList.add('modal-open');
                        closeButton.focus();
                    } catch (error) {
                        formError.textContent = error.message || 'We could not send your request. Please try again.';
                        formError.hidden = false;
                    } finally {
                        submitButton.disabled = false;
                        submitLabel.textContent = 'Schedule a call';
                    }
                });

                renderCalendar();
            })();
        </script>

        @if ($faqs->isNotEmpty())
            @include('partials.faq-section', [
                'faqs' => $faqs,
                'id' => 'home-faq-title',
                'title' => 'A few things you may be wondering.',
                'description' => 'Clear answers to help you decide what to build next.',
            ])
        @endif
    </main>

    <!-- 10. FOOTER -->
    <x-site-footer />

</body>
</html>
