<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AgencyPageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SitemapController;

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

// Core Agency Pages
Route::get('/services', [AgencyPageController::class, 'services'])->name('services');
Route::get('/projects', [AgencyPageController::class, 'projects'])->name('projects');
Route::get('/about', [AgencyPageController::class, 'about'])->name('about');
Route::get('/testimonials', [AgencyPageController::class, 'testimonials'])->name('testimonials');
Route::get('/contact', [AgencyPageController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->middleware('throttle:10,1')->name('contact.store');

// Preserve the previous insights URL as a useful legacy alias.
Route::redirect('/insights', '/testimonials', 301)->name('insights');
