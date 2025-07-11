<?php

use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LinkController;
use Illuminate\Support\Facades\Route;

Route::get('/', [RegisterController::class, 'index'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::get('/a/{link:token}', [LinkController::class, 'show'])->name('link.show');
