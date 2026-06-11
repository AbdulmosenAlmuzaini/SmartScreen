<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScreenController;
use App\Http\Controllers\DashboardController;

// Public Signage Screen Display
Route::get('/screen/{slug}', [ScreenController::class, 'show'])->name('screen.show');

// Language Switcher Route
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

// Admin Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Admin Dashboard
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // Screen Management
    Route::post('/screens', [DashboardController::class, 'screenStore'])->name('screens.store');
    Route::delete('/screens/{screen}', [DashboardController::class, 'screenDestroy'])->name('screens.destroy');

    // Slide Management
    Route::get('/screens/{screen}/slides', [DashboardController::class, 'slidesIndex'])->name('screens.slides');
    Route::post('/screens/{screen}/slides', [DashboardController::class, 'slideStore'])->name('slides.store');
    Route::put('/slides/{slide}', [DashboardController::class, 'slideUpdate'])->name('slides.update');
    Route::delete('/slides/{slide}', [DashboardController::class, 'slideDestroy'])->name('slides.destroy');
    Route::post('/screens/{screen}/slides/reorder', [DashboardController::class, 'slideReorder'])->name('slides.reorder');
});
