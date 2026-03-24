<?php

use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Example: This route requires the 'inventory.stocks.view' permission
    Route::get('/inventory/stocks', function () {
        return "Stocks View Page";
    })->middleware('permission:inventory.stocks.view');

    Route::resource('roles', RoleController::class)->only(['index', 'store', 'update']);
    Route::resource('users', UserController::class);
});

require __DIR__.'/auth.php';
