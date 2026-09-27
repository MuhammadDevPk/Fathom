<?php

use App\Http\Controllers\MeetingController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome')->name('home');

Route::get('meetings', [MeetingController::class, 'index'])->name('meetings.index');
Route::get('meetings/{meeting}', [MeetingController::class, 'show'])->name('meetings.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [MeetingController::class, 'index'])->name('dashboard');
});

require __DIR__.'/settings.php';
