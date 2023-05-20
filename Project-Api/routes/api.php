<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\GameController;
use App\Http\Controllers\API\UserController;

    Route::post('/players', [AuthController::class, 'register'])->name('register');
    Route::post('/players/login', [AuthController::class, 'login'])->name('login');

    Route::middleware(['auth:api'])->group(function(){
      
    Route::put('/players/{id}',  [UserController::class, 'editUsername']);
    Route::post('/players/{id}/games', [GameController::class, 'diceRoll']);
    Route::delete('/players/{id}/games', [GameController::class, 'destroy']);
    Route::get('/players', [UserController::class, 'userRanking']);
    Route::get('/players/{id}/games', [UserController::class, 'userGames']);
    Route::get('/players/ranking', [GameController::class, 'rankingAverage']);
    Route::get('/players/ranking/loser', [GameController::class, 'loser']);
    Route::get('/players/ranking/winner', [GameController::class, 'winner']);
   
    });
