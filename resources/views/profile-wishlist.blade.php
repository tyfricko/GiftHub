@extends('components.layout')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Profile Header -->
    @php
        $isOwnProfile = (auth()->check() && auth()->user()->id === $user->id);
    @endphp
    <x-ui.profile-header
        :user="$user"
        :isOwnProfile="$isOwnProfile"
        :avatarSize="'lg'"
    />
    
    <!-- Page Title and Intro -->
    <div class="mt-4 sm:mt-6 mb-4 sm:mb-6">
       @if($isOwnProfile)
           <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-neutral-900 dark:text-white">{{ __('messages.welcome_message', ['name' => $user->fullname ?? $user->username]) }}</h1>
           <p class="text-sm sm:text-body text-neutral-600 dark:text-neutral-400 mt-2">{{ __('messages.profile_description') }}</p>
       @else
           <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-neutral-900 dark:text-white">{{ __('messages.user_wishlist_title', ['username' => $user->username]) }}</h1>
           <p class="text-sm sm:text-body text-neutral-600 dark:text-neutral-400 mt-2">{{ __('messages.viewing_public_wishlist', ['username' => $user->username]) }}</p>
       @endif
    </div>

    <!-- Profile Tabs -->
    @if($isOwnProfile)
    @php
        $profileTabs = [
            ['key' => 'wishlists', 'label' => __('messages.my_wishlist'), 'url' => route('profile.wishlist')],
            ['key' => 'events',    'label' => __('messages.my_events'),   'url' => route('profile.events'), 'badge' => $pendingInvitationsCount ?? 0],
        ];
    @endphp
    <x-ui.tabs :tabs="$profileTabs" :active="$activeTab ?? 'wishlists'" />
    @endif

    <!-- Toolbar -->
    @if($isOwnProfile)
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0 mb-6">
        <h2 class="text-xl sm:text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('messages.my_wishlist') }}</h2>
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:space-x-3 w-full sm:w-auto">
            <button class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-700 focus:outline-none min-h-[44px]" data-modal-target="create-wishlist-modal">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span class="hidden sm:inline">{{ __('messages.create_new_wishlist') }}</span>
            </button>
            <button class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-white dark:bg-neutral-800 border border-neutral-200 dark:border-neutral-700 text-neutral-700 dark:text-neutral-300 hover:bg-neutral-50 dark:hover:bg-neutral-700 focus:outline-none min-h-[44px]">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                <span class="hidden sm:inline">{{ __('messages.get_gift_ideas') }}</span>
            </button>
            <a href="/add-wish" class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-primary-600 dark:bg-primary-600 text-white hover:bg-primary-700 dark:hover:bg-primary-700 focus:outline-none min-h-[44px]">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                {{ __('messages.add_item') }}
            </a>
        </div>
    </div>
    @else
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl sm:text-2xl font-semibold text-neutral-900 dark:text-white">{{ __('messages.public_wishlists', ['username' => $user->username]) }}</h2>
    </div>
    @endif

    <!-- Wishlist Items -->
    <div class="space-y-6">
        @if($userWishlists->count() > 0)
            @foreach ($userWishlists as $wishlist)
                <x-ui.wishlist-group-card :wishlist="$wishlist" :canEdit="$isOwnProfile" />
            @endforeach
        @else
            <x-ui.card class="text-center py-8 sm:py-12">
                <div class="text-neutral-600 dark:text-neutral-400">
                    <i class="fa fa-gift text-3xl sm:text-4xl mb-4 block" aria-hidden="true"></i>
                    @if($isOwnProfile)
                        <h3 class="text-lg sm:text-subheadline font-semibold mb-2 text-neutral-900 dark:text-white">{{ __('messages.no_wishlists_found') }}</h3>
                        <p class="text-sm sm:text-body mb-4">{{ __('messages.no_wishlists_desc') }}</p>
                        <a href="/add-wish" class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-primary-600 dark:bg-primary-600 text-white min-h-[44px]">{{ __('messages.create_wishlist') }}</a>
                    @else
                        <h3 class="text-lg sm:text-subheadline font-semibold mb-2 text-neutral-900 dark:text-white">{{ __('messages.no_public_wishlists') }}</h3>
                        <p class="text-sm sm:text-body mb-1">{{ __('messages.no_public_wishlists_desc', ['username' => $user->username]) }}</p>
                    @endif
                </div>
            </x-ui.card>
        @endif
    </div>
</div>

