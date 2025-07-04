<?php

use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('tickets.index');
});


Auth::routes();
use App\Http\Controllers\Auth\LoginController;

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
    Route::get('/api/tickets/user', [TicketController::class, 'getUserTickets'])->name('tickets.user');
    Route::get('/api/top-support-agents', [TicketController::class, 'topSupportAgents'])->name('api.top-agents');
});



// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
