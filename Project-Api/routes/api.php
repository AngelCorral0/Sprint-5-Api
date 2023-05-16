<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\GameController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();

    Route::post('/players', [AuthController::class, 'register'])->name('register');
    Route::post('/players/login', [AuthController::class, 'login'])->name('login');


    Route::get('/players/{user}', 'UserController@show');
    Route::put('/players/{user}', 'UserController@update');
    Route::delete('/players/{user}', 'UserController@destroy');
//});
