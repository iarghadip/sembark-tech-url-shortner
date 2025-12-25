<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\InviteController;
use App\Http\Controllers\CompanyController;

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
        Route::post('/accept/{invite}', [InviteController::class, 'accept'])->name('accept');
        Route::get('/create', [InviteController::class, 'create'])->name('create');
        Route::post('/store', [InviteController::class, 'store'])->name('store');
        Route::delete('/destroy/{invite}', [InviteController::class, 'destroy'])->name('destroy');
    });
    
    Route::prefix('company')->name('company.')->group(function () {
        Route::get('/', [CompanyController::class, 'index'])->name('index');
        Route::get('/edit/{company}', [CompanyController::class, 'edit'])->name('edit');
        Route::put('/update/{company}', [CompanyController::class, 'update'])->name('update');
        Route::delete('/destroy/{company}', [CompanyController::class, 'destroy'])->name('destroy');
        Route::post('/remove/{company}/{user}', [CompanyController::class, 'remove'])->name('remove');
    });
    
    Route::resource('company', CompanyController::class);
    Route::post('company/{company}/leave', [CompanyController::class, 'leave'])->name('company.leave');
    Route::delete('/company/{company}/user/{user}', [CompanyController::class, 'removeUser'])
        ->name('company.user.destroy')
        ->middleware('permission:can-see-self-org');

});
    
require __DIR__.'/auth.php';
    