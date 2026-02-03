@extends('components.layout')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-6">
    <div class="max-w-2xl mx-auto">
        <x-ui.card>
            <h1 class="text-2xl font-bold mb-6 text-neutral-900 dark:text-neutral-100">
                {{ isset($wish) ? 'Uredi željo' : 'Dodaj novo željo' }}
            </h1>
            
            <form action="{{ isset($wish) ? '/wish/' . $wish->id : '/add-wish' }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @if(isset($wish))
                    @method('PUT')
                @endif

                @if(request()->has('wishlist_id'))
                    <input type="hidden" name="wishlist_id" value="{{ request('wishlist_id') }}">
                @endif

                <!-- Wishlist Selection Field -->
                <x-ui.form-group label="Dodaj v seznam želja" :required="true">
                    @php
                        $userWishlists = auth()->user()->userWishlists()->orderBy('is_default', 'desc')->orderBy('name')->get();
                        $selectedWishlistIds = old('user_wishlist_ids', request()->has('wishlist_id') ? [request('wishlist_id')] : []);
                        
                        // If editing a wish, get the current wishlists
                        if (isset($wish)) {
                            $selectedWishlistIds = $wish->userWishlists()->pluck('user_wishlists.id')->toArray();
                        }
                        
                        // If no selection and no default, select the default wishlist
                        if (empty($selectedWishlistIds) && $userWishlists->isNotEmpty()) {
                            $defaultWishlist = $userWishlists->where('is_default', true)->first();
                            if ($defaultWishlist) {
                                $selectedWishlistIds = [$defaultWishlist->id];
                            }
                        }
                    @endphp
                    
                    @forelse($userWishlists as $wishlist)
                        <label class="flex items-center p-3 border border-neutral-200 rounded-lg hover:bg-neutral-50 cursor-pointer transition-colors duration-150 dark:border-neutral-700 dark:hover:bg-neutral-700/50">
                            <x-ui.checkbox
                                name="user_wishlist_ids[]"
                                :value="$wishlist->id"
                                :checked="in_array($wishlist->id, $selectedWishlistIds)"
                            />
                            <div class="ml-3 flex-1">
                                <span class="text-sm font-medium text-neutral-700 dark:text-neutral-300">
                                    {{ $wishlist->name }}
                                    @if($wishlist->is_default)
                                        <x-ui.badge variant="success" size="sm">
                                            Privzeto
                                        </x-ui.badge>
                                    @endif
                                </span>
                            </div>
                        </label>
                    @empty
                        <p class="text-sm text-neutral-500 dark:text-neutral-400 italic">
                            Nimate še nobenega seznama želja. Sistem bo ustvaril privzeti seznam.
                        </p>
                    @endforelse

                    @error('user_wishlist_ids')
                        <x-ui.alert type="error" class="mt-2">{{ $message }}</x-ui.alert>
                    @enderror
                    @error('user_wishlist_ids.*')
                        <x-ui.alert type="error" class="mt-2">{{ $message }}</x-ui.alert>
                    @enderror
                </x-ui.form-group>

                <x-ui.form-group label="Spletni naslov izdelka (url)" for="post-url" help="Vnesite URL izdelka za samodejno pridobivanje podrobnosti">
                    <x-ui.input 
                        type="text" 
                        name="url" 
                        id="post-url" 
                        :value="old('url', $wish->url ?? '')"
                        placeholder="https://"
                        autocomplete="off"
                    />
                    @error('url')
                        <x-ui.alert type="error" class="mt-2">{{ $message }}</x-ui.alert>
                    @enderror
                </x-ui.form-group>

                <div id="url-scrape-status" class="h-4 mb-6">
                    <span id="url-loading" class="text-sm text-neutral-600 dark:text-neutral-400 hidden">
                        <i class="fa fa-spinner fa-spin mr-2"></i>
                        Pridobivam podatke o izdelku...
                    </span>
                    <span id="url-error" class="hidden">
                        <x-ui.alert type="error"></x-ui.alert>
                    </span>
                </div>

                <x-ui.form-group label="Naziv izdelka" for="post-title">
                    <x-ui.input 
                        type="text" 
                        name="itemname" 
                        id="post-title" 
                        :value="old('itemname', $wish->itemname ?? '')"
                        placeholder="Vnesite naziv izdelka"
                        autocomplete="off"
                    />
                    @error('itemname')
                        <x-ui.alert type="error" class="mt-2">{{ $message }}</x-ui.alert>
                    @enderror
                </x-ui.form-group>

                <x-ui.form-group label="Cena" for="price" help="Format: 0.00">
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-neutral-500 dark:text-neutral-400">€</span>
                        <x-ui.input 
                            type="number" 
                            name="price" 
                            id="price" 
                            :value="old('price', $wish->price ?? '')"
                            class="pl-8"
                            min="0"
                            step="0.01"
                            placeholder="0.00"
                        />
                    </div>
                    @error('price')
                        <x-ui.alert type="error" class="mt-2">{{ $message }}</x-ui.alert>
                    @enderror
                </x-ui.form-group>

                <x-ui.form-group label="Kratek opis" for="description" :help="'0/500 znakov'">
                    <x-ui.textarea 
                        name="description" 
                        id="description" 
                        :value="old('description', $wish->description ?? '')"
                        placeholder="Dodajte kratek opis izdelka..."
                        rows="4"
                    />
                    <div class="text-neutral-500 dark:text-neutral-400 text-xs mt-1 text-right">
                        <span id="desc-char-count">0</span>/500 znakov
                    </div>
                    @error('description')
                        <x-ui.alert type="error" class="mt-2">{{ $message }}</x-ui.alert>
                    @enderror
                </x-ui.form-group>

                <x-ui.form-group label="Slika izdelka" for="product-photo">
                    <input 
                        type="file" 
                        name="image_file" 
                        id="product-photo" 
                        class="block w-full text-sm text-neutral-500 dark:text-neutral-400
                            file:mr-4 file:py-2 file:px-4 
                            file:rounded-full file:border-0 
                            file:text-sm file:font-semibold 
                            file:bg-primary-50 file:text-primary-700 
                            dark:file:bg-primary-900/30 dark:file:text-primary-300
                            hover:file:bg-primary-100 dark:hover:file:bg-primary-900/50
                            cursor-pointer" 
                        accept="image/jpeg,image/png" 
                        onchange="validateAndPreviewImage(event)"
                    />
                    <div id="photo-error" class="hidden mt-2">
                        <x-ui.alert type="error"></x-ui.alert>
                    </div>
                    <div id="photo-preview" class="mt-4"></div>
                    <div id="scraped-image-url-display" class="hidden mt-2">
                        <x-ui.alert type="info">
                            Predlagana slika: <span id="scraped-url-display-text"></span>
                        </x-ui.alert>
                    </div>
                    @error('product_photo')
                        <x-ui.alert type="error" class="mt-2">{{ $message }}</x-ui.alert>
                    @enderror
                </x-ui.form-group>

                <div class="flex justify-end gap-3 pt-4">
                    <x-ui.button as="a" href="{{ route('profile.wishlist') }}" variant="secondary">
                        <i class="fa fa-times mr-2"></i> Prekliči
                    </x-ui.button>
                    <x-ui.button type="submit" class="min-h-[44px]">
                        <i class="fa fa-plus mr-2"></i> {{ isset($wish) ? 'Posodobi izdelek' : 'Dodaj izdelek' }}
                    </x-ui.button>
                </div>
            </form>
        </x-ui.card>
    </div>
