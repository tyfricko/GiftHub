<x-layout>

  <div class="container py-md-5 container--narrow">
    <form action="{{ isset($wish) ? '/wish/' . $wish->id : '/add-wish' }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($wish))
            @method('POST') {{-- Laravel uses POST for form submissions, but we can spoof PUT/PATCH --}}
        @endif
      <div class="form-group">
        <label for="product-title" class="text-muted mb-1"><small>Naziv izdelka</small></label>
        <input value="{{old('itemname', $wish->itemname ?? '')}}" name="itemname" id="post-title" class="form-control form-control-lg form-control-title" type="text" placeholder="" autocomplete="off" />
        @error('itemname')
          <p class="m-0 small alert alert-danger shadow-sm">{{$message}}</p>
        @enderror
      </div>

      <div class="form-group">
        <label for="product-url" class="text-muted mb-1"><small>Spletni naslov izdelka (url)</small></label>
        <input value="{{old('url', $wish->url ?? '')}}" name="url" id="post-url" class="form-control form-control-lg form-control-title" type="text" placeholder="https://" autocomplete="off" />
        @error('url')
        <p class="m-0 small alert alert-danger shadow-sm">{{$message}}</p>
      @enderror
      </div>
      <div id="url-scrape-status" style="margin-bottom:10px;">
        <span id="url-loading" style="display:none;">🔄 Fetching product info...</span>
        <span id="url-error" class="small alert alert-danger shadow-sm" style="display:none;"></span>
      </div>

      <div class="form-group">
        <label for="price" class="text-muted mb-1"><small>Price</small></label>
        <div class="input-group">
          <input value="{{ old('price', $wish->price ?? '') }}" name="price" id="price" class="form-control" type="number" step="0.01" min="0" placeholder="0.00" />
          <!-- Currency selection removed: default is now EUR -->
        </div>
        @error('price')
          <p class="m-0 small alert alert-danger shadow-sm">{{$message}}</p>
        @enderror
        <!-- Currency error removed -->
      </div>

      <div class="form-group">
        <label for="description" class="text-muted mb-1"><small>Short Description</small></label>
        <textarea name="description" id="description" class="form-control" maxlength="500" rows="3" oninput="updateCharCount()" style="resize: vertical;">{{ old('description', $wish->description ?? '') }}</textarea>
        <div class="text-muted small">
          <span id="desc-char-count">0</span>/500 characters
        </div>
        @error('description')
          <p class="m-0 small alert alert-danger shadow-sm">{{$message}}</p>
        @enderror
      </div>

      <div class="form-group">
        <label for="product-photo" class="text-muted mb-1"><small>Product Photo</small></label>
        <input type="file" name="image_file" id="product-photo" class="form-control-file" accept="image/jpeg,image/png" onchange="validateAndPreviewImage(event)">
        <div id="photo-error" class="m-0 small alert alert-danger shadow-sm" style="display:none;"></div>
        <div id="photo-preview" style="margin-top:10px;"></div>
        <div id="scraped-image-url-display" class="text-muted small mt-1" style="display:none;"></div>
        @error('product_photo')
          <p class="m-0 small alert alert-danger shadow-sm">{{$message}}</p>
        @enderror
      </div>

      <button class="btn btn-primary">{{ isset($wish) ? 'Update Izdelek' : 'Dodaj izelek' }}</button>
    </form>
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
        if (scrapedImageUrlDisplay) { // Check if element exists before trying to hide
            scrapedImageUrlDisplay.style.display = 'none'; // Hide scraped URL when new file is selected
        }

        if (!file) return;

        // Validate type
        if (!['image/jpeg', 'image/png'].includes(file.type)) {
          errorDiv.textContent = 'Only JPEG or PNG images are allowed.';
          errorDiv.style.display = 'block';
          fileInput.value = '';
          return;
        }
        // Validate size (<1MB)
        if (file.size > 1024 * 1024) {
          errorDiv.textContent = 'Image must be less than 1MB.';
          errorDiv.style.display = 'block';
          fileInput.value = '';
          return;
        }
        // Show thumbnail preview
        var reader = new FileReader();
        reader.onload = function(e) {
          var img = document.createElement('img');
          img.src = e.target.result;
          img.style.maxWidth = '150px';
          img.style.maxHeight = '150px';
          img.alt = 'Product Photo Preview';
          img.className = 'img-thumbnail mt-2';
          previewDiv.appendChild(img);
        };
        reader.readAsDataURL(file);
      }
    </script>
    <script>
      // --- Metadata Scraper Integration ---
      (function() {
        const urlInput = document.getElementById('post-url');
        const titleInput = document.getElementById('post-title');
        const descInput = document.getElementById('description');
        const photoPreviewDiv = document.getElementById('photo-preview');
        const photoInput = document.getElementById('product-photo');
        const loadingSpan = document.getElementById('url-loading');
        const errorSpan = document.getElementById('url-error');
        let scrapeTimeout = null;
        let lastScrapedUrl = '';

        function showLoading(show) {
          loadingSpan.style.display = show ? 'inline' : 'none';
        }
        function showError(msg) {
          if (msg) {
            errorSpan.textContent = msg;
            errorSpan.style.display = 'inline-block';
          } else {
            errorSpan.textContent = '';
            errorSpan.style.display = 'none';
          }
        }
        function setImagePreview(imageUrl) {
          console.log('[setImagePreview] Called with:', imageUrl);
          if (!imageUrl) {
            console.log('[setImagePreview] No imageUrl provided, aborting.');
            return;
          }
          // Clear file input and preview
          if (photoInput) photoInput.value = '';
          photoPreviewDiv.innerHTML = '';
          const img = document.createElement('img');
          img.src = imageUrl;
          img.style.maxWidth = '150px';
          img.style.maxHeight = '150px';
          img.alt = 'Product Photo Preview';
          img.className = 'img-thumbnail mt-2';
          photoPreviewDiv.appendChild(img);

          // Display the scraped image URL
          const scrapedImageUrlDisplay = document.getElementById('scraped-image-url-display');
          scrapedImageUrlDisplay.textContent = 'Scraped Image URL: ' + imageUrl;
          scrapedImageUrlDisplay.style.display = 'block';

          // Add a hidden input to retain the image_url if no new file is uploaded
          const hiddenInput = document.createElement('input');
          hiddenInput.type = 'hidden';
          hiddenInput.name = 'image_url';
          hiddenInput.value = imageUrl;
          photoPreviewDiv.appendChild(hiddenInput);

          // Log DOM state for debugging
          console.log('[setImagePreview] photoPreviewDiv.innerHTML:', photoPreviewDiv.innerHTML);
          console.log('[setImagePreview] scrapedImageUrlDisplay.textContent:', scrapedImageUrlDisplay.textContent);
        }

        function scrapeUrl(url) {
          if (!url || url === lastScrapedUrl) return;
          showError('');
          showLoading(true);
          fetch('/api/scrape-url', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({ url: url })
          })
          .then(async resp => {
            showLoading(false);
            if (!resp.ok) {
              let msg = 'Failed to fetch product info.';
              try {
                const data = await resp.json();
                if (data && data.message) msg = data.message;
              } catch {}
              throw new Error(msg);
            }
            return resp.json();
          })
          .then(data => {
            lastScrapedUrl = url;
            if (data.title && titleInput) titleInput.value = data.title;
            if (data.description && descInput) descInput.value = data.description;

            // Debug: log scraped data
            console.log('[scrapeUrl] Scraped data:', data);

            // Handle image URL for preview (use image_url property)
            if (data.image_url) {
                let imageUrlForPreview = data.image_url;
                if (imageUrlForPreview.startsWith('/i/')) {
                    imageUrlForPreview = 'https://www.mimovrste.com' + imageUrlForPreview;
                }
                // Only set preview if it's a valid absolute URL now
                if (imageUrlForPreview.startsWith('http')) {
                    console.log('[scrapeUrl] Setting image preview with:', imageUrlForPreview);
                    setImagePreview(imageUrlForPreview);
                } else {
                    console.log('[scrapeUrl] Image URL not valid for preview:', imageUrlForPreview);
                }
            } else {
                console.log('[scrapeUrl] No image_url in scraped data.');
            }

            // Populate price and currency if available
            if (data.price && priceInput) priceInput.value = data.price;
            // Currency is always EUR now; no need to set currencySelect
          })
          .catch(err => {
            showError(err.message || 'Could not fetch product info.');
          })
          .finally(() => {
            showLoading(false);
          });
        }

        // Debounced handler for blur and input
        function handleUrlInput() {
          if (scrapeTimeout) clearTimeout(scrapeTimeout);
          scrapeTimeout = setTimeout(() => {
            const url = urlInput.value.trim();
            if (url && /^https?:\/\//i.test(url)) {
              scrapeUrl(url);
            }
          }, 400);
        }

        if (urlInput) {
          urlInput.addEventListener('blur', handleUrlInput);
          urlInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') handleUrlInput();
          });
        }
        // Initial population of fields if a wish object is present (for edit mode)
        @if(isset($wish))
          titleInput.value = "{{ $wish->itemname ?? '' }}";
          urlInput.value = "{{ $wish->url ?? '' }}";
          descInput.value = "{{ $wish->description ?? '' }}";
          priceInput.value = "{{ $wish->price ?? '' }}";
          // currencySelect removed; currency is always EUR
          @if($wish->image_url)
            setImagePreview("{{ asset('storage/' . $wish->image_url) }}");
          @endif
        @endif
      })();
    </script>
</x-layout>