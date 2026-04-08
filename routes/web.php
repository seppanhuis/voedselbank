<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KlantController;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::get('/Klant', [KlantController::class, 'index'])->name('klant.index');
Route::get('/Klant/create', [KlantController::class, 'create'])->name('klant.create');
Route::post('/Klant', [KlantController::class, 'store'])->name('klant.store');

require __DIR__.'/settings.php';
