<header class="site-navbar fixed top-0 w-full z-50 glass-panel" id="site-main-header">
    <div class="site-navbar-inner px-6 md:px-16 py-3.5 max-w-7xl mx-auto flex items-center justify-between">
        <!-- Logo -->
        <a class="flex items-center gap-2.5 font-bold text-xl tracking-tight text-slate-900" href="{{ route('home') }}" aria-label="SutharTech home">
            <span class="site-navbar-mark w-8 h-8 rounded-lg bg-gradient-to-tr from-pink-400 to-blue-500 flex items-center justify-center text-white font-bold text-sm shadow-xs">S</span>
            <span>SutharTech</span>
        </a>

        <!-- Desktop Navigation -->
        <nav class="site-navbar-menu hidden md:flex text-sm font-medium text-slate-600" aria-label="Main navigation">
            <a class="{{ request()->routeIs('home') ? 'navbar-link-current' : '' }}" href="{{ route('home') }}">Home</a>
            <a class="{{ request()->routeIs('about') ? 'navbar-link-current' : '' }}" href="{{ route('about') }}">About</a>
            <a class="{{ request()->routeIs('services') ? 'navbar-link-current' : '' }}" href="{{ route('services') }}">Services</a>
            <a class="{{ request()->routeIs('projects') ? 'navbar-link-current' : '' }}" href="{{ route('projects') }}">Projects</a>
            <a class="{{ request()->routeIs('testimonials') ? 'navbar-link-current' : '' }}" href="{{ route('testimonials') }}">Testimonials</a>
            <a class="{{ request()->routeIs('contact') ? 'navbar-link-current' : '' }}" href="{{ route('contact') }}">Contact</a>
        </nav>

        <!-- Desktop CTA & Mobile Hamburger -->
        <div class="site-navbar-actions flex items-center">
            <nav class="site-navbar-resource-card hidden md:flex items-center" aria-label="Blogs and certificates">
                <a class="site-navbar-blog-link {{ request()->routeIs('blogs', 'blog.show') ? 'navbar-link-current' : '' }}" href="{{ route('blogs') }}">
                    <span>Blogs</span>
                </a>
                <a class="site-navbar-certificate-link {{ request()->routeIs('certifications', 'certification.show') ? 'navbar-link-current' : '' }}" href="{{ route('certifications') }}">Certificates</a>
            </nav>
            <a class="site-navbar-cta text-xs" href="{{ route('contact') }}">
                <span>Let's Talk</span>
                <span class="site-navbar-cta-icon material-symbols-outlined" aria-hidden="true">arrow_forward</span>
            </a>

            <!-- Mobile Hamburger Toggle (ONLY ON SMALL SCREENS) -->
            <button
                type="button"
                id="site-mobile-nav-toggle"
                class="md:hidden flex items-center justify-center w-9 h-9 rounded-lg border border-slate-200 bg-white/80 backdrop-blur-md text-slate-800 focus:outline-none transition-colors"
                aria-label="Toggle mobile menu"
                aria-expanded="false"
                aria-controls="site-mobile-menu-drawer"
            >
                <span class="material-symbols-outlined text-xl" id="nav-toggle-icon" aria-hidden="true">menu</span>
            </button>
        </div>
    </div>

    <!-- Mobile Slide-Down Navigation Drawer (STRICTLY HIDDEN ON DESKTOP) -->
    <div
        id="site-mobile-menu-drawer"
        class="hidden md:hidden px-6 pb-6 pt-2 bg-white/95 backdrop-blur-xl border-b border-slate-200/90 shadow-xl transition-all duration-300 ease-in-out"
        role="navigation"
        aria-label="Mobile navigation"
    >
        <div class="flex flex-col gap-1 text-sm font-semibold text-slate-700">
            <a class="px-4 py-2.5 rounded-lg {{ request()->routeIs('home') ? 'bg-slate-100 text-slate-900 font-bold' : 'hover:bg-slate-50' }}" href="{{ route('home') }}">Home</a>
            <a class="px-4 py-2.5 rounded-lg {{ request()->routeIs('about') ? 'bg-slate-100 text-slate-900 font-bold' : 'hover:bg-slate-50' }}" href="{{ route('about') }}">About</a>
            <a class="px-4 py-2.5 rounded-lg {{ request()->routeIs('services') ? 'bg-slate-100 text-slate-900 font-bold' : 'hover:bg-slate-50' }}" href="{{ route('services') }}">Services</a>
            <a class="px-4 py-2.5 rounded-lg {{ request()->routeIs('projects') ? 'bg-slate-100 text-slate-900 font-bold' : 'hover:bg-slate-50' }}" href="{{ route('projects') }}">Projects</a>
            <a class="px-4 py-2.5 rounded-lg {{ request()->routeIs('testimonials') ? 'bg-slate-100 text-slate-900 font-bold' : 'hover:bg-slate-50' }}" href="{{ route('testimonials') }}">Testimonials</a>
            <a class="px-4 py-2.5 rounded-lg {{ request()->routeIs('contact') ? 'bg-slate-100 text-slate-900 font-bold' : 'hover:bg-slate-50' }}" href="{{ route('contact') }}">Contact</a>
            <a class="px-4 py-2.5 rounded-lg {{ request()->routeIs('blogs', 'blog.show') ? 'bg-slate-100 text-slate-900 font-bold' : 'hover:bg-slate-50' }}" href="{{ route('blogs') }}">Blogs</a>
            <a class="px-4 py-2.5 rounded-lg {{ request()->routeIs('certifications', 'certification.show') ? 'bg-slate-100 text-slate-900 font-bold' : 'hover:bg-slate-50' }}" href="{{ route('certifications') }}">Certificates</a>
        </div>

        <div class="pt-4 mt-2 border-t border-slate-100">
            <a class="w-full flex items-center justify-center gap-2 py-3 rounded-full bg-slate-900 text-white font-bold text-xs uppercase tracking-wider shadow-md" href="{{ route('contact') }}">
                <span>Start a Project</span>
                <span class="material-symbols-outlined text-sm" aria-hidden="true">arrow_forward</span>
            </a>
        </div>
    </div>
