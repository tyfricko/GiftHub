@extends('components.layout')

@section('content')
<div class="container mx-auto px-6">
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
    <div class="mt-6 mb-6">
       @if($isOwnProfile)
           <h1 class="text-3xl lg:text-4xl font-extrabold text-neutral-900">Welcome, {{ $user->fullname ?? $user->username }}!</h1>
           <p class="text-body text-neutral-600 mt-2">This is your personal space to manage your wishlists and gift exchanges.</p>
       @else
           <h1 class="text-3xl lg:text-4xl font-extrabold text-neutral-900">{{ $user->username }}'s Wishlist</h1>
           <p class="text-body text-neutral-600 mt-2">You are viewing the public wishlist of {{ $user->username }}.</p>
       @endif
    </div>

    <!-- Profile Tabs -->
    @if($isOwnProfile)
    @php
        $profileTabs = [
            ['key' => 'wishlists', 'label' => 'My Wishlist', 'url' => route('profile.wishlist')],
            ['key' => 'events',    'label' => 'My Events',   'url' => route('profile.events')],
        ];
    @endphp
    <x-ui.tabs :tabs="$profileTabs" :active="$activeTab ?? 'wishlists'" />
    @endif

    <!-- Toolbar -->
    @if($isOwnProfile)
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-neutral-900">My Wishlist</h2>
        <div class="flex items-center space-x-3">
            <button class="inline-flex items-center px-4 py-2 rounded-md bg-neutral-white border border-neutral-200 text-neutral-700 hover:bg-neutral-50 focus:outline-none" data-modal-target="create-wishlist-modal">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Create New Wishlist
            </button>
            <button class="inline-flex items-center px-4 py-2 rounded-md bg-neutral-white border border-neutral-200 text-neutral-700 hover:bg-neutral-50 focus:outline-none">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                Get Gift Ideas
            </button>
            <a href="/add-wish" class="inline-flex items-center px-4 py-2 rounded-md bg-primary-600 text-white hover:bg-primary-700 focus:outline-none">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Item
            </a>
        </div>
    </div>
    @else
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-neutral-900">{{ $user->username }}'s Public Wishlists</h2>
    </div>
    @endif

    <!-- Wishlist Items -->
    <div class="space-y-6">
        @if($userWishlists->count() > 0)
            @foreach ($userWishlists as $wishlist)
                <x-ui.wishlist-group-card :wishlist="$wishlist" :canEdit="$isOwnProfile" />
            @endforeach
        @else
            <x-ui.card class="text-center py-12">
                <div class="text-neutral-600">
                    <i class="fa fa-gift text-4xl mb-4 block" aria-hidden="true"></i>
                    @if($isOwnProfile)
                        <h3 class="text-subheadline font-semibold mb-2">No wishlists found</h3>
                        <p class="text-body mb-4">You don't have any wishlists yet. Create your first wishlist to get started.</p>
                        <a href="/add-wish" class="inline-flex items-center px-4 py-2 rounded-md bg-primary-600 text-white">Create Wishlist</a>
                    @else
                        <h3 class="text-subheadline font-semibold mb-2">No public wishlists</h3>
                        <p class="text-body mb-1">{{ $user->username }} has not published any wishlists yet.</p>
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

                if (confirm(`Are you sure you want to delete the wishlist "${wishlistName}"? This action cannot be undone.`)) {
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
            if (confirm(`Are you sure you want to delete "${itemName}"? This action cannot be undone.`)) {
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