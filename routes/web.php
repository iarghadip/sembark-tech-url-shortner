<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\InviteController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\LinkController;

Route::get('/', function () {
    return view('welcome');
});

//Route::get('/links', function () {
//  return view('links');
//})->middleware(['auth', 'verified'])->name('links');

Route::middleware(['auth', 'verified'])->group(function () {
    
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
    
    Route::prefix('links')->name('links.')->group(function () {
        Route::get('/', [LinkController::class, 'index'])->name('index');
        Route::get('/create', [LinkController::class, 'create'])->name('create');
        Route::post('/store', [LinkController::class, 'store'])->name('store');
        Route::get('/edit/{link}', [LinkController::class, 'edit'])->name('edit');
        Route::put('/update/{link}', [LinkController::class, 'update'])->name('update');
        Route::delete('/destroy/{link}', [LinkController::class, 'destroy'])->name('destroy');
    });
    
    Route::get('/forward/{slug}', [LinkController::class, 'forward'])->name('links.forward');

});
    
require __DIR__.'/auth.php';
    