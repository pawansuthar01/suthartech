<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgencyPageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\PublicContentController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\FormBotCheckController;

/*
|--------------------------------------------------------------------------
| Web Routes - SutharTech Enterprise Agency
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// SEO Engine Routes
Route::get('/sitemap.xml', [SitemapController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SitemapController::class, 'robots'])->name('robots');

// Public content managed through the SutharTech admin panel.
Route::get('/blogs', [PublicContentController::class, 'blogs'])->name('blogs');
Route::get('/blog/{slug}', [PublicContentController::class, 'blog'])->name('blog.show');
Route::get('/projects/{slug}', [PublicContentController::class, 'project'])->name('project.show');
Route::get('/services/{slug}', [AgencyPageController::class, 'service'])->name('service.show');
Route::get('/certifications', [PublicContentController::class, 'certifications'])->name('certifications');
Route::get('/certifications/verify', [PublicContentController::class, 'verifyCertification'])->name('certifications.verify');
Route::get('/certifications/{slug}', [PublicContentController::class, 'certification'])->name('certification.show');

// Core Agency Pages
Route::get('/services', [AgencyPageController::class, 'services'])->name('services');
Route::get('/projects', [AgencyPageController::class, 'projects'])->name('projects');
Route::get('/about', [AgencyPageController::class, 'about'])->name('about');
Route::get('/testimonials', [AgencyPageController::class, 'testimonials'])->name('testimonials');
Route::get('/contact', [AgencyPageController::class, 'contact'])->name('contact');
Route::post('/form-bot-protection/check', FormBotCheckController::class)->middleware('throttle:20,1')->name('form-bot.check');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:10,1')->name('contact.store');
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->middleware('throttle:5,1')->name('newsletter.subscribe');

Route::redirect('/insights', '/blogs', 301)->name('insights');

// Catch-all CMS pages stay last so they cannot shadow app routes.
Route::get('/{slug}', [PublicContentController::class, 'page'])
    ->where('slug', '^(?!(?:admin|api|storage|login|register|up|insights|home)$)[a-z0-9][a-z0-9-]*$')
    ->name('pages.show');