</div>

<script>
// Description character counter
function updateCharCount() {
    var textarea = document.getElementById('description');
    var counter = document.getElementById('desc-char-count');
    counter.textContent = textarea.value.length;
}
// Initialize counter on page load (for old input)
document.addEventListener('DOMContentLoaded', function() {
    updateCharCount();
});

// Product photo validation and preview
function validateAndPreviewImage(event) {
    var fileInput = event.target;
    var file = fileInput.files[0];
    var errorDiv = document.getElementById('photo-error');
    var previewDiv = document.getElementById('photo-preview');
    var scrapedImageUrlDisplay = document.getElementById('scraped-image-url-display');
    errorDiv.style.display = 'none';
    errorDiv.textContent = '';
    previewDiv.innerHTML = '';
    if (scrapedImageUrlDisplay) { 
        scrapedImageUrlDisplay.style.display = 'none'; 
    }

    if (!file) return;

    if (!['image/jpeg', 'image/png'].includes(file.type)) {
        errorDiv.querySelector('.x-ui-alert').textContent = 'Dovoljeni sta samo JPEG ali PNG sliki.';
        errorDiv.style.display = 'block';
        fileInput.value = '';
        return;
    }
    if (file.size > 1024 * 1024) {
        errorDiv.querySelector('.x-ui-alert').textContent = 'Slika mora biti manjša od 1MB.';
        errorDiv.style.display = 'block';
        fileInput.value = '';
        return;
    }
    var reader = new FileReader();
    reader.onload = function(e) {
        var img = document.createElement('img');
        img.src = e.target.result;
        img.className = 'w-32 h-32 object-cover rounded-lg shadow-md';
        previewDiv.appendChild(img);
    };
    reader.readAsDataURL(file);
}

// URL scraping functionality
let urlInput = document.getElementById('post-url');
let urlLoading = document.getElementById('url-loading');
let urlError = document.getElementById('url-error');
let priceInput = document.getElementById('price');
let titleInput = document.getElementById('post-title');
let descriptionInput = document.getElementById('description');
let scrapedImageUrlDisplay = document.getElementById('scraped-image-url-display');

urlInput.addEventListener('blur', async function() {
    let url = urlInput.value.trim();
    if (!url) return;

    urlLoading.classList.remove('hidden');
    urlError.classList.add('hidden');

    try {
        let response = await fetch('/api/scrape-url', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ url: url })
        });

        if (!response.ok) {
            throw new Error('Neuspešno pridobivanje podatkov o izdelku.');
        }

        let data = await response.json();
        if (data.error) {
            throw new Error(data.error);
        }

        if (!titleInput.value.trim()) titleInput.value = data.title || '';
        if (!priceInput.value.trim() && data.price) {
            let price = parseFloat(data.price.replace(/[^0-9.-]+/g, ''));
            priceInput.value = price || '';
        }
        if (!descriptionInput.value.trim()) descriptionInput.value = data.description || '';
        updateCharCount(); // Update character count after setting description

        if (data.image) {
            document.getElementById('scraped-url-display-text').textContent = data.image;
            scrapedImageUrlDisplay.style.display = 'block';
        }

    } catch (err) {
        urlError.querySelector('.x-ui-alert').textContent = err.message || 'Napaka pri pridobivanju podatkov.';
        urlError.classList.remove('hidden');
    } finally {
        urlLoading.classList.add('hidden');
    }
});
</script>
@endsection
