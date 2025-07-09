<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\QueueController as AdminQueueController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\BookingController as UserBookingController;

// Auth
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'store']);
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Route untuk admin
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Resource routes
    Route::resource('doctors', DoctorController::class)->names('doctor');
    Route::resource('schedules', ScheduleController::class);
    Route::resource('bookings', AdminBookingController::class)->names('booking'); // Menggunakan alias

    // Queue management routes
    Route::get('/queue', [AdminQueueController::class, 'index'])->name('queue.index');
    Route::get('/queue/dashboard', [AdminQueueController::class, 'dashboard'])->name('queue.dashboard');
    Route::patch('/queue/{queue}/start', [AdminQueueController::class, 'start'])->name('queue.start');
    Route::patch('/queue/{queue}/complete', [AdminQueueController::class, 'complete'])->name('queue.complete');
    Route::patch('/queue/{queue}/cancel', [AdminQueueController::class, 'cancel'])->name('queue.cancel');
});

// Route untuk user
Route::middleware(['auth', 'is_user'])->prefix('user')->name('user.')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Menggunakan alias untuk BookingController User
    Route::get('/booking', [UserBookingController::class, 'index'])->name('booking.index');
    Route::post('/booking', [UserBookingController::class, 'store'])->name('booking.store');
    Route::get('/history', [UserBookingController::class, 'history'])->name('booking.history');

    // Queue routes for user
    Route::get('/queue/today', [UserBookingController::class, 'todayQueue'])->name('queue.today');
});