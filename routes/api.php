<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/scrape-url', [\App\Http\Controllers\WishlistController::class, 'scrapeUrl']);

Route::middleware('auth:sanctum')->patch('/wishlist/{id}', [\App\Http\Controllers\WishlistController::class, 'update']);
Route::middleware('web')->delete('/wishlist/{id}', [\App\Http\Controllers\WishlistController::class, 'destroy']);
