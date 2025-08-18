@props([
    'item',
    'canEdit' => false,
])

@php
    use Illuminate\Support\Str;

    $imageUrl = $item->image_url ?? null;
    if ($imageUrl) {
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

    $priority = strtolower($item->priority ?? '');
    $priorityClasses = match($priority) {
        'high' => 'bg-red-100 text-red-700',
        'medium' => 'bg-yellow-100 text-yellow-700',
        'low' => 'bg-green-100 text-green-700',
        default => 'bg-neutral-100 text-neutral-700',
    };
@endphp

<div class="bg-white rounded-lg shadow-level-2 p-6 mb-6 wishlist-item-row" data-wish-id="{{ $item->id }}">
  <div class="flex items-center gap-6">
    <!-- Image -->
    <div class="flex-shrink-0">
      <img src="{{ $src }}" alt="{{ $item->itemname }}" class="w-20 h-20 object-cover rounded-md" loading="lazy" />
    </div>

    <!-- Main content -->
    <div class="flex-1 min-w-0">
      <h3 class="text-subheadline font-semibold text-neutral-900 mb-1 truncate">{{ $item->itemname }}</h3>
      @if(!empty($item->description))
        <p class="text-body text-neutral-600 mb-2 line-clamp-2">{{ $item->description }}</p>
      @endif

      <div class="flex items-center gap-4">
        @if(!empty($item->price))
          <span class="text-primary-600 font-semibold text-body-sm">${{ number_format((float)$item->price, 2) }}</span>
        @endif

        @if(!empty($item->url))
          <a href="{{ $item->url }}" target="_blank" rel="noopener" class="text-sm text-accent-600 hover:underline flex items-center gap-1">
            View Product
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 3h7v7M10 14L21 3"></path>
            </svg>
          </a>
        @endif
      </div>
    </div>

    <!-- Actions -->
    <div class="flex flex-col items-end gap-3">
      <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $priorityClasses }}">{{ ucfirst($priority ?: 'None') }}</span>

      @if($canEdit)
        <div class="flex flex-col items-stretch gap-2">
          <button onclick="window.location.href='/wish/{{ $item->id }}/edit'" class="inline-flex items-center justify-center px-3 py-2 rounded-md bg-neutral-white border border-neutral-200 text-neutral-700 hover:bg-neutral-50 focus:outline-none">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M4 20l1-4 11-11 4 4-11 11-4 1z"></path>
            </svg>
            Edit
          </button>

          <button onclick="deleteWishlistItem({{ $item->id }}, '{{ addslashes($item->itemname) }}')" class="inline-flex items-center justify-center px-3 py-2 rounded-md bg-red-600 text-white hover:bg-red-700 focus:outline-none">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            Delete
          </button>
        </div>
      @endif
    </div>
  </div>
</div>