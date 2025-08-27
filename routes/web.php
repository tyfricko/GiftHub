<?php

use Illuminate\Http\Request;
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
Route::get('/test-homepage', function() {
    return view('homepage-redesigned');
})->name('test-homepage');
Route::get('/login', [UserController::class, "showLoginForm"])->middleware('guest')->name('login');
Route::get('/register', [UserController::class, "showRegisterForm"])->middleware('guest')->name('register');
Route::post('/register', [UserController::class, "register"])->middleware('guest');
Route::post('/login', [UserController::class, "login"]);
Route::post('/logout', [UserController::class, "logout"])->middleware('mustBeLoggedIn')->name('logout');

// Email Verification Routes
use Illuminate\Foundation\Auth\EmailVerificationRequest;
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    
    // Check if there's an intended URL stored in session
    $intendedUrl = session()->pull('url.intended');
    
    if ($intendedUrl) {
        return redirect($intendedUrl)->with('success', 'Vaš e-poštni naslov je bil uspešno potrjen.');
    }
    
    // Default: redirect to profile wishlist page after verification
    return redirect()->route('profile.wishlist')->with('success', 'Vaš e-poštni naslov je bil uspešno potrjen. Dobrodošli v GiftHub!');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Povezava za potrditev je bila poslana!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
Route::get('/manage-avatar', [UserController::class, "showProfileForm"])->middleware(['mustBeLoggedIn', 'verified'])->name('profile.update');
Route::put('/manage-avatar', [UserController::class, "updateProfile"])->middleware(['mustBeLoggedIn', 'verified'])->name('profile.update');

// Wishlist Routes
Route::get('/add-wish', [WishlistController::class, "showCreateForm"])->middleware(['mustBeLoggedIn', 'requireVerified']);
Route::post('/add-wish', [WishlistController::class, "storeNewWish"])->middleware(['mustBeLoggedIn', 'requireVerified']);
Route::get('/wishlist', [WishlistController::class, 'index'])->middleware(['mustBeLoggedIn', 'requireVerified'])->name('wishlist.index');

// Edit Wish Route
Route::get('/wish/{wish}/edit', [WishlistController::class, 'edit'])->middleware(['mustBeLoggedIn', 'requireVerified']);
Route::put('/wish/{wish}', [WishlistController::class, 'updateWish'])->middleware(['mustBeLoggedIn', 'requireVerified']);

// Delete Wish Route
Route::delete('/wishlist/{id}', [WishlistController::class, 'destroy'])->middleware(['mustBeLoggedIn', 'requireVerified'])->name('wishlist.destroy');

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
    // Note: /profile/friends and /profile/settings pages removed; routes intentionally omitted.
    // Optional stub for future settings persistence (do NOT add handlers yet in this subtask)
    // Route::post('/profile/settings', [UserController::class, 'updateSettings'])->name('profile.settings.update');

    // Wishlist Management Routes
    Route::post('/wishlists', [WishlistController::class, 'storeUserWishlist'])->middleware('requireVerified')->name('wishlists.store');
    Route::put('/wishlists/{userWishlist}', [WishlistController::class, 'updateUserWishlist'])->middleware('requireVerified')->name('wishlists.update');
    Route::delete('/wishlists/{userWishlist}', [WishlistController::class, 'destroyUserWishlist'])->middleware('requireVerified')->name('wishlists.destroy');
    Route::post('/wishlists/{userWishlist}/items', [WishlistController::class, 'storeNewWishToSpecificWishlist'])->middleware('requireVerified')->name('wishlists.items.store');
 
    // Redirect the legacy gift-exchange dashboard to the consolidated profile events page
    Route::permanentRedirect('/gift-exchange', '/profile/events')->name('gift-exchange.dashboard');
    Route::get('/gift-exchange/create', [GiftExchangeController::class, 'showCreateForm'])->middleware('requireVerified')->name('gift-exchange.create.form');
    Route::post('/gift-exchange/create', [GiftExchangeController::class, 'createEventWeb'])->middleware('requireVerified')->name('gift-exchange.create');
    Route::post('/gift-exchange/{event}/invite', [GiftExchangeController::class, 'inviteParticipantsWeb'])->middleware('requireVerified')->name('gift-exchange.invite');

    // In-app invitation response routes (authenticated users)
    Route::post('/invitations/{invitation}/accept', [GiftExchangeController::class, 'acceptInvitationFromDashboard'])
        ->name('invitations.accept');

    Route::post('/invitations/{invitation}/decline', [GiftExchangeController::class, 'declineInvitationFromDashboard'])
        ->name('invitations.decline');

    // Shipping address collection for participants (when event requires it)
    Route::get('/gift-exchange/{event}/shipping-address', [GiftExchangeController::class, 'showShippingAddressForm'])
        ->middleware('requireVerified')
        ->name('gift-exchange.shipping-address');

    Route::post('/gift-exchange/{event}/shipping-address', [GiftExchangeController::class, 'updateShippingAddress'])
        ->middleware('requireVerified')
        ->name('gift-exchange.shipping-address.update');
    // View a specific event's dashboard (avoid collision with /invitations/* routes)
    Route::get('/gift-exchange/{event}/dashboard', [GiftExchangeController::class, 'show'])->name('gift-exchange.show');
    // Edit / Update event (owner-only — enforced in controller)
    Route::get('/gift-exchange/{event}/edit', [GiftExchangeController::class, 'edit'])->middleware('requireVerified')->name('gift-exchange.edit');
    Route::put('/gift-exchange/{event}', [GiftExchangeController::class, 'update'])->middleware('requireVerified')->name('gift-exchange.update');
    Route::delete('/gift-exchange/{event}', [GiftExchangeController::class, 'destroy'])->middleware('requireVerified')->name('gift-exchange.destroy');
 // Manual assign gifts route (event owner) - quick trigger for assignments
 Route::post('/gift-exchange/{event}/assign-gifts', [GiftExchangeController::class, 'assignGifts'])
     ->middleware('requireVerified')
     ->name('gift-exchange.assign-gifts');
});
 // Public profile viewing route (other users)
 Route::get('/profile/{user:username}', [UserController::class, "profile"])->middleware(['mustBeLoggedIn'])->name('profile.show');
