@extends('components.layout')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Profile Header -->
    <x-ui.profile-header 
        :user="auth()->user() ?? (object)['username' => $username]"
        :isOwnProfile="auth()->user()->username ?? '' === $username"
    />
    
    <!-- Profile Tabs -->
    @php
        $profileTabs = [
            ['key' => 'wishlists', 'label' => 'My Wishlist', 'url' => route('profile.wishlist')],
            ['key' => 'events',    'label' => 'My Events',   'url' => route('profile.events')],
            ['key' => 'friends',   'label' => 'My Friends',  'url' => route('profile.friends')],
            ['key' => 'settings',  'label' => 'Settings',    'url' => route('profile.settings')],
        ];
    @endphp
    <x-ui.tabs :tabs="$profileTabs" :active="$activeTab ?? 'wishlists'" />
    
    <!-- Wishlist Content -->
    <div class="space-y-6">
        @if (auth()->user()->username == $username)
            <x-ui.wishlist-management-toolbar />
        @endif

        @if($userWishlists->count() > 0)
            @foreach ($userWishlists as $wishlist)
                <x-ui.wishlist-group-card :wishlist="$wishlist" :canEdit="auth()->user()->username ?? '' === $username" />
            @endforeach
        @else
            <x-ui.card class="text-center py-12">
                <div class="text-neutral-gray opacity-75">
                    <i class="fa fa-gift text-4xl mb-4 block" aria-hidden="true"></i>
                    <h3 class="text-subheadline font-semibold mb-2">
                        No wishlists found
                    </h3>
                    <p class="text-body mb-4">
                        @if(auth()->user()->username ?? '' === $username)
                            Start by creating your first wishlist!
                        @else
                            {{ $username }} has not created any wishlists yet.
                        @endif
                    </p>
                    @if(auth()->user()->username ?? '' === $username)
                        <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <i class="fas fa-plus mr-2"></i> Create New Wishlist
                        </button>
                    @endif
                </div>
            </x-ui.card>
        @endif
    </div>
</div>

<!-- Modals -->
<x-modals.create-wishlist-modal />
<x-modals.edit-wishlist-modal />

<!-- Toast Notification -->
<div id="toast"
     style="display:none; position:fixed; bottom:32px; right:32px; background:#333; color:#fff; padding:14px 24px; border-radius:6px; z-index:2000; font-size:1.1em; opacity:0.95;">
    <span id="toastMsg"></span>
</div>

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

    // Toast logic
    function showToast(msg) {
        const toast = document.getElementById('toast');
        document.getElementById('toastMsg').textContent = msg;
        toast.style.display = 'block';
        setTimeout(() => { toast.style.display = 'none'; }, 2500);
    }

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
                        const response = await fetch(`/wishlists/${wishlistId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (response.ok) {
                            showToast(data.message || 'Wishlist deleted successfully!');
                            location.reload(); // Reload page to reflect changes
                        } else {
                            showToast(data.message || 'Error deleting wishlist.');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showToast('An unexpected error occurred.');
                    }
                }
            });
        });

        // Existing delete button for wishlist items (if still needed)
        const deleteItemButtons = document.querySelectorAll('.btn-delete-wish');
        deleteItemButtons.forEach((btn, index) => {
            btn.addEventListener('click', async function(e) {
                e.preventDefault();
                const wishElem = e.target.closest('.wishlist-item');
                if (!wishElem) return;
                
                const wishId = wishElem.dataset.wishId;
                const wishTitle = wishElem.dataset.wishTitle;
                
                if (window.confirm(`Ali ste prepričani, da želite izbrisati "${wishTitle}"?`)) {
                    try {
                        const resp = await fetch(`/api/wishlist/${wishId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            credentials: 'include'
                        });
                        
                        if (!resp.ok) throw new Error('Napaka pri brisanju.');
                        
                        wishElem.remove();
                        showToast('Želja uspešno izbrisana.');
                    } catch (err) {
                        console.error('Delete error:', err);
                        showToast('Napaka: ' + err.message);
                    }
                }
            });
        });
    });
</script>

@endsection