<?php

use App\Http\Controllers\LeverancierController;
use App\Http\Controllers\KlantController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\VoedselpakketController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

// ====Klant routes==============================================================================
Route::get('/Klant', [KlantController::class, 'index'])->middleware(['auth', 'verified'])->name('klant.index');
Route::get('/Klant/create', [KlantController::class, 'create'])->middleware(['auth', 'verified'])->name('klant.create');
Route::post('/Klant', [KlantController::class, 'store'])->middleware(['auth', 'verified'])->name('klant.store');
Route::get('/Klant/{id}/edit', [KlantController::class, 'edit'])->middleware(['auth', 'verified'])->name('klant.edit');
Route::put('/Klant/{id}', [KlantController::class, 'update'])->middleware(['auth', 'verified'])->name('klant.update');
Route::delete('/Klant/{id}', [KlantController::class, 'destroy'])->middleware(['auth', 'verified'])->name('klant.destroy');
//================================================================================================

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/Leverancier', [LeverancierController::class, 'index'])->name('leverancier.index');
    Route::get('/Leverancier/create', [LeverancierController::class, 'create'])->name('leverancier.create');
    Route::post('/Leverancier', [LeverancierController::class, 'store'])->name('leverancier.store');
    Route::get('/Leverancier/{id}/edit', [LeverancierController::class, 'edit'])->name('leverancier.edit');
    Route::put('/Leverancier/{id}', [LeverancierController::class, 'update'])->name('leverancier.update');
    Route::delete('/Leverancier/{id}', [LeverancierController::class, 'destroy'])->name('leverancier.destroy');

    Route::get('/Voorraad', [ProductController::class, 'index'])->name('voorraad.index');
    Route::get('/Voorraad/create', [ProductController::class, 'create'])->name('voorraad.create');
    Route::post('/Voorraad', [ProductController::class, 'store'])->name('voorraad.store');
    Route::get('/Voorraad/{id}/edit', [ProductController::class, 'edit'])->name('voorraad.edit');
    Route::put('/Voorraad/{id}', [ProductController::class, 'update'])->name('voorraad.update');
    Route::delete('/Voorraad/{id}', [ProductController::class, 'destroy'])->name('voorraad.destroy');

    Route::get('/Voedselpakket', [VoedselpakketController::class, 'index'])->name('voedselpakket.index');
    Route::get('/Voedselpakket/create', [VoedselpakketController::class, 'create'])->name('voedselpakket.create');
    Route::post('/Voedselpakket', [VoedselpakketController::class, 'store'])->name('voedselpakket.store');
    Route::get('/Voedselpakket/{id}', [VoedselpakketController::class, 'show'])->name('voedselpakket.show');
    Route::get('/Voedselpakket/{id}/edit', [VoedselpakketController::class, 'edit'])->name('voedselpakket.edit');
    Route::put('/Voedselpakket/{id}', [VoedselpakketController::class, 'update'])->name('voedselpakket.update');
    Route::delete('/Voedselpakket/{id}', [VoedselpakketController::class, 'destroy'])->name('voedselpakket.destroy');
});

require __DIR__.'/settings.php';
