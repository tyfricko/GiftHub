<div id="create-wishlist-modal" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.5); z-index: 9999; display: none;">
    <div style="position: relative; top: 50px; margin: 0 auto; padding: 20px; border: 1px solid #ccc; width: 400px; max-width: 90%; background-color: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div class="text-center">
            <h3 class="text-2xl font-semibold text-gray-900 mb-4">Create New Wishlist</h3>
            <form action="{{ route('wishlists.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="create-wishlist-name" class="block text-gray-700 text-sm font-bold mb-2 text-left">Wishlist Name</label>
                    <input type="text" name="name" id="create-wishlist-name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    @error('name')
                        <p class="text-red-500 text-xs italic mt-2 text-left">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="create-wishlist-description" class="block text-gray-700 text-sm font-bold mb-2 text-left">Description (Optional)</label>
                    <textarea name="description" id="create-wishlist-description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline h-24"></textarea>
                    @error('description')
                        <p class="text-red-500 text-xs italic mt-2 text-left">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="create-wishlist-visibility" class="block text-gray-700 text-sm font-bold mb-2 text-left">Visibility</label>
                    <select name="visibility" id="create-wishlist-visibility" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="public">Public</option>
                        <option value="private">Private</option>
                    </select>
                    @error('visibility')
                        <p class="text-red-500 text-xs italic mt-2 text-left">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-center justify-end space-x-4 mt-6">
                    <button type="button" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-md hover:bg-gray-400" onclick="toggleModal('create-wishlist-modal')">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Create Wishlist</button>
                </div>
            </form>
        </div>
    </div>
</div>