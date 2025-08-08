@extends('components.layout')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow-md rounded-lg p-4 md:p-8">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">{{ isset($wish) ? 'Uredi željo' : 'Dodaj novo željo' }}</h1>
        <form action="{{ isset($wish) ? '/wish/' . $wish->id : '/add-wish' }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($wish))
                @method('PUT')
            @endif

            @if(request()->has('wishlist_id'))
                <input type="hidden" name="wishlist_id" value="{{ request('wishlist_id') }}">
            @endif

            <!-- Wishlist Selection Field -->
            <div class="mb-4">
                <label for="user_wishlist_ids" class="block text-gray-700 text-sm font-bold mb-2">
                    Dodaj v seznam želja <span class="text-red-500">*</span>
                </label>
                <div class="space-y-2">
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
                        <label class="flex items-center space-x-3 p-2 border rounded hover:bg-gray-50 cursor-pointer">
                            <input
                                type="checkbox"
                                name="user_wishlist_ids[]"
                                value="{{ $wishlist->id }}"
                                {{ in_array($wishlist->id, $selectedWishlistIds) ? 'checked' : '' }}
                                class="form-checkbox h-4 w-4 text-green-600 transition duration-150 ease-in-out"
                            >
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">
                                    {{ $wishlist->name }}
                                    @if($wishlist->is_default)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">
                                            Privzeto
                                        </span>
                                    @endif
                                </span>
                            </div>
                        </label>
                    @empty
                        <p class="text-sm text-gray-500 italic">Nimate še nobenega seznama želja. Sistem bo ustvaril privzeti seznam.</p>
                    @endforelse
                </div>
                @error('user_wishlist_ids')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
                @error('user_wishlist_ids.*')
                    <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                @enderror
            </div>
 
           <div class="mb-4">
             <label for="product-url" class="block text-gray-700 text-sm font-bold mb-2">Spletni naslov izdelka (url)</label>
             <input value="{{old('url', $wish->url ?? '')}}" name="url" id="post-url" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" placeholder="https://" autocomplete="off" />
             @error('url')
             <p class="text-red-500 text-xs italic mt-2">{{$message}}</p>
           @enderror
           </div>
          <div id="url-scrape-status" class="mb-4 h-4">
            <span id="url-loading" class="text-sm text-gray-600" style="display:none;">🔄 Pridobivam podatke o izdelku...</span>
            <span id="url-error" class="text-red-500 text-xs italic" style="display:none;"></span>
          </div>

          <div class="mb-4">
            <label for="product-title" class="block text-gray-700 text-sm font-bold mb-2">Naziv izdelka</label>
            <input value="{{old('itemname', $wish->itemname ?? '')}}" name="itemname" id="post-title" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" placeholder="" autocomplete="off" />
            @error('itemname')
              <p class="text-red-500 text-xs italic mt-2">{{$message}}</p>
            @enderror
          </div>

          <div class="mb-4">
            <label for="price" class="block text-gray-700 text-sm font-bold mb-2">Cena</label>
            <div class="relative">
              <input value="{{ old('price', $wish->price ?? '') }}" name="price" id="price" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="number" step="0.01" min="0" placeholder="0.00" />
            </div>
            @error('price')
              <p class="text-red-500 text-xs italic mt-2">{{$message}}</p>
            @enderror
          </div>

          <div class="mb-4">
            <label for="description" class="block text-gray-700 text-sm font-bold mb-2">Kratek opis</label>
            <textarea name="description" id="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline h-32 resize-y" maxlength="500" oninput="updateCharCount()">{{ old('description', $wish->description ?? '') }}</textarea>
            <div class="text-gray-600 text-xs mt-1 text-right">
              <span id="desc-char-count">0</span>/500 znakov
            </div>
            @error('description')
              <p class="text-red-500 text-xs italic mt-2">{{$message}}</p>
            @enderror
          </div>

          <div class="mb-4">
            <label for="product-photo" class="block text-gray-700 text-sm font-bold mb-2">Slika izdelka</label>
            <input type="file" name="image_file" id="product-photo" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100" accept="image/jpeg,image/png" onchange="validateAndPreviewImage(event)">
            <div id="photo-error" class="text-red-500 text-xs italic mt-2" style="display:none;"></div>
            <div id="photo-preview" class="mt-4"></div>
            <div id="scraped-image-url-display" class="text-gray-600 text-xs mt-1" style="display:none;"></div>
            @error('product_photo')
              <p class="text-red-500 text-xs italic mt-2">{{$message}}</p>
            @enderror
          </div>

          <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">{{ isset($wish) ? 'Posodobi izdelek' : 'Dodaj izdelek' }}</button>
        </form>
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
      errorDiv.textContent = 'Dovoljeni sta samo JPEG ali PNG sliki.';
      errorDiv.style.display = 'block';
      fileInput.value = '';
      return;
    }
    if (file.size > 1024 * 1024) {
      errorDiv.textContent = 'Slika mora biti manjša od 1MB.';
      errorDiv.style.display = 'block';
      fileInput.value = '';
      return;
    }
    var reader = new FileReader();
    reader.onload = function(e) {
      var img = document.createElement('img');
      img.src = e.target.result;
      img.className = 'w-32 h-32 object-cover rounded';
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

    urlLoading.style.display = 'inline';
    urlError.style.display = 'none';

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
        scrapedImageUrlDisplay.textContent = 'Predlagana slika: ' + data.image;
        scrapedImageUrlDisplay.style.display = 'block';
      }

    } catch (err) {
      urlError.textContent = err.message || 'Napaka pri pridobivanju podatkov.';
      urlError.style.display = 'block';
    } finally {
        urlLoading.style.display = 'none';
    }
  });
</script>
@endsection