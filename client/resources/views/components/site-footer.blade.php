<footer class="site-footer">
    <div class="site-footer-inner max-w-7xl mx-auto px-6 md:px-16 grid grid-cols-1 md:grid-cols-12 gap-8">
        <div class="site-footer-brand md:col-span-4 flex flex-col gap-3">
            <div class="site-footer-brand-lockup flex items-center gap-2.5 font-bold text-lg text-white">
                <span class="site-footer-mark w-7 h-7 rounded-lg bg-gradient-to-tr from-pink-400 to-blue-500 flex items-center justify-center text-white font-bold text-xs">S</span>
                <span class="site-footer-name">SutharTech</span>
            </div>
            <p class="site-footer-description text-slate-400 text-xs leading-relaxed max-w-sm">
                Engineering high-performance digital products for modern ambitious businesses.
            </p>
        </div>

        <div class="site-footer-column md:col-span-2 flex flex-col gap-2 text-xs">
            <span class="site-footer-heading font-bold text-white uppercase tracking-wider mb-1">Company</span>
            <a class="site-footer-link text-slate-400" href="{{ route('about') }}">About</a>
            <a class="site-footer-link text-slate-400" href="{{ route('services') }}">Services</a>
            <a class="site-footer-link text-slate-400" href="{{ route('projects') }}">Projects</a>
            <a class="site-footer-link text-slate-400" href="{{ route('certifications') }}">Certificates</a>
        </div>

        <div class="site-footer-column md:col-span-3 flex flex-col gap-2 text-xs">
            <span class="site-footer-heading font-bold text-white uppercase tracking-wider mb-1">Connect</span>
            <a class="site-footer-link text-slate-400" href="{{ route('testimonials') }}">Testimonials</a>
            <a class="site-footer-link text-slate-400" href="{{ route('contact') }}">Start a project</a>
            <a class="site-footer-link text-slate-400" href="mailto:hello@suthartech.com">hello@suthartech.com</a>
        </div>

        <div class="site-footer-column md:col-span-3 flex flex-col gap-2 text-xs">
            <span class="site-footer-heading font-bold text-white uppercase tracking-wider mb-1">SEO Engine</span>
            <a class="site-footer-link text-slate-400" href="{{ route('sitemap') }}" target="_blank" rel="noreferrer">XML Sitemap</a>
            <a class="site-footer-link text-slate-400" href="{{ route('robots') }}" target="_blank" rel="noreferrer">Robots.txt</a>
        </div>

    </div>
    <div class="site-footer-wordmark" aria-hidden="true">SutharTech</div>
    <div class="site-footer-bottom max-w-7xl mx-auto px-6 md:px-16 border-t border-slate-800 pt-6 mt-4 flex flex-col sm:flex-row justify-between items-center text-xs text-slate-500 gap-4">
        <p>&copy; {{ date('Y') }} SutharTech. All rights reserved.</p>
        <p class="site-footer-status"><span aria-hidden="true"></span> Built for ambitious teams</p>
    </div>
</footer>
