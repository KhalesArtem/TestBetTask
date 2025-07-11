<?php

use App\Http\Controllers\LinkController;
use App\Http\Controllers\GameController;
use Illuminate\Support\Facades\Route;

Route::post('/links/{link:token}/renew', [LinkController::class, 'renew'])->name('api.link.renew');
Route::post('/links/{link:token}/deactivate', [LinkController::class, 'deactivate'])->name('api.link.deactivate');
Route::post('/game/{link:token}/play', [GameController::class, 'play'])->name('api.game.play');
Route::get('/game/{link:token}/history', [GameController::class, 'history'])->name('api.game.history');