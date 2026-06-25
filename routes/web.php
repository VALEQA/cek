<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\DashboardController;

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/login', [AuthController::class, 'tampilLogin'])->name('login');
Route::post('/login', [AuthController::class, 'prosesLogin']);
Route::get('/register', [AuthController::class, 'tampilRegister'])->name('register');
Route::post('/register', [AuthController::class, 'prosesRegister']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/booking', [BookingController::class, 'index'])->name('booking');
    Route::get('/booking/paket-id', [BookingController::class, 'getPaketId'])->name('booking.paket_id');
    Route::post('/booking', [BookingController::class, 'prosesBooking'])->name('booking.proses');
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/dashboard/proses/{action}/{id}', [AdminController::class, 'prosesAksi'])->name('admin.dashboard.proses');
});