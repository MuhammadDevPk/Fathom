<?php

use App\Http\Controllers\HighlightController;
use App\Http\Controllers\MeetingController;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Artisan;

Route::get('/setup-demo-data', function () {
    // Wipes the old database, runs migrations, and executes your updated MeetingSeeder
    Artisan::call('migrate:fresh', [
        '--seed' => true,
        '--force' => true
    ]);

    return 'Database refreshed and seeded successfully! Output: ' . Artisan::output();
});

Route::inertia('/', 'Welcome')->name('home');
Route::get('demo/meeting', [MeetingController::class, 'demo'])->name('demo.meeting');
Route::post('demo/login', [MeetingController::class, 'demoLogin'])->middleware('throttle:10,1')->name('demo.login');
Route::get('share/{meeting}', [MeetingController::class, 'share'])->name('meetings.share');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [MeetingController::class, 'index'])->name('dashboard');
    Route::get('meetings', [MeetingController::class, 'index'])->name('meetings.index');
    Route::get('meetings/{meeting}', [MeetingController::class, 'show'])->name('meetings.show');
    Route::post('meetings/{meeting}/summary', [MeetingController::class, 'generateSummary'])->name('meetings.summary.generate');
    Route::post('meetings/{meeting}/highlights', [HighlightController::class, 'store'])->name('meetings.highlights.store');
    Route::post('meetings/{meeting}/ask', [MeetingController::class, 'ask'])->name('meetings.ask');
    Route::post('meetings/{meeting}/action-items/toggle', [MeetingController::class, 'toggleActionItem'])->name('meetings.action-items.toggle');
});

require __DIR__.'/settings.php';
