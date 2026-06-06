<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\WisataController;
use App\Http\Controllers\Api\V1\KulinerController;
use App\Http\Controllers\Api\V1\NongkrongController;
use App\Http\Controllers\Api\V1\SentimentController;
use App\Http\Controllers\Api\V1\ChatbotController;
use App\Http\Controllers\Api\V1\RecommendationController;
use App\Http\Controllers\Api\V1\PlanningController;
use App\Http\Controllers\Api\V1\SearchController;
use App\Http\Controllers\Api\V1\FeedbackController;
use App\Http\Controllers\RegionController;

Route::prefix('v1')->name('api.v1.')->group(function () {

    // ── AUTH ────────────────────────────────────────────────────────
    Route::prefix('auth')->name('auth.')->middleware('throttle:10,1')->group(function () {
        Route::post('register', [AuthController::class, 'register'])->name('register');
        Route::post('login',    [AuthController::class, 'login'])->name('login');

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('logout',   [AuthController::class, 'logout'])->name('logout');
            Route::get('me',        [AuthController::class, 'me'])->name('me');
            Route::match(['put', 'post'], 'profile',   [AuthController::class, 'updateProfile'])->name('profile');
        });
    });

    // ── PUBLIC ──────────────────────────────────────────────────────
    Route::middleware('throttle:120,1')->group(function () {
        Route::get('wisata',           [WisataController::class, 'index'])->name('wisata.index');
        Route::get('wisata/{kode}',    [WisataController::class, 'show'])->name('wisata.show');

        Route::get('kuliner',          [KulinerController::class, 'index'])->name('kuliner.index');
        Route::get('kuliner/{kode}',   [KulinerController::class, 'show'])->name('kuliner.show');

        Route::get('nongkrong',        [NongkrongController::class, 'index'])->name('nongkrong.index');
        Route::get('nongkrong/{kode}', [NongkrongController::class, 'show'])->name('nongkrong.show');

        Route::get('search',           [SearchController::class, 'index'])->name('search');
        Route::get('regions',          [RegionController::class, 'index'])->name('regions.index');
        Route::post('feedback',        [FeedbackController::class, 'store'])->name('feedback.store');

        // Sentimen summary — publik
        Route::get('sentiment/summary-all',       [SentimentController::class, 'summaryAll'])->name('sentiment.summary_all');
        Route::get('sentiment/summary/{wilayah}', [SentimentController::class, 'summary'])
            ->name('sentiment.summary')
            ->where('wilayah', 'Indramayu|Cirebon|Majalengka|Kuningan');
        
        // Detail sentimen per tempat
        Route::get('sentiment/detail/{kode}', [SentimentController::class, 'show'])->name('sentiment.detail');
        
        // Versi path panjang sesuai request user (redundant tapi membantu frontend)
        Route::get('sentiment/summary/{wilayah}/{tipe}/{kode}', [SentimentController::class, 'show'])
            ->name('sentiment.summary.detail')
            ->where(['wilayah' => 'Indramayu|Cirebon|Majalengka|Kuningan', 'tipe' => 'wisata|kuliner|nongkrong']);
    });

    // ── AI ENDPOINTS ────────────────────────────────────────────────
    Route::middleware(['throttle:120,1', 'fastapi.health'])->group(function () {
        Route::post('chatbot/ask',             [ChatbotController::class, 'ask'])->name('chatbot.ask');
        Route::get('chatbot/history/{token}',  [ChatbotController::class, 'history'])->name('chatbot.history');
        Route::delete('chatbot/history/{token}', [ChatbotController::class, 'destroy'])->name('chatbot.history.destroy');
        Route::post('recommendation',          [RecommendationController::class, 'index'])->name('recommendation.index');
        Route::post('recommendation/planning', [RecommendationController::class, 'planning'])->name('recommendation.planning');
    });

    // ── AUTHENTICATED USER ──────────────────────────────────────────
    Route::middleware(['auth:sanctum', 'throttle:120,1'])->group(function () {
        // History tracking
        Route::get('recommendation/history',  [RecommendationController::class, 'getHistory'])->name('recommendation.history.list');
        Route::post('recommendation/history', [RecommendationController::class, 'trackHistory'])->name('recommendation.history.store');

        // Planning wisata (CRUD personal)
        Route::apiResource('planning', PlanningController::class)->names([
            'index'   => 'planning.index',
            'store'   => 'planning.store',
            'show'    => 'planning.show',
            'update'  => 'planning.update',
            'destroy' => 'planning.destroy',
        ]);

        // User preferences
        Route::get('preferences',  [AuthController::class, 'getPreferences'])->name('preferences.show');
        Route::put('preferences',  [AuthController::class, 'updatePreferences'])->name('preferences.update');
    });

    // ── ADMIN ONLY ──────────────────────────────────────────────────
    Route::middleware(['auth:sanctum', 'role:admin', 'throttle:120,1'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            Route::apiResource('wisata',    WisataController::class)->except(['index', 'show']);
            Route::apiResource('kuliner',   KulinerController::class)->except(['index', 'show']);
            Route::apiResource('nongkrong', NongkrongController::class)->except(['index', 'show']);

            Route::post('sentiment/sync-all', [SentimentController::class, 'syncAll'])
                ->name('sentiment.sync_all');

            Route::post('sentiment/sync/{tipe}/{kode}', [SentimentController::class, 'sync'])
                ->name('sentiment.sync')
                ->where('tipe', 'wisata|kuliner|nongkrong');

        });
});
