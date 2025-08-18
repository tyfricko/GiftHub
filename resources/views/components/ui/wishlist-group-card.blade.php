@props([
    'wishlist',
    'canEdit' => false
])

<div class="space-y-4 mb-6">
    <div class="flex justify-between items-start">
        <div>
            <h2 class="text-2xl font-semibold text-neutral-900">{{ $wishlist->name }}</h2>
            @if ($wishlist->description)
                <p class="text-body text-neutral-600 mt-1">{{ $wishlist->description }}</p>
            @endif
        </div>

        <div class="flex items-center space-x-3">
            <span class="inline-flex items-center px-3 py-1 rounded-full bg-neutral-100 text-neutral-700 text-sm font-semibold">
                {{ ucfirst($wishlist->visibility->value) }}
            </span>

            @if($canEdit)
                <button class="px-3 py-2 rounded-md bg-neutral-white border border-neutral-200 text-neutral-700 hover:bg-neutral-50 edit-wishlist-btn"
                        data-wishlist-id="{{ $wishlist->id }}"
                        data-wishlist-name="{{ $wishlist->name }}"
                        data-wishlist-description="{{ $wishlist->description }}"
                        data-wishlist-visibility="{{ $wishlist->visibility->value }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M4 20l1-4 11-11 4 4-11 11-4 1z"></path>
                    </svg>
                </button>

                <button class="px-3 py-2 rounded-md bg-red-600 text-white hover:bg-red-700 delete-wishlist-btn"
                        data-wishlist-id="{{ $wishlist->id }}"
                        data-wishlist-name="{{ $wishlist->name }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            @endif
        </div>
    </div>

    <!-- Items: render as full-width horizontal cards (matches mockup) -->
    <div class="space-y-6">
        @forelse ($wishlist->items as $item)
            <x-ui.wishlist-item-card :item="$item" :canEdit="$canEdit" />
        @empty
            <x-ui.card class="text-center py-8">
                <p class="text-neutral-600">No items in this wishlist yet.</p>
                @if($canEdit)
                    <div class="mt-4">
                        <a href="/add-wish?wishlist_id={{ $wishlist->id }}" class="inline-flex items-center px-4 py-2 rounded-md bg-primary-600 text-white hover:bg-primary-700">
                            Add Item
                        </a>
                    </div>
                @endif
            </x-ui.card>
        @endforelse
    </div>

    <!-- Add item action -->
    @if ($canEdit)
        <div class="mt-4 text-right">
            <a href="/add-wish?wishlist_id={{ $wishlist->id }}" class="inline-flex items-center px-4 py-2 rounded-md bg-primary-600 text-white hover:bg-primary-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Item
            </a>
        </div>
    @endif
</div>