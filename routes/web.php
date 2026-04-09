<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KlantController;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

// ====Klant routes==============================================================================
Route::get('/Klant', [KlantController::class, 'index'])->name('klant.index');
Route::get('/Klant/create', [KlantController::class, 'create'])->name('klant.create');
Route::post('/Klant', [KlantController::class, 'store'])->name('klant.store');
Route::get('/Klant/{id}/edit', [KlantController::class, 'edit'])->name('klant.edit');
Route::put('/Klant/{id}', [KlantController::class, 'update'])->name('klant.update');
Route::delete('/Klant/{id}', [KlantController::class, 'destroy'])->name('klant.destroy');
//================================================================================================

require __DIR__.'/settings.php';
