<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\BookingController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('movies', \App\Http\Controllers\MovieController::class);
    Route::resource('cinemas', \App\Http\Controllers\CinemaController::class);
    Route::resource('rooms', \App\Http\Controllers\RoomController::class);
    Route::resource('showtimes', \App\Http\Controllers\ShowtimeController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/booking', [\App\Http\Controllers\BookingController::class, 'index'])->name('booking.index');
    Route::get('/movie/{movie}', [\App\Http\Controllers\BookingController::class, 'movie'])->name('booking.movie');
    Route::get('/booking/{showtime}', [\App\Http\Controllers\BookingController::class, 'show'])->name('booking.show');
    Route::post('/booking/{showtime}/lock', [\App\Http\Controllers\BookingController::class, 'lockSeat'])->name('booking.lock');
    Route::post('/booking/{showtime}/unlock', [\App\Http\Controllers\BookingController::class, 'unlockSeat'])->name('booking.unlock');
    Route::post('/booking/{showtime}/checkout', [\App\Http\Controllers\BookingController::class, 'checkout'])->name('booking.checkout');
});

require __DIR__.'/auth.php';
