@props([
    'user',
    'isOwnProfile' => false,
    'avatarSize' => 'xl'
])

<div class="bg-neutral-white rounded-lg shadow-card p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center gap-4">
        <!-- Avatar Section -->
        <div class="flex-shrink-0">
            <x-ui.avatar 
                :src="$user->avatar ? '/storage/' . $user->avatar : '/fallback-avatar.jpg'" 
                :alt="$user->username . ' Avatar'"
                size="{{ $avatarSize }}"
                class="w-24 h-24 md:w-32 md:h-32"
/>
        </div>
        
        <!-- User Info Section -->
        <div class="flex-grow">
            <h1 class="text-headline font-semibold text-neutral-gray mb-1">
                {{ $user->username }}
            </h1>
            <!-- Future: Location display -->
            <p class="text-body text-neutral-gray opacity-75 mb-3">
                <!-- Placeholder for future location -->
            </p>
            
            @if($isOwnProfile)
                <x-ui.button 
                    variant="secondary" 
                    size="sm"
                    onclick="window.location.href='/manage-avatar'"
                >
                    Uredi podatke Profila
                </x-ui.button>
            @endif
        </div>
    </div>
</div>