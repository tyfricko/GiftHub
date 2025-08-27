<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wishlist;
use App\Models\UserWishlist;
use App\Models\GiftExchangeEvent;
use App\Models\GiftExchangeParticipant;
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
        return $this->showWishlistForUser($user);
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
            'name'      => ['required', 'string', 'max:255'],
            'username'  => ['required', 'min:3', 'max:20', Rule::unique('users', 'username')],
            'email'     => ['required', 'email', Rule::unique('users', 'email')],
            'password'  => ['required', 'min:6', 'confirmed']
            
        ]);
        
        $user = User::create($incomingFields);
        
        // Create a default wishlist for the new user
        $user->getOrCreateDefaultWishlist();
        
        // Send email verification notification
        $user->sendEmailVerificationNotification();
        
        // Log the user in and show a clear verification reminder
        auth()->login($user);
        return redirect('/')->with('warning', 'Vaš račun je bil ustvarjen. Preverite svoj e-poštni naslov in potrdite ga, da boste lahko ustvarjali sezname želja in dogodke.');
    }

    public function showCorrectHomepage() {

        if(auth()->check()) {

            $user = auth()->user();
            
            // If user hasn't verified email, show the simplified homepage with only verification message
            if (!$user->hasVerifiedEmail()) {
                return view('homepage-unverified');
            }
            
            // For verified users, always redirect to their profile wishlist page
            // This ensures they see the welcome message and can start adding items
            return $this->showWishlistForUser($user);
        
        } else {
            
            return view('homepage-redesigned');
        }
    }

    /**
     * Display the authenticated user's wishlists (profile tab).
     *
     * Variables passed to view:
     * - user: Authenticated User model
     * - username: string (user's username)
     * - userWishlists: Collection of UserWishlist models with 'items' relation loaded
     * - wishes: Collection of wishlist items (kept for backward compatibility)
     * - activeTab: 'wishlists'
     *
     * @return \Illuminate\View\View
     */
    public function profileWishlist()
    {
        return $this->showWishlistForUser(auth()->user());
    }

    private function showWishlistForUser(User $user)
    {
        $viewer = auth()->user();
        $isOwnProfile = $viewer && $viewer->id === $user->id;

        if ($isOwnProfile) {
            // Owner sees all wishlists and all items
            $user->load([
                'userWishlists.items' => function ($q) {
                    $q->latest();
                },
                'wishlistItems'
            ]);

            $userWishlists = $user->userWishlists;
            $wishes = $user->wishlistItems->sortByDesc('created_at');
            
            // Get pending invitations count for badge (only for own profile)
            $pendingInvitationsCount = $user->getPendingInvitationsCount();
        } else {
            // Only show PUBLIC wishlists (and their items) to other users
            $userWishlists = $user->userWishlists()
                ->public()
                ->with(['items' => function ($q) {
                    $q->latest();
                }])
                ->get();

            // Flatten only items from PUBLIC wishlists (avoid leaking private items)
            $wishes = $userWishlists
                ->flatMap(function ($wishlist) {
                    return $wishlist->items;
                })
                ->sortByDesc('created_at')
                ->values();
                
            $pendingInvitationsCount = 0; // Don't show badges on other users' profiles
        }

        return view('profile-wishlist', [
            'user' => $user->fresh(),
            'userWishlists' => $userWishlists,
            'wishes' => $wishes,
            'pendingInvitationsCount' => $pendingInvitationsCount,
            'activeTab' => 'wishlists'
        ]);
    }

    /**
     * Display the authenticated user's gift exchange events (created and participating).
     *
     * Variables passed to view:
     * - user: Authenticated User model
     * - createdEvents: Collection of GiftExchangeEvent created by the user
     * - participatingEvents: Collection of GiftExchangeEvent the user is participating in
     * - counts: array with counts for 'created' and 'participating'
     * - links: array of related routes/links used by the view
     * - activeTab: 'events'
     *
     * @return \Illuminate\View\View
     */
    public function events()
    {
        $user = auth()->user();

        $createdEvents = GiftExchangeEvent::where('created_by', $user->id)
            ->orderByDesc('end_date')
            ->get();

        $participating = GiftExchangeParticipant::where('user_id', $user->id)
            ->with('event')
            ->get();

        $participatingEvents = $participating->pluck('event')->filter()->sortByDesc('end_date')->values();

        $counts = [
            'created' => $createdEvents->count(),
            'participating' => $participatingEvents->count()
        ];

        $links = [
            'dashboard' => route('gift-exchange.dashboard'),
            'createEvent' => route('gift-exchange.create.form'),
        ];

        // Pending invitations for this user's email (may include invitations created before user registered)
        $pendingInvitations = $user->getPendingInvitations();
        $pendingInvitationsCount = $user->getPendingInvitationsCount();

        return view('profile-events', [
            'user' => $user,
            'createdEvents' => $createdEvents,
            'participatingEvents' => $participatingEvents,
            'counts' => $counts,
            'links' => $links,
            'pendingInvitations' => $pendingInvitations,
            'pendingInvitationsCount' => $pendingInvitationsCount,
            'activeTab' => 'events'
        ]);
    }

    /**
     * Display the authenticated user's friends/social tab.
     *
     * Currently returns placeholder empty collections and counts until social features are implemented.
     *
     * Variables passed to view:
     * - user: Authenticated User model
     * - following: Collection
     * - followers: Collection
     * - requests: Collection
     * - counts: array (following, followers, requests)
     * - activeTab: 'friends'
     *
     * @return \Illuminate\View\View
     */
    public function friends()
    {
        $user = auth()->user();

        // Placeholder empty collections for now
        $following = collect([]);
        $followers = collect([]);
        $requests = collect([]);

        $counts = [
            'following' => $following->count(),
            'followers' => $followers->count(),
            'requests' => $requests->count()
        ];

        return view('profile-friends', [
            'user' => $user,
            'following' => $following,
            'followers' => $followers,
            'requests' => $requests,
            'counts' => $counts,
            'activeTab' => 'friends'
        ]);
    }

    /**
     * Display the authenticated user's settings tab.
     *
     * Variables passed to view:
     * - user: Authenticated User model
     * - settings: array with privacy, notifications, and account defaults
     * - activeTab: 'settings'
     *
     * @return \Illuminate\View\View
     */
    public function settings()
    {
        $user = auth()->user();

        $settings = [
            'privacy' => [
                'default_wishlist_visibility' => 'public'
            ],
            'notifications' => [
                'email_invitations' => true,
                'email_assignments' => true
            ],
            'account' => [
                'display_name' => $user->name,
                'avatar' => $user->avatar ?? null
            ]
        ];

        return view('profile-settings', [
            'user' => $user,
            'settings' => $settings,
            'activeTab' => 'settings'
        ]);
    }
}
