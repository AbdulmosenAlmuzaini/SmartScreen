<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScreenController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// Public Landing Page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Public Signage Screen Display
Route::get('/screen/{slug}', [ScreenController::class, 'show'])->name('screen.show');

// Language Switcher Route
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        session()->put('locale', $locale);
    }
    return redirect()->back();
})->name('lang.switch');

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Email Verification Notice
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    // Email Verification Handler
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect()->route('dashboard');
    })->middleware('signed')->name('verification.verify');

    // Resend Email Verification
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'verification-link-sent');
    })->middleware('throttle:6,1')->name('verification.send');

    // Profile Settings (Accessible to unverified users so they can correct email typos)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

// Protected Admin Dashboard (Must be authenticated AND verified)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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

