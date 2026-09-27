<?php

use App\Http\Controllers\HighlightController;
use App\Http\Controllers\MeetingController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use App\Models\Meeting;
use App\Models\Highlight;

Route::get('/setup-demo-data', function () {
    try {
        // Temporarily disable foreign key checks in case related tables are blocking the delete
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Delete old records
        Highlight::query()->delete();
        Meeting::query()->delete();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Run the seeder
        Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\MeetingSeeder',
            '--force' => true
        ]);

        return 'Old data deleted and new data seeded successfully! Output: ' . Artisan::output();

    } catch (\Exception $e) {
        // This will print the exact reason for the 500 error to your screen
        return 'Error: ' . $e->getMessage() . ' in ' . $e->getFile() . ' on line ' . $e->getLine();
    }
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
