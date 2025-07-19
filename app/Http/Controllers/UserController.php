<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller {

    public function showProfileForm() {
        return view('profile-form');
    }

    public function profile(User $user) {

        return view('profile-wishlist', ['username' => $user->username, 'wishes' => $user->wishes()->latest()->get()]);
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
        auth()->login($user);
        return redirect('/')->with('success', 'Hvala, ker ste ustvarili račun.');
    }

    public function showCorrectHomepage() {

        if(auth()->check()) {

            $user = auth()->user();
            $wishlistItems = Wishlist::where('user_id', $user->id)->get();

            if($wishlistItems->count() > 0) {

                return view('profile-wishlist', ['username' => $user->username, 'wishes' => $user->wishes()->latest()->get()]);

            } else {

                return view('homepage-feed');

            }
        
        } else {
            
            return view('homepage');
        }
    }
}
