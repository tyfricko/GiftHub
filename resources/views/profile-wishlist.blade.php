@extends('components.layout')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Profile Header -->
    <x-ui.profile-header 
        :user="auth()->user() ?? (object)['username' => $username]"
        :isOwnProfile="auth()->user()->username ?? '' === $username"
    />
    
    <!-- Navigation Tabs -->
    @php
        $tabs = [
            [
                'key' => 'wishlists',
                'label' => 'Moje želje',
                'url' => '#',
            ],
            // Future tabs (commented for now)
            // ['key' => 'events', 'label' => 'Moji dogodki', 'url' => '#'],
            // ['key' => 'friends', 'label' => 'Prijatelji', 'url' => '#'],
            // ['key' => 'settings', 'label' => 'Nastavitve', 'url' => '#'],
        ];
    @endphp
    
    <x-ui.tabs :tabs="$tabs" active="wishlists" />
    
    <!-- Wishlist Content -->
    <div class="space-y-4">
        <!-- Add Wish Button -->
        @if (auth()->user()->username == $username)
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-headline font-semibold text-neutral-gray">
                    Moja lista želja
                </h2>
                <x-ui.button onclick="window.location.href='/add-wish'">
                    <i class="fa fa-gift mr-2" aria-hidden="true"></i>
                    Dodaj proizvod
                </x-ui.button>
            </div>
        @endif
        
        <!-- Wishlist Items -->
        @if($wishes->count() > 0)
            <div class="grid gap-4">
                @foreach ($wishes as $wish)
                    <x-ui.wishlist-item-card 
                        :wish="$wish"
                        :canEdit="auth()->user()->username ?? '' === $username"
                    />
                @endforeach
            </div>
        @else
            <x-ui.card class="text-center py-12">
                <div class="text-neutral-gray opacity-75">
                    <i class="fa fa-gift text-4xl mb-4 block" aria-hidden="true"></i>
                    <h3 class="text-subheadline font-semibold mb-2">
                        Ni še nobene želje
                    </h3>
                    <p class="text-body mb-4">
                        @if(auth()->user()->username ?? '' === $username)
                            Dodajte svojo prvo željo na seznam!
                        @else
                            {{ $username }} še ni dodal nobene želje.
                        @endif
                    </p>
                    @if(auth()->user()->username ?? '' === $username)
                        <x-ui.button onclick="window.location.href='/add-wish'">
                            Dodaj prvo željo
                        </x-ui.button>
                    @endif
                </div>
            </x-ui.card>
        @endif
    </div>
</div>

<!-- Toast Notification -->
<div id="toast" 
     style="display:none; position:fixed; bottom:32px; right:32px; background:#333; color:#fff; padding:14px 24px; border-radius:6px; z-index:2000; font-size:1.1em; opacity:0.95;">
    <span id="toastMsg"></span>
</div>

<script>
// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, looking for delete buttons...');
    
    // Delete button click handler
    const deleteButtons = document.querySelectorAll('.btn-delete-wish');
    console.log('Found delete buttons:', deleteButtons.length);
    
    deleteButtons.forEach((btn, index) => {
        console.log(`Setting up listener for button ${index}:`, btn);
        btn.addEventListener('click', async function(e) {
            e.preventDefault();
            console.log('Delete button clicked:', e.target);
            
            const wishElem = e.target.closest('.wishlist-item');
            if (!wishElem) {
                console.error('Could not find wishlist-item parent');
                return;
            }
            
            const wishId = wishElem.dataset.wishId;
            const wishTitle = wishElem.dataset.wishTitle;
            
            console.log('Wish ID:', wishId, 'Title:', wishTitle);
            
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

// Toast logic
function showToast(msg) {
    const toast = document.getElementById('toast');
    document.getElementById('toastMsg').textContent = msg;
    toast.style.display = 'block';
    setTimeout(() => { toast.style.display = 'none'; }, 2500);
}
</script>

@endsection