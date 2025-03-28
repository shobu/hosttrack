<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RenewalLogController;


// Route για login/logout/register (Το Laravel Breeze το προσθέτει αυτόματα)
require __DIR__.'/auth.php';

// Αν κάποιος πάει στη ρίζα του site, ανακατευθύνεται στο dashboard
Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : redirect('/login');
});

// Προστατευμένα routes (Απαιτούν login)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Clients Routes
    Route::resource('clients', ClientController::class);
    Route::post('/clients/{client}/renew', [ClientController::class, 'renew'])->name('clients.renew');


    // **Inactive Clients Route (χωρίς το /clients/)**
    Route::get('/inactive-list', [ClientController::class, 'inactive'])->name('clients.inactive');
    Route::patch('/clients/{client}/deactivate', [ClientController::class, 'deactivate'])->name('clients.deactivate');
    Route::patch('/clients/{client}/activate', [ClientController::class, 'activate'])->name('clients.activate');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::delete('/payments/{payment}', [ClientController::class, 'deletePayment'])->name('payments.delete');
    Route::delete('/renewals/{renewal}', [RenewalLogController::class, 'destroy'])->name('renewals.delete');

    Route::resource('servers', App\Http\Controllers\ServerController::class);

});
