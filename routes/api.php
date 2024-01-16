<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API as API;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login', [API\AuthController::class, 'login']);
Route::post('register', [API\AuthController::class, 'register']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('dish-list', [API\DishController::class, 'index']);
    Route::get('dish-detail/{id}', [API\DishController::class, 'show']);
    Route::post('dish', [API\DishController::class, 'store']);
    Route::get('delete/{id}', [API\DishController::class, 'destroy']);
    Route::get('reviews/{id}', [API\ReviewController::class, 'index']);
    Route::post('reviews', [API\ReviewController::class, 'store']);
    Route::post('/logout', [API\AuthController::class, 'logout']);
});
