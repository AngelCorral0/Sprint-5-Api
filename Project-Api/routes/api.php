<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\API\UserController;

    Route::post('/players', [AuthController::class, 'register'])->name('register');
    Route::post('/players/login', [AuthController::class, 'login'])->name('login');

    Route::middleware(['auth:api'])->group(function(){

    Route::post('players/logout', [AuthController::class, 'logout'])->name('logout');

      
    Route::put('/players/{id}',  [UserController::class, 'editUsername']);
    Route::post('/players/{id}/games', [GameController::class, 'diceRoll'])->name('diceRoll');
    Route::delete('/players/{id}/games', [GameController::class, 'destroy'])->name('destroy');
    Route::get('/players', [UserController::class, 'userRanking'])->name('userRanking');
    Route::get('/players/{id}/games', [GameController::class, 'userGames'])->name('userGames');
    Route::get('/players/ranking', [GameController::class, 'rankingAverage'])->name('rankingAverage');
    Route::get('/players/ranking/loser', [GameController::class, 'loser'])->name('loser');
    Route::get('/players/ranking/winner', [GameController::class, 'winner'])->name('winner');
   
    });
