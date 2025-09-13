<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InterviewController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// Homepage - redirect to dashboard if authenticated, otherwise show welcome
Route::get('/', function () {
    return auth()->check() 
        ? redirect()->route('dashboard') 
        : view('welcome');
});

// Authentication Routes
require __DIR__.'/auth.php';

// Authenticated Routes
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile Routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // Register role middleware
    Route::aliasMiddleware('role', \App\Http\Middleware\RoleMiddleware::class);

    // Interview Routes
    Route::prefix('interviews')->name('interviews.')->group(function () {
        // Public routes (all authenticated users)
        Route::get('/', [InterviewController::class, 'index'])->name('index');
        Route::get('/{interview}', [InterviewController::class, 'show'])->name('show');

        // Admin/Reviewer only routes
        Route::middleware(['role:admin,reviewer'])->group(function () {
            Route::get('/create', [InterviewController::class, 'create'])->name('create');
            Route::post('/', [InterviewController::class, 'store'])->name('store');
            
            // Interview-specific admin/reviewer routes
            Route::prefix('{interview}')->group(function () {
                Route::get('/edit', [InterviewController::class, 'edit'])->name('edit');
                Route::put('/', [InterviewController::class, 'update'])->name('update');
                Route::delete('/', [InterviewController::class, 'destroy'])->name('destroy');
                Route::get('/invite', [InterviewController::class, 'invite'])->name('invite');
                Route::post('/invite', [InterviewController::class, 'inviteCandidates'])->name('invite.store');
            });
        });

        // Candidate-only routes
        Route::middleware(['role:candidate'])->group(function () {
            Route::post('{interview}/start', [InterviewController::class, 'start'])->name('start');
            Route::post('{interview}/{question}/answer', [InterviewController::class, 'submitAnswer'])->name('answer');
        });
    });

    // Review Routes
    Route::prefix('reviews')->name('reviews.')->group(function () {
        Route::get('/', [ReviewController::class, 'index'])->name('index');
        
        // Interview-specific reviews
        Route::get('/interview/{interview?}', [ReviewController::class, 'index'])->name('interview');
        
        // Submission routes
        Route::prefix('submission/{submission}')->group(function () {
            Route::get('/', [ReviewController::class, 'show'])->name('show');
            Route::post('/review', [ReviewController::class, 'storeReview'])->name('store');
            Route::get('/download', [ReviewController::class, 'downloadSubmission'])->name('download');
        });
    });
});
