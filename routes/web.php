<?php

use App\Http\Controllers\HighlightController;
use App\Http\Controllers\MeetingController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/run-seeder', function () {
    try {
        Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\MeetingSeeder',
        ]);

        return 'Seeder executed successfully! Output: '.Artisan::output();
    } catch (Exception $e) {
        return 'Error: '.$e->getMessage().' in '.$e->getFile().' on line '.$e->getLine();
    }
});

Route::inertia('/', 'Welcome')->name('home');
Route::get('demo/meeting', [MeetingController::class, 'demo'])->name('demo.meeting');
Route::post('demo/login', [MeetingController::class, 'demoLogin'])->middleware('throttle:10,1')->name('demo.login');
Route::get('share/{meeting}', [MeetingController::class, 'share'])->name('meetings.share');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', [MeetingController::class, 'index'])->name('dashboard');
    Route::get('meetings', [MeetingController::class, 'index'])->name('meetings.index');
    Route::delete('meetings/{meeting}', [MeetingController::class, 'destroy'])->name('meetings.destroy');
    Route::get('meetings/{meeting}', [MeetingController::class, 'show'])->name('meetings.show');
    Route::post('meetings/{meeting}/summary', [MeetingController::class, 'generateSummary'])->name('meetings.summary.generate');
    Route::post('meetings/{meeting}/highlights', [HighlightController::class, 'store'])->name('meetings.highlights.store');
    Route::post('meetings/{meeting}/ask', [MeetingController::class, 'ask'])->name('meetings.ask');
    Route::post('meetings/{meeting}/action-items/toggle', [MeetingController::class, 'toggleActionItem'])->name('meetings.action-items.toggle');
});

require __DIR__.'/settings.php';
