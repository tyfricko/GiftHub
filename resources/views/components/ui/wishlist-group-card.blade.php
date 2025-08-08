@props([
    'wishlist',
    'canEdit' => false
])

<div class="bg-white shadow-md rounded-lg p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold text-gray-800">{{ $wishlist->name }}</h2>
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-500">
                {{ ucfirst($wishlist->visibility->value) }}
            </span>
            <button class="text-blue-500 hover:text-blue-700 edit-wishlist-btn"
                    data-wishlist-id="{{ $wishlist->id }}"
                    data-wishlist-name="{{ $wishlist->name }}"
                    data-wishlist-description="{{ $wishlist->description }}"
                    data-wishlist-visibility="{{ $wishlist->visibility->value }}">
                <i class="fas fa-edit"></i>
            </button>
            <button class="text-red-500 hover:text-red-700 delete-wishlist-btn"
                    data-wishlist-id="{{ $wishlist->id }}"
                    data-wishlist-name="{{ $wishlist->name }}">
                <i class="fas fa-trash-alt"></i>
            </button>
        </div>
    </div>

    @if ($wishlist->description)
        <p class="text-gray-600 mb-4">{{ $wishlist->description }}</p>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse ($wishlist->items as $item)
            <x-ui.wishlist-item-card :item="$item" :canEdit="$canEdit" />
        @empty
            <div class="col-span-full text-center text-gray-500 py-8">
                <p class="mb-4">No items in this wishlist yet.</p>
                <a href="/add-wish?wishlist_id={{ $wishlist->id }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <i class="fas fa-plus mr-2"></i> Add Item
                </a>
            </div>
        @endforelse
    </div>

    @if ($wishlist->items->isNotEmpty())
        <div class="mt-6 text-center">
            <a href="/add-wish?wishlist_id={{ $wishlist->id }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-plus mr-2"></i> Add Item
            </a>
        </div>
    @endif
</div>