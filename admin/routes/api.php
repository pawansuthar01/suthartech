<?php

use App\Http\Controllers\Admin\NewsletterController;
use App\Http\Controllers\Api\InternalFormController;
use Illuminate\Support\Facades\Route;

Route::post('newsletter/subscribe', [NewsletterController::class, 'subscribe'])->middleware('throttle:5,1');
Route::get('internal/forms/protection', [InternalFormController::class, 'protectionSettings'])->middleware(['internal.forms', 'throttle:60,1']);
Route::post('internal/forms/contact', [InternalFormController::class, 'contact'])->middleware(['internal.forms', 'throttle:30,1']);
