<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wishlist;
use App\Models\UserWishlist;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller {

    public function showLoginForm() {
        return view('login');
    }

    public function showRegisterForm() {
        return view('register');
    }

    public function showProfileForm() {
        return view('profile-form');
    }

    public function updateProfile(Request $request) {
        $user = auth()->user();
        
        $incomingFields = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'username' => ['required', 'string', 'min:3', 'max:20', Rule::unique('users', 'username')->ignore($user->id)],
            'address' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048']
        ]);

        // Handle avatar upload if provided
        if ($request->hasFile('avatar')) {
            // Store the avatar file
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $incomingFields['avatar'] = $avatarPath;
        }

        // Update user profile
        $user->update($incomingFields);

        return redirect()->route('profile.show', $user)->with('success', 'Profile updated successfully!');
    }

    public function profile(User $user) {
        // Eager load user's wishlists and their associated wishlist items
        $userWishlists = $user->userWishlists()->with('items')->get();

        return view('profile-wishlist', [
            'username' => $user->username,
            'userWishlists' => $userWishlists, // Pass the collection of UserWishlist models
            'wishes' => $user->wishlistItems()->latest()->get() // Keep for backward compatibility with views expecting 'wishes'
        ]);
    }
    
    public function logout() {
        auth()->logout();
        return redirect('/')->with('success', 'Uspešno ste se odjavili.');
    }
    
    public function login(Request $request) {
        $incomingFields = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required'
        ]);

        if(auth()->attempt(['username' => $incomingFields['loginusername'], 'password' => $incomingFields['loginpassword']])) {
            $request->session()->regenerate();
            return redirect('/')->with('success', 'Uspešno ste se prijavili');
        } else {
            return redirect('/')->with('failure', 'Neveljaven vpis');
        };

    }

    public function register(Request $request) {
        
        $incomingFields = $request->validate([
            'username'  => ['required', 'min:3', 'max:20', Rule::unique('users', 'username')],  
            'email'     => ['required', 'email', Rule::unique('users', 'email')],
            'password'  => ['required', 'min:6', 'confirmed']
            
        ]);
        
        $user = User::create($incomingFields);
        
        // Create a default wishlist for the new user
        $user->getOrCreateDefaultWishlist();
        
        auth()->login($user);
        return redirect('/')->with('success', 'Hvala, ker ste ustvarili račun.');
    }

    public function showCorrectHomepage() {

        if(auth()->check()) {

            $user = auth()->user();
            // Check if the user has any wishlist items across all their wishlists
            $hasWishlistItems = $user->wishlistItems()->exists();

            if($hasWishlistItems) {
                // Eager load user's wishlists and their associated wishlist items
                $userWishlists = $user->userWishlists()->with('items')->get();

                return view('profile-wishlist', [
                    'username' => $user->username,
                    'userWishlists' => $userWishlists,
                    'wishes' => $user->wishlistItems()->latest()->get() // Keep for backward compatibility with views expecting 'wishes'
                ]);

            } else {

                // For now, provide empty collections until activity system is implemented
                $activities = collect([]);
                $upcomingExchanges = collect([]);
                
                return view('homepage-feed', compact('activities', 'upcomingExchanges'));

            }
        
        } else {
            
            return view('homepage');
        }
    }
}
