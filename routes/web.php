<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Route για login/logout/register (Το Laravel Breeze το προσθέτει αυτόματα)
require __DIR__.'/auth.php';

// Προστατευμένα routes (Απαιτούν login)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('clients', ClientController::class);
    Route::post('clients/{client}/renew', [ClientController::class, 'renew'])->name('clients.renew');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/', function () {
        return redirect('/dashboard'); // Ή μπορείς να δείξεις μια άλλη σελίδα
    });
});






