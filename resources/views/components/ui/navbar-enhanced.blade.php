@props([
    'user' => null,
])

<nav class="bg-white shadow-level-1 sticky top-0 z-50" x-data="{ mobileMenuOpen: false }" aria-label="Primary">
    <div class="container mx-auto px-6">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="/" class="flex items-center space-x-2 text-primary-600 hover:text-primary-700 transition-colors duration-fast" aria-label="GiftHub home">
                    <svg class="w-8 h-8" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false" role="img">
                        <path d="M12 2L2 7v10c0 5.55 3.84 9.95 9 11 5.16-1.05 9-5.45 9-11V7l-10-5z"/>
                        <path d="M9 12l2 2 4-4" stroke="white" stroke-width="2" fill="none"/>
                    </svg>
                    <span class="text-xl font-bold">Gift Hub</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            @if ($user)
                <div class="hidden md:flex items-center space-x-8" role="navigation" aria-label="Primary navigation">
                    <a href="{{ route('wishlist.index') }}" class="text-neutral-700 hover:text-primary-600 font-medium transition-colors duration-fast">My Wishlist</a>
                    <a href="{{ route('profile.events') }}" class="text-neutral-700 hover:text-primary-600 font-medium transition-colors duration-fast">My Events</a>
                    <a href="{{ route('profile.manage') }}" class="text-neutral-700 hover:text-primary-600 font-medium transition-colors duration-fast">Manage Profile</a>
                </div>
            @endif

            <!-- Desktop Auth Buttons -->
            <div class="hidden md:flex items-center space-x-4">
                @if ($user)
                    <x-ui.button variant="primary" size="sm" as="a" href="/add-wish">Add Wish</x-ui.button>

                    <div class="flex items-center space-x-3">
                        <a href="/profile/{{ $user->username }}" class="flex items-center space-x-2 text-neutral-700 hover:text-primary-600 transition-colors duration-fast" aria-label="Open profile">
                            <img src="{{ $user->avatar ? '/storage/' . $user->avatar : '/fallback-avatar.jpg' }}"
                                 alt="{{ $user->username }}"
                                 class="w-8 h-8 rounded-full border-2 border-neutral-200" />
                            <span class="font-medium">{{ $user->username }}</span>
                        </a>

                        <form action="/logout" method="POST" class="inline" style="margin: 0px 12px">
                            @csrf
                            <x-ui.button variant="secondary" size="sm" type="submit">Logout</x-ui.button>
                        </form>
                    </div>
                @else
                    <a href="/login" class="text-neutral-700 hover:text-primary-600 font-semibold transition-colors duration-fast">Sign In</a>
                    <x-ui.button variant="primary" size="sm" as="a" href="/register">Register</x-ui.button>
                @endif
            </div>

            <!-- Mobile menu button -->
            <button 
                type="button" 
                class="md:hidden p-2 rounded-md text-neutral-700 hover:text-primary-600 hover:bg-neutral-50 focus:ring-2 focus:ring-accent-600" 
                aria-label="Toggle menu"
                @click="mobileMenuOpen = !mobileMenuOpen"
                :aria-expanded="mobileMenuOpen.toString()"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>

        <!-- Mobile menu -->
        <div class="md:hidden" x-show="mobileMenuOpen" @click.away="mobileMenuOpen = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2" aria-hidden="false">
            <div class="px-2 pt-2 pb-3 space-y-1 bg-white border-t border-neutral-200" role="menu" aria-label="Mobile primary">
                @if ($user)
                    <a href="{{ route('wishlist.index') }}" class="block px-3 py-2 text-neutral-700 hover:text-primary-600 hover:bg-neutral-50 rounded-md font-medium transition-colors duration-fast" role="menuitem">My Wishlist</a>
                    <a href="{{ route('profile.events') }}" class="block px-3 py-2 text-neutral-700 hover:text-primary-600 hover:bg-neutral-50 rounded-md font-medium transition-colors duration-fast" role="menuitem">My Events</a>
                    <a href="{{ route('profile.manage') }}" class="block px-3 py-2 text-neutral-700 hover:text-primary-600 hover:bg-neutral-50 rounded-md font-medium transition-colors duration-fast" role="menuitem">Manage Profile</a>
                    <a href="/add-wish" class="block px-3 py-2 text-neutral-700 hover:text-primary-600 hover:bg-neutral-50 rounded-md font-medium transition-colors duration-fast" role="menuitem">Add Wish</a>
                    <form action="/logout" method="POST" class="block px-3 py-2" role="none">
                        @csrf
                        <button type="submit" class="w-full text-left text-neutral-700 hover:text-primary-600 font-medium transition-colors duration-fast" role="menuitem">Logout</button>
                    </form>
                @else
                    <a href="/login" class="block px-3 py-2 text-neutral-700 hover:text-primary-600 hover:bg-neutral-50 rounded-md font-medium transition-colors duration-fast" role="menuitem">Sign In</a>
                    <a href="/register" class="block px-3 py-2 text-neutral-700 hover:text-primary-600 hover:bg-neutral-50 rounded-md font-medium transition-colors duration-fast" role="menuitem">Register</a>
                @endif
            </div>
        </div>
    </div>
</nav>