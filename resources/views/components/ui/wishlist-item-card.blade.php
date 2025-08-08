@props([
    'item',
    'canEdit' => false,
    'compact' => false
])

<div class="bg-neutral-white rounded-lg shadow-card p-4 hover:shadow-lg transition-shadow duration-200 wishlist-item"
     data-wish-id="{{ $item->id }}"
     data-wish-title="{{ e($item->itemname) }}"
     data-wish-url="{{ e($item->url) }}"
     data-wish-price="{{ e($item->price) }}"
     data-wish-description="{{ e($item->description) }}">
    
    <div class="flex gap-4">
        <!-- Product Image -->
        <div class="flex-shrink-0">
            @php
                $imageUrl = $item->image_url;
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
                alt="{{ $item->itemname }}"
                class="w-16 h-16 md:w-20 md:h-20 object-cover rounded-lg"
                loading="lazy"
            />
        </div>
        
        <!-- Content Section -->
        <div class="flex-grow min-w-0">
            <a target="_blank"
               href="{{ $item->url }}"
               class="block hover:text-primary transition-colors duration-150">
                <h3 class="text-subheadline font-semibold text-neutral-gray mb-1 truncate">
                    {{ $item->itemname }}
                </h3>
                <p class="text-small text-neutral-gray opacity-75 mb-2 truncate">
                    {{ $item->url }}
                </p>
            </a>
            
            @if (!empty($item->price))
                <div class="mb-2">
                    <span class="inline-block bg-primary text-white px-2 py-1 rounded text-small font-semibold">
                        {{ $item->price }}&nbsp;€
                    </span>
                </div>
            @endif
            
            @if (!empty($item->description))
                <p class="text-body text-neutral-gray line-clamp-2">
                    {{ $item->description }}
                </p>
            @endif
        </div>
        
        <!-- Actions Section -->
        @if($canEdit)
            <div class="flex flex-col gap-2 flex-shrink-0">
                <x-ui.button
                    variant="secondary"
                    size="sm"
                    onclick="window.location.href='/wish/{{ $item->id }}/edit'"
                    ariaLabel="Uredi {{ $item->itemname }}"
                >
                    <i class="fa fa-pencil" aria-hidden="true"></i>
                </x-ui.button>
                
                <x-ui.button
                    variant="secondary"
                    size="sm"
                    class="btn-delete-wish text-error hover:bg-error hover:text-white"
                    ariaLabel="Izbriši {{ $item->itemname }}"
                >
                    <i class="fa fa-trash" aria-hidden="true"></i>
                </x-ui.button>
            </div>
        @endif
    </div>
</div>