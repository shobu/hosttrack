<?php

use App\Http\Controllers\ClientController;

Route::resource('clients', ClientController::class);
Route::post('clients/{client}/renew', [ClientController::class, 'renew'])->name('clients.renew');
