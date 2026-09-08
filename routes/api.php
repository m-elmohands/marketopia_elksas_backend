<?php

use App\Http\Controllers\Api\V1\ArticleController;
use App\Http\Controllers\Api\V1\HomeController;
use App\Http\Controllers\Api\V1\MotorcycleController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::get('home', HomeController::class)->name('home');
    Route::get('articles', [ArticleController::class, 'index'])->name('articles.index');
    Route::get('articles/{article}', [ArticleController::class, 'show'])->name('articles.show');
    Route::get('motorcycles', [MotorcycleController::class, 'index'])->name('motorcycles.index');
    Route::get('motorcycles/{motorcycle}', [MotorcycleController::class, 'show'])->name('motorcycles.show');
});
