<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Internal\ContentController;

Route::middleware('internal.content')->prefix('internal/content')->group(function (): void {
    Route::get('fixed-pages', [ContentController::class, 'fixedPages']);
    Route::put('fixed-pages/{slug}', [ContentController::class, 'updateFixedPage']);
    Route::get('{type}', [ContentController::class, 'index']);
    Route::post('{type}', [ContentController::class, 'store']);
    Route::get('{type}/{record}', [ContentController::class, 'show']);
    Route::put('{type}/{record}', [ContentController::class, 'update']);
    Route::delete('{type}/{record}', [ContentController::class, 'destroy']);
});
