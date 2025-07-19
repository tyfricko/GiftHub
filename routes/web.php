<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// User Routes
Route::get('/', [UserController::class, "showCorrectHomepage"])->name('login');
Route::post('/register', [UserController::class, "register"])->middleware('guest');
Route::post('/login', [UserController::class, "login"]);
Route::post('/logout', [UserController::class, "logout"])->middleware('mustBeLoggedIn');
Route::post('manage-profiel', [UserController::class, "showProfileForm"])->middleware('mustBeLoggedIn');

// Wishlist Routes
Route::get('/add-wish', [WishlistController::class, "showCreateForm"])->middleware('mustBeLoggedIn');
Route::post('/add-wish', [WishlistController::class, "storeNewWish"])->middleware('mustBeLoggedIn');

// Edit Wish Route
Route::get('/wish/{wish}/edit', [WishlistController::class, 'edit'])->middleware('mustBeLoggedIn');
Route::post('/wish/{wish}', [WishlistController::class, 'updateWish'])->middleware('mustBeLoggedIn');

// Profile related Routes
Route::get('/profile/{user:username}', [UserController::class, "profile"])->middleware('mustBeLoggedIn');
