<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;


use App\Http\Controllers\AuthController;

// Root Route
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Public Ticket Routes
Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
Route::get('/tickets/track', [TicketController::class, 'showTrackForm'])->name('tickets.track.form');
Route::post('/tickets/track', [TicketController::class, 'track'])->name('tickets.track');

// Guest Authentication Routes (Admin Only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Protected Admin Routes
Route::middleware('auth')->group(function () {
    Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/export/excel', [TicketController::class, 'exportExcel'])->name('tickets.export.excel');
    Route::get('/tickets/export/pdf', [TicketController::class, 'exportPDF'])->name('tickets.export.pdf');
    Route::get('/tickets/{id}', [TicketController::class, 'show'])->name('tickets.show');
    Route::put('/tickets/{id}', [TicketController::class, 'update'])->name('tickets.update');

    // User Management (Super Admin Only)
    Route::resource('users', \App\Http\Controllers\UserController::class);
});
