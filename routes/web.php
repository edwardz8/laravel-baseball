<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PlayerController;

Route::get('/players', [PlayerController::class, 'index'])->name('players.index');

Route::get('/', function () {
    return view('welcome');
});
