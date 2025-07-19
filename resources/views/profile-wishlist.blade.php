<x-layout>
    
    <div class="container py-md-5 container--wide">
        <h2>
          <img class="avatar-small" src="/fallback-avatar.jpg" /> {{$username}}
          <form class="ml-2 d-inline" action="/add-wish" method="GET">
            @if (auth()->user()->username == $username)
                <button class="btn btn-primary btn-sm">Dodaj proizvod <i class="fa fa-gift" aria-hidden="true"></i></button>
                <a href="/manage-avatar" class="btn btn-secondary btn-sm">Uredi podatke Profila</a>
            @endif
            <!-- <button class="btn btn-danger btn-sm">Stop Following <i class="fas fa-user-times"></i></button> -->
          </form>
        </h2>
  
        <div class="profile-nav nav nav-tabs pt-2 mb-4">
          <a href="#" class="profile-nav-link nav-item nav-link active"><strong>Moje želje:</strong></a>
        </div>
  
        <div class="list-group">
          @foreach ($wishes as $wish)
            <div class="list-group-item list-group-item-action wishlist-item" data-wish-id="{{$wish->id}}" data-wish-title="{{ e($wish->itemname) }}" data-wish-url="{{ e($wish->url) }}" data-wish-price="{{ e($wish->price) }}" data-wish-description="{{ e($wish->description) }}">
              <div class="d-flex align-items-start">
                @php
                    $imageUrl = $wish->image_url;
                    if (!empty($imageUrl)) {
                        if (Str::startsWith($imageUrl, 'http')) {
                            $src = $imageUrl;
                        } elseif (Str::startsWith($imageUrl, '/i/')) {
                            $src = 'https://www.mimovrste.com' . $imageUrl;
                        } else {
                            $src = asset('storage/' . $imageUrl);
                        }
                    } else {
                        $src = asset('fallback-avatar.jpg');
                    }
                @endphp
                <img
                  src="{{ $src }}"
                  alt="Product Image"
                  style="width: 64px; height: 64px; object-fit: cover; margin-right: 16px; border-radius: 6px;"
                  loading="lazy"
                />
                <div class="flex-grow-1">
                  <a target="_blank" href="{{$wish->url}}" style="text-decoration: none; color: inherit;">
                    <strong>{{$wish->itemname}}</strong><br>
                    <span class="m-0 small text-muted">{{$wish->url}}</span>
                  </a>
                  @if (!empty($wish->price))
                    <div class="mt-1"><span class="badge bg-success" style="font-size: 1em;">{{$wish->price}}&nbsp;€</span></div>
                  @endif
                  @if (!empty($wish->description))
                    <div class="mt-2 text-secondary" style="font-size: 0.95em;">{{$wish->description}}</div>
                  @endif
                </div>
                @if (auth()->user()->username == $username)
                  <div class="ms-3 d-flex flex-column align-items-end">
                    <a class="btn btn-sm btn-outline-primary mb-1" title="Uredi" href="/wish/{{$wish->id}}/edit"><i class="fa fa-pencil"></i></a>
                    <button class="btn btn-sm btn-outline-danger btn-delete-wish" title="Izbriši"><i class="fa fa-trash"></i></button>
                  </div>
                @endif
              </div>
            </div>
          @endforeach

        </div>
      </div>


      <!-- Toast Notification -->
      <div id="toast" style="display:none; position:fixed; bottom:32px; right:32px; background:#333; color:#fff; padding:14px 24px; border-radius:6px; z-index:2000; font-size:1.1em; opacity:0.95;">
        <span id="toastMsg"></span>
      </div>

      <script>

        // Delete button click
        document.querySelectorAll('.btn-delete-wish').forEach(btn => {
          btn.addEventListener('click', async function(e) {
            e.preventDefault();
            const wishElem = e.target.closest('.wishlist-item');
            const wishId = wishElem.dataset.wishId;
            if (window.confirm('Ali ste prepričani, da želite izbrisati to željo?')) {
              try {
                const resp = await fetch(`/api/wishlist/${wishId}`, {
                  method: 'DELETE',
                  headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                  },
                  credentials: 'include' // This ensures cookies (including session cookie) are sent
                });
                if (!resp.ok) throw new Error('Napaka pri brisanju.');
                wishElem.remove();
                showToast('Želja uspešno izbrisana.');
              } catch (err) {
                showToast('Napaka: ' + err.message);
              }
            }
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

</x-layout>