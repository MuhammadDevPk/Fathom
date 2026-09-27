<?php

use App\Http\Controllers\HighlightController;
use App\Http\Controllers\MeetingController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::get('meetings', [MeetingController::class, 'index'])->name('meetings.index');
Route::get('meetings/{meeting}', [MeetingController::class, 'show'])->name('meetings.show');
Route::post('meetings/{meeting}/summary', [MeetingController::class, 'generateSummary'])->name('meetings.summary.generate');
Route::post('meetings/{meeting}/highlights', [HighlightController::class, 'store'])->name('meetings.highlights.store');
Route::post('meetings/{meeting}/ask', [MeetingController::class, 'ask'])->name('meetings.ask');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [MeetingController::class, 'index'])->name('dashboard');
});

require __DIR__.'/settings.php';
