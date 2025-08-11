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
Route::put('/wish/{wish}', [WishlistController::class, 'updateWish'])->middleware('mustBeLoggedIn');

// Profile related Routes
// Note: explicit auth-protected profile/tab routes moved below to avoid conflicting with the parameterized profile route.

// Gift Exchange Dashboard Route
use App\Http\Controllers\GiftExchangeController;

// Public invitation routes (no auth required)
Route::get('/gift-exchange/invitations/{token}', [GiftExchangeController::class, 'showInvitation'])->name('gift-exchange.showInvitation');
Route::post('/gift-exchange/invitations/{token}/respond', [GiftExchangeController::class, 'respondToInvitationWeb'])->name('gift-exchange.respondToInvitationWeb');
Route::get('/gift-exchange/invitation-error', [GiftExchangeController::class, 'invitationError'])->name('gift-exchange.invitationError');

Route::middleware(['web', 'auth'])->group(function () {
    // Profile tabs (auth-protected)
    Route::get('/profile/wishlist', [UserController::class, 'profileWishlist'])->name('profile.wishlist');
    Route::get('/profile/events', [UserController::class, 'events'])->name('profile.events');
    Route::get('/profile/friends', [UserController::class, 'friends'])->name('profile.friends');
    Route::get('/profile/settings', [UserController::class, 'settings'])->name('profile.settings');
    // Optional stub for future settings persistence (do NOT add handlers yet in this subtask)
    // Route::post('/profile/settings', [UserController::class, 'updateSettings'])->name('profile.settings.update');

    // Wishlist Management Routes
    Route::post('/wishlists', [WishlistController::class, 'storeUserWishlist'])->name('wishlists.store');
    Route::put('/wishlists/{userWishlist}', [WishlistController::class, 'updateUserWishlist'])->name('wishlists.update');
    Route::delete('/wishlists/{userWishlist}', [WishlistController::class, 'destroyUserWishlist'])->name('wishlists.destroy');
    Route::post('/wishlists/{userWishlist}/items', [WishlistController::class, 'storeNewWishToSpecificWishlist'])->name('wishlists.items.store');
 
    Route::get('/gift-exchange', [GiftExchangeController::class, 'dashboard'])->name('gift-exchange.dashboard');
    Route::post('/gift-exchange/create', [GiftExchangeController::class, 'createEventWeb'])->name('gift-exchange.create');
    Route::post('/gift-exchange/{event}/invite', [GiftExchangeController::class, 'inviteParticipantsWeb'])->name('gift-exchange.invite');
    // View a specific event's dashboard (avoid collision with /invitations/* routes)
    Route::get('/gift-exchange/{event}/dashboard', [GiftExchangeController::class, 'show'])->name('gift-exchange.show');
    // Edit / Update event (owner-only — enforced in controller)
    Route::get('/gift-exchange/{event}/edit', [GiftExchangeController::class, 'edit'])->name('gift-exchange.edit');
    Route::put('/gift-exchange/{event}', [GiftExchangeController::class, 'update'])->name('gift-exchange.update');
});

 // Public profile viewing route (other users)
 Route::get('/profile/{user:username}', [UserController::class, "profile"])->middleware('mustBeLoggedIn')->name('profile.show');