</header>

<script>
(function() {
    function initMobileNav() {
        const toggleBtn = document.getElementById('site-mobile-nav-toggle');
        const drawer = document.getElementById('site-mobile-menu-drawer');
        const icon = document.getElementById('nav-toggle-icon');

        if (!toggleBtn || !drawer) return;

        function openDrawer() {
            drawer.classList.remove('hidden');
            drawer.classList.add('is-open');
            toggleBtn.setAttribute('aria-expanded', 'true');
            if (icon) icon.textContent = 'close';
        }

        function closeDrawer() {
            drawer.classList.remove('is-open');
            drawer.classList.add('hidden');
            toggleBtn.setAttribute('aria-expanded', 'false');
            if (icon) icon.textContent = 'menu';
        }

        toggleBtn.onclick = function(e) {
            e.stopPropagation();
            if (drawer.classList.contains('is-open')) {
                closeDrawer();
            } else {
                openDrawer();
            }
        };

        // Close on clicking links inside the drawer
        const drawerLinks = drawer.querySelectorAll('a');
        drawerLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                closeDrawer();
            });
        });

        // Close on click outside
        document.addEventListener('click', function(e) {
            if (drawer.classList.contains('is-open') && !drawer.contains(e.target) && e.target !== toggleBtn && !toggleBtn.contains(e.target)) {
                closeDrawer();
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && drawer.classList.contains('is-open')) {
                closeDrawer();
            }
        });

        // Close drawer on resize to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768 && drawer.classList.contains('is-open')) {
                closeDrawer();
            }
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMobileNav);
    } else {
        initMobileNav();
    }
})();
</script>
