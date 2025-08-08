<div id="edit-wishlist-modal" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0,0,0,0.5); z-index: 9999; display: none;">
    <div style="position: relative; top: 50px; margin: 0 auto; padding: 20px; border: 1px solid #ccc; width: 400px; max-width: 90%; background-color: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
        <div class="text-center">
            <h3 class="text-2xl font-semibold text-gray-900 mb-4">Edit Wishlist</h3>
            <form id="edit-wishlist-form" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="wishlist_id" id="edit-wishlist-id">
                <div class="mb-4">
                    <label for="edit-wishlist-name" class="block text-gray-700 text-sm font-bold mb-2 text-left">Wishlist Name</label>
                    <input type="text" name="name" id="edit-wishlist-name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    @error('name')
                        <p class="text-red-500 text-xs italic mt-2 text-left">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="edit-wishlist-description" class="block text-gray-700 text-sm font-bold mb-2 text-left">Description (Optional)</label>
                    <textarea name="description" id="edit-wishlist-description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline h-24"></textarea>
                    @error('description')
                        <p class="text-red-500 text-xs italic mt-2 text-left">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="edit-wishlist-visibility" class="block text-gray-700 text-sm font-bold mb-2 text-left">Visibility</label>
                    <select name="visibility" id="edit-wishlist-visibility" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <option value="public">Public</option>
                        <option value="private">Private</option>
                    </select>
                    @error('visibility')
                        <p class="text-red-500 text-xs italic mt-2 text-left">{{ $message }}</p>
                    @enderror
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                    <button type="button" style="padding: 8px 16px; background-color: #d1d5db; color: #374151; border: none; border-radius: 6px; cursor: pointer;" onclick="toggleModal('edit-wishlist-modal')">Cancel</button>
                    <button type="submit" style="padding: 8px 16px; background-color: #2563eb; color: white; border: none; border-radius: 6px; cursor: pointer;">Update Wishlist</button>
                </div>
            </form>
        </div>
    </div>
</div>