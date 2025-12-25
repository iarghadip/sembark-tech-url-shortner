<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\InviteController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('invites')->name('invites.')->group(function () {
        Route::get('/', [InviteController::class, 'index'])->name('index');
        Route::post('/{invite}/accept', [InviteController::class, 'accept'])->name('accept');
        Route::get('/create', [InviteController::class, 'create'])->name('create');
        Route::post('/store', [InviteController::class, 'store'])->name('store');
    });
});

require __DIR__.'/auth.php';
