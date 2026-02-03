<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\BookingController::class, 'index'])->name('home');
Route::view('/offers', 'pages.offers')->name('offers');

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
    
    // Orders Management
    Route::get('orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::post('orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    
    // Tickets Management
    Route::get('tickets', [\App\Http\Controllers\Admin\TicketController::class, 'index'])->name('tickets.index');
    Route::get('tickets/{ticket}', [\App\Http\Controllers\Admin\TicketController::class, 'show'])->name('tickets.show');
    Route::post('tickets/{ticket}/check-in', [\App\Http\Controllers\Admin\TicketController::class, 'checkIn'])->name('tickets.checkIn');
    
    // Users Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/booking', [\App\Http\Controllers\BookingController::class, 'index'])->name('booking.index');
    Route::get('/movie/{movie}', [\App\Http\Controllers\BookingController::class, 'movie'])->name('booking.movie');
    Route::get('/booking/{showtime}', [\App\Http\Controllers\BookingController::class, 'show'])->name('booking.show');
    Route::post('/booking/{showtime}/lock', [\App\Http\Controllers\BookingController::class, 'lockSeat'])->name('booking.lock');
    Route::post('/booking/{showtime}/unlock', [\App\Http\Controllers\BookingController::class, 'unlockSeat'])->name('booking.unlock');
    Route::post('/booking/{showtime}/payment', [\App\Http\Controllers\BookingController::class, 'payment'])->name('booking.payment');
    Route::post('/booking/{showtime}/checkout', [\App\Http\Controllers\BookingController::class, 'checkout'])->name('booking.checkout');
    
    // My Tickets
    Route::get('/my-tickets', [\App\Http\Controllers\BookingController::class, 'myTickets'])->name('my-tickets');
    Route::delete('/my-tickets/{order}/cancel', [\App\Http\Controllers\BookingController::class, 'cancelOrder'])->name('my-tickets.cancel');
});

require __DIR__.'/auth.php';
