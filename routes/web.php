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
Route::get('/', [UserController::class, "showCorrectHomepage"])->name('home');
Route::get('/login', [UserController::class, "showLoginForm"])->middleware('guest')->name('login');
Route::get('/register', [UserController::class, "showRegisterForm"])->middleware('guest')->name('register');
Route::post('/register', [UserController::class, "register"])->middleware('guest');
Route::post('/login', [UserController::class, "login"]);
Route::post('/logout', [UserController::class, "logout"])->middleware('mustBeLoggedIn');
Route::get('/manage-avatar', [UserController::class, "showProfileForm"])->middleware('mustBeLoggedIn')->name('profile.update');
Route::put('/manage-avatar', [UserController::class, "updateProfile"])->middleware('mustBeLoggedIn')->name('profile.update');

// Wishlist Routes
Route::get('/add-wish', [WishlistController::class, "showCreateForm"])->middleware('mustBeLoggedIn');
Route::post('/add-wish', [WishlistController::class, "storeNewWish"])->middleware('mustBeLoggedIn');
Route::get('/wishlist', [WishlistController::class, 'index'])->middleware('mustBeLoggedIn')->name('wishlist.index');

// Edit Wish Route
Route::get('/wish/{wish}/edit', [WishlistController::class, 'edit'])->middleware('mustBeLoggedIn');
Route::post('/wish/{wish}', [WishlistController::class, 'updateWish'])->middleware('mustBeLoggedIn');

// Profile related Routes
Route::get('/profile/{user:username}', [UserController::class, "profile"])->middleware('mustBeLoggedIn')->name('profile.show');

// Gift Exchange Dashboard Route
use App\Http\Controllers\GiftExchangeController;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/gift-exchange', [GiftExchangeController::class, 'dashboard'])->name('gift-exchange.dashboard');
    Route::post('/gift-exchange/create', [GiftExchangeController::class, 'createEventWeb'])->name('gift-exchange.create');
Route::post('/gift-exchange/{event}/invite', [GiftExchangeController::class, 'inviteParticipantsWeb'])->name('gift-exchange.invite');
Route::get('/gift-exchange/invitations/{token}', [GiftExchangeController::class, 'showInvitation'])->name('gift-exchange.showInvitation');
Route::post('/gift-exchange/invitations/{token}/respond', [GiftExchangeController::class, 'respondToInvitationWeb'])->name('gift-exchange.respondToInvitationWeb');
});
