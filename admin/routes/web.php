<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\FixedPageController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Admin\ServiceCitySeoController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\WebsiteContentController;
use Illuminate\Support\Facades\Route;

Route::get('/newsletter/confirm/{token}', [NewsletterController::class, 'confirm'])
    ->middleware('throttle:10,1')
    ->name('newsletter.confirm');
Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])
    ->middleware('throttle:10,1')
    ->name('newsletter.unsubscribe');

Route::name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('/login', [AuthController::class, 'login']);
        Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
        Route::post('/forgot-password', [AuthController::class, 'sendResetOtp'])->name('password.email');
        Route::get('/verify-otp', [AuthController::class, 'showVerifyOtp'])->name('password.verify-otp');
        Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('password.verify-otp.post');
        Route::get('/reset-password', [AuthController::class, 'showResetForm'])->name('password.reset-form');
        Route::post('/reset-password', [AuthController::class, 'resetPasswordOtp'])->name('password.update');
    });

    Route::middleware('auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/website-content/{type}', [WebsiteContentController::class, 'index'])->name('website-content.index');
        Route::get('/website-pages', [FixedPageController::class, 'index'])->name('website-pages.index');
        Route::get('/website-pages/{slug}/edit', [FixedPageController::class, 'edit'])->name('website-pages.edit');
        Route::put('/website-pages/{slug}', [FixedPageController::class, 'update'])->name('website-pages.update');
        Route::get('/website-content/{type}/create', [WebsiteContentController::class, 'create'])->name('website-content.create');
        Route::post('/website-content/{type}', [WebsiteContentController::class, 'store'])->name('website-content.store');
        Route::get('/website-content/{type}/{record}/edit', [WebsiteContentController::class, 'edit'])->name('website-content.edit');
        Route::put('/website-content/{type}/{record}', [WebsiteContentController::class, 'update'])->name('website-content.update');
        Route::delete('/website-content/{type}/{record}', [WebsiteContentController::class, 'destroy'])->name('website-content.destroy');
        Route::get('/newsletter', [NewsletterController::class, 'index'])->name('newsletter.index');
        Route::post('/newsletter/campaigns', [NewsletterController::class, 'campaign'])->middleware('throttle:2,1')->name('newsletter.campaign');
        Route::resource('/blogs', AdminBlogController::class);
        Route::patch('/blogs/{blog}/toggle-slider', [AdminBlogController::class, 'toggleSlider'])->name('blogs.toggle-slider');
        Route::resource('/sliders', SliderController::class);
        Route::resource('/galleries', GalleryController::class);
        Route::patch('/galleries/{gallery}/toggle-home', [GalleryController::class, 'toggleHome'])->name('galleries.toggle-home');
        Route::redirect('/services', '/website-content/services')->name('services.index');
        Route::prefix('service-city-seo')->name('service-city-seo.')->group(function () {
            Route::get('/', [ServiceCitySeoController::class, 'index'])->name('index');
            Route::get('/create', [ServiceCitySeoController::class, 'create'])->name('create');
            Route::post('/', [ServiceCitySeoController::class, 'store'])->name('store');
            Route::get('/{serviceCitySeo}', [ServiceCitySeoController::class, 'show'])->name('show');
            Route::get('/{serviceCitySeo}/edit', [ServiceCitySeoController::class, 'edit'])->name('edit');
            Route::put('/{serviceCitySeo}', [ServiceCitySeoController::class, 'update'])->name('update');
            Route::delete('/{serviceCitySeo}', [ServiceCitySeoController::class, 'destroy'])->name('destroy');
            Route::patch('/{serviceCitySeo}/toggle-active', [ServiceCitySeoController::class, 'toggleActive'])->name('toggle-active');
            Route::post('/bulk-generate', [ServiceCitySeoController::class, 'bulkGenerate'])->name('bulk-generate');
            Route::get('/stats/export', [ServiceCitySeoController::class, 'exportStats'])->name('stats.export');
        });

        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/test-razorpay', [SettingController::class, 'testRazorpay'])->name('settings.test-razorpay');
        Route::post('/settings/test-mail', [SettingController::class, 'testMail'])->name('settings.test-mail');
        Route::get('/integrations', [SettingController::class, 'integrations'])->name('integrations.index');
        Route::put('/integrations', [SettingController::class, 'updateIntegrations'])->name('integrations.update');
        Route::post('/integrations/test-whatsapp', [SettingController::class, 'testWhatsApp'])->middleware('throttle:3,1')->name('integrations.test-whatsapp');
        Route::get('/account', [SettingController::class, 'account'])->name('account');
        Route::put('/account/email', [SettingController::class, 'updateEmail'])->name('account.update-email');
        Route::put('/account/password', [SettingController::class, 'updatePassword'])->name('account.update-password');
        Route::put('/account/notifications', [SettingController::class, 'updateNotifications'])->name('account.update-notifications');
        Route::get('/contacts', [AdminContactController::class, 'index'])->name('contacts.index');
        Route::get('/contacts/{contact}', [AdminContactController::class, 'show'])->name('contacts.show');
        Route::delete('/contacts/{contact}', [AdminContactController::class, 'destroy'])->name('contacts.destroy');
        Route::patch('/contacts/{contact}/mark-read', [AdminContactController::class, 'markRead'])->name('contacts.mark-read');
        Route::post('/contacts/mark-all-read', [AdminContactController::class, 'markAllRead'])->name('contacts.mark-all-read');
        Route::resource('/faqs', FaqController::class);
        Route::patch('/faqs/{faq}/toggle-status', [FaqController::class, 'toggleStatus'])->name('faqs.toggle-status');
        Route::resource('/testimonials', TestimonialController::class);
    });
});

Route::redirect('/admin', '/', 301);