<!-- Modals -->
@if($isOwnProfile)
<x-modals.create-wishlist-modal />
<x-modals.edit-wishlist-modal />
@endif


<script>
    // Generic modal toggler
    function toggleModal(modalId, show = null) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        if (show === true) {
            modal.style.display = 'block';
        } else if (show === false) {
            modal.style.display = 'none';
        } else {
            modal.style.display = modal.style.display === 'none' ? 'block' : 'none';
        }
    }

    // Notifications are handled by the global NotificationManager.
    // Use showNotification(message, type) or the backward compatible showToast(message, type).

    document.addEventListener('DOMContentLoaded', function() {
        // Handle "Create New Wishlist" button click
        document.querySelectorAll('[data-modal-target]').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.dataset.modalTarget;
                toggleModal(targetId, true);
            });
        });

        // Handle "Edit Wishlist" button click
        document.querySelectorAll('.edit-wishlist-btn').forEach(button => {
            button.addEventListener('click', function() {
                const wishlistId = this.dataset.wishlistId;
                const wishlistName = this.dataset.wishlistName;
                const wishlistDescription = this.dataset.wishlistDescription;
                const wishlistVisibility = this.dataset.wishlistVisibility;

                const form = document.getElementById('edit-wishlist-form');
                form.action = `/wishlists/${wishlistId}`;
                document.getElementById('edit-wishlist-id').value = wishlistId;
                document.getElementById('edit-wishlist-name').value = wishlistName;
                document.getElementById('edit-wishlist-description').value = wishlistDescription;
                document.getElementById('edit-wishlist-visibility').value = wishlistVisibility;

                toggleModal('edit-wishlist-modal', true);
            });
        });

        // Handle "Delete Wishlist" button click
        document.querySelectorAll('.delete-wishlist-btn').forEach(button => {
            button.addEventListener('click', async function() {
                const wishlistId = this.dataset.wishlistId;
                const wishlistName = this.dataset.wishlistName;

                if (confirm(`{{ __('messages.confirm_delete_wishlist', ['name' => '${wishlistName}']) }}`)) {
                    try {
                        console.log(`Attempting to delete wishlist: ${wishlistId} - ${wishlistName}`);
                        
                        const response = await fetch(`/wishlists/${wishlistId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        });

                        console.log(`Response status: ${response.status}`);
                        
                        const data = await response.json();
                        console.log('Response data:', data);

                        if (response.ok && data.success) {
                            showToast(data.message || 'Wishlist deleted successfully!', 'success');
                            setTimeout(() => location.reload(), 1500); // Delay reload to show success message
                        } else {
                            const errorMsg = data.message || `Error deleting wishlist (Status: ${response.status})`;
                            showToast(errorMsg, 'error');
                            console.error('Delete failed:', data);
                        }
                    } catch (error) {
                        console.error('Network/Parse Error:', error);
                        showToast(`Network error occurred: ${error.message}`, 'error');
                    }
                }
            });
        });

        // Note: Wishlist item deletion is now handled by the global deleteWishlistItem function
        // called directly from the wishlist-item-card component

        // Global function for wishlist item deletion (called from wishlist-item-card component)
        window.deleteWishlistItem = async function(itemId, itemName) {
            if (confirm(`{{ __('messages.confirm_delete_item', ['name' => '${itemName}']) }}`)) {
                try {
                    console.log(`Attempting to delete wishlist item: ${itemId} - ${itemName}`);
                    
                    const response = await fetch(`/wishlist/${itemId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    });

                    console.log(`Response status: ${response.status}`);
                    
                    const data = await response.json();
                    console.log('Response data:', data);

                    if (response.ok && data.success) {
                        showToast(data.message || 'Item deleted successfully!', 'success');
                        // Remove the item from the DOM
                        const itemElement = document.querySelector(`[data-wish-id="${itemId}"]`);
                        if (itemElement) {
                            itemElement.style.transition = 'opacity 0.3s ease-out';
                            itemElement.style.opacity = '0';
                            setTimeout(() => itemElement.remove(), 300);
                        }
                    } else {
                        const errorMsg = data.message || `Error deleting item (Status: ${response.status})`;
                        showToast(errorMsg, 'error');
                        console.error('Delete failed:', data);
                    }
                } catch (error) {
                    console.error('Network/Parse Error:', error);
                    showToast(`Network error occurred: ${error.message}`, 'error');
                }
            }
        };

    });
</script>

@endsection