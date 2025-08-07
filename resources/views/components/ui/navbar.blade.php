@props([
    'user' => null,
])

<nav class="bg-primary text-white sticky top-0 z-50 shadow-sm">
    <div class="container mx-auto px-4 flex items-center justify-between h-16">
        <a href="/" class="text-xl font-bold hover:underline">GiftHub</a>

        @if ($user)
            <div class="hidden md:flex space-x-6">
                <a href="/wishlist" class="hover:underline">My Wishlist</a>
                <a href="/explore" class="hover:underline">Explore</a>
                <a href="/profile/{{ $user->username }}" class="hover:underline">Profile</a>
            </div>
        @endif

        <div class="flex items-center space-x-4">
            @if ($user)
                <a href="/add-wish" class="bg-secondary hover:bg-secondary-dark text-white px-3 py-1 rounded text-sm font-semibold">
                    Add Wish
                </a>
                <a href="/profile/{{ $user->username }}" class="flex items-center space-x-2">
                    <img src="{{ $user->avatar ? '/storage/' . $user->avatar : '/fallback-avatar.jpg' }}" alt="Profile" class="w-8 h-8 rounded-full" />
                    <span class="hidden sm:inline">{{ $user->username }}</span>
                </a>
                <form action="/logout" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-gray-600 hover:bg-gray-700 px-3 py-1 rounded text-sm font-semibold">
                        Logout
                    </button>
                </form>
            @else
                <a href="/login" class="hover:underline font-semibold">Sign In</a>
                <a href="/register" class="bg-secondary hover:bg-secondary-dark text-white px-3 py-1 rounded text-sm font-semibold">
                    Register
                </a>
            @endif
        </div>

        <!-- Mobile menu button -->
        <button type="button" class="md:hidden focus:outline-none focus:ring-2 focus:ring-accent" aria-label="Toggle menu" @click="open = !open">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </div>

    <!-- Mobile menu, show/hide based on menu state -->
    <div class="md:hidden" x-show="open" @click.away="open = false">
        @if ($user)
            <a href="/wishlist" class="block px-4 py-2 hover:bg-primary-dark">My Wishlist</a>
            <a href="/explore" class="block px-4 py-2 hover:bg-primary-dark">Explore</a>
            <a href="/profile/{{ $user->username }}" class="block px-4 py-2 hover:bg-primary-dark">Profile</a>
            <a href="/add-wish" class="block px-4 py-2 hover:bg-primary-dark">Add Wish</a>
            <form action="/logout" method="POST" class="block px-4 py-2">
                @csrf
                <button type="submit" class="w-full text-left">Logout</button>
            </form>
        @else
            <a href="/login" class="block px-4 py-2 hover:bg-primary-dark">Sign In</a>
            <a href="/register" class="block px-4 py-2 hover:bg-primary-dark">Register</a>
        @endif
    </div>
</nav>