<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\GameController;
use App\Http\Controllers\API\UserController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

    Route::post('/players', [AuthController::class, 'register'])->name('register');
    Route::post('/players/login', [AuthController::class, 'login'])->name('login');

    Route::middleware(['auth:api'])->group(function(){
      
    Route::put('/players/{id}',  [UserController::class, 'editUsername']);
    //Route::put('/players/{user}', 'UserController@update');
    //Route::delete('/players/{user}', 'UserController@destroy');
});
