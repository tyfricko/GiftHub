<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\UserWishlist;
use Illuminate\Http\Request;
use AshAllenDesign\ShortURL\Classes\Builder;
use App\Services\ShortUrlService;
use App\Jobs\DownloadWishlistImageJob;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UserWishlistRequest;
use App\Http\Requests\WishlistItemRequest;
use App\Enums\WishlistVisibility;
use Illuminate\Validation\Rule;

class WishlistController extends Controller
{

    public function showCreateForm() {
        return view('add-wish');
    }

    /**
     * Display the current user's wishlist items.
     */
    public function index()
    {
        $userWishlists = auth()->user()->userWishlists()->latest()->get();
        return view('profile-wishlist', [
            'userWishlists' => $userWishlists,
            'username' => auth()->user()->username
        ]);
    }

    public function storeNewWish(WishlistItemRequest $request) {
        $incomingFields = $request->validated();

        // Sanitize required fields
        $incomingFields['itemname'] = strip_tags($incomingFields['itemname']);
        $incomingFields['url'] = strip_tags($incomingFields['url']);
        $incomingFields['user_id'] = auth()->id();

        // Sanitize and cast optional fields
        if (isset($incomingFields['price'])) {
            $incomingFields['price'] = (float) $incomingFields['price'];
        }
        // Always set currency to EUR
        $incomingFields['currency'] = 'EUR';
        if (isset($incomingFields['description'])) {
            $incomingFields['description'] = strip_tags($incomingFields['description']);
        }

        // Scrape metadata
        $scraper = new \App\Services\MetadataScraperService();
        $metadata = $scraper->scrape($incomingFields['url']);

        // Handle file upload for product image (takes precedence over image_url)
        if ($request->hasFile('image_file') && $request->file('image_file')->isValid()) {
            $path = $request->file('image_file')->store('wishlist_images', 'public');
            $incomingFields['image_url'] = $path;
        } elseif ($request->hasFile('image') && $request->file('image')->isValid()) {
            $path = $request->file('image')->store('wishlist_images', 'public');
            $incomingFields['image_url'] = $path;
        } elseif (!empty($metadata['image_url'])) {
            // Only use scraped image_url if no file was uploaded
            $incomingFields['image_url'] = $metadata['image_url'];
        } else {
            // If no image is scraped or uploaded, ensure image_url is null
            $incomingFields['image_url'] = null;
        }

        // Shorten URL if applicable (existing logic)
        $shortUrlService = new ShortUrlService();
        // Ensure URL has a scheme before shortening
        if (!preg_match("~^(?:f|ht)tps?://~i", $incomingFields['url'])) {
            $incomingFields['url'] = "https://" . $incomingFields['url'];
        }
        $incomingFields['url'] = $shortUrlService->generate($incomingFields['url']);

        // Handle multiple wishlist assignment
        $selectedWishlistIds = [];
        
        if (!empty($incomingFields['user_wishlist_ids'])) {
            // Use the selected wishlists from the form
            $selectedWishlistIds = $incomingFields['user_wishlist_ids'];
            \Log::info('Add Wish Debug - Using selected wishlists: ' . json_encode($selectedWishlistIds));
        } elseif (!empty($incomingFields['wishlist_id'])) {
            // Backward compatibility: single wishlist from URL parameter
            $selectedWishlistIds = [$incomingFields['wishlist_id']];
            \Log::info('Add Wish Debug - Using URL wishlist_id: ' . $incomingFields['wishlist_id']);
        } else {
            // Default to user's default wishlist
            $defaultWishlist = auth()->user()->getOrCreateDefaultWishlist();
            $selectedWishlistIds = [$defaultWishlist->id];
            \Log::info('Add Wish Debug - Using default wishlist: ' . $defaultWishlist->id);
        }

        // Validate that all selected wishlists belong to the current user
        $userWishlists = auth()->user()->userWishlists()->whereIn('id', $selectedWishlistIds)->get();
        if ($userWishlists->count() !== count($selectedWishlistIds)) {
            \Log::warning('Add Wish Debug - Some selected wishlists do not belong to user');
            return redirect()->back()->withErrors(['user_wishlist_ids' => 'Some selected wishlists are invalid.'])->withInput();
        }

        \Log::info('Add Wish Debug - Validated wishlists: ' . $userWishlists->pluck('name', 'id')->toJson());

        // Remove wishlist-specific fields from the main item data
        unset($incomingFields['wishlist_id'], $incomingFields['user_wishlist_ids']);

        // Determine sort_order (use the highest from all selected wishlists)
        $maxSortOrder = 0;
        foreach ($userWishlists as $wishlist) {
            $wishlistMaxSort = $wishlist->items()->max('sort_order') ?? 0;
            $maxSortOrder = max($maxSortOrder, $wishlistMaxSort);
        }
        $incomingFields['sort_order'] = $maxSortOrder + 1;

        // Create the wishlist item
        $wishlist = Wishlist::create($incomingFields);
        
        // Attach the item to all selected wishlists using the pivot table
        $wishlist->userWishlists()->attach($selectedWishlistIds);
        
        \Log::info('Add Wish Debug - Created item ID: ' . $wishlist->id . ' and attached to wishlists: ' . json_encode($selectedWishlistIds));

        // If an image_url was scraped and no file was uploaded, dispatch the job to download it
        // We check if it's a URL (not a local path) and if it was set from metadata (not user upload)
        if (isset($incomingFields['image_url']) && filter_var($incomingFields['image_url'], FILTER_VALIDATE_URL)) {
            // Ensure this only happens if no file was uploaded by the user
            // The logic above already prioritizes user upload, so if image_url is still an external URL,
            // it means it came from scraping and no user file was present.
            DownloadWishlistImageJob::dispatch($wishlist->id, $incomingFields['image_url']);
        }

        return redirect("/")->with('success', 'Dodal si izdelek v tovj seznam želja!');
    }
    /**
     * Scrape metadata from a given URL and return as JSON.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function scrapeUrl(Request $request)
    {
        $validated = $request->validate([
            'url' => 'required|url'
        ]);

        $scraper = new \App\Services\MetadataScraperService();
        $metadata = $scraper->scrape($validated['url']);

        return response()->json($metadata);
    }

    /**
     * Update a wishlist item (partial update).
     */
    public function update(Request $request, $id)
    {
        $wishlist = \App\Models\Wishlist::find($id);
        if (!$wishlist) {
            return response()->json([
                'message' => 'Wishlist item not found.'
            ], 404);
        }

        if ($wishlist->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'You are not authorized to update this wishlist item.'
            ], 403);
        }

        $fields = $request->only(['title', 'description', 'price', 'currency', 'image_url', 'user_wishlist_id', 'sort_order']);

        if (empty($fields)) {
            return response()->json([
                'message' => 'No fields provided for update.'
            ], 400);
        }

        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'image_url' => 'nullable|url',
            'user_wishlist_id' => 'nullable|exists:user_wishlists,id',
            'sort_order' => 'nullable|integer|min:0',
            'user_wishlist_id' => 'nullable|exists:user_wishlists,id',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if (empty($validated)) {
            return response()->json([
                'message' => 'No fields provided for update.'
            ], 400);
        }

        // Map 'title' to 'itemname' in DB
        if (array_key_exists('title', $validated)) {
            $validated['itemname'] = strip_tags($validated['title']);
            unset($validated['title']);
        }

        if (array_key_exists('description', $validated)) {
            $validated['description'] = strip_tags($validated['description']);
        }
        if (array_key_exists('currency', $validated)) {
            $validated['currency'] = strip_tags($validated['currency']);
        }
        if (array_key_exists('image_url', $validated)) {
            $validated['image_url'] = strip_tags($validated['image_url']);
        }
        if (array_key_exists('price', $validated)) {
            $validated['price'] = (float) $validated['price'];
        }
        if (array_key_exists('user_wishlist_id', $validated)) {
            $validated['user_wishlist_id'] = (int) $validated['user_wishlist_id'];
        }
        if (array_key_exists('sort_order', $validated)) {
            $validated['sort_order'] = (int) $validated['sort_order'];
        }

        $wishlist->fill($validated);
        $wishlist->save();

        return response()->json([
            'message' => 'Wishlist item updated successfully.',
            'data' => $wishlist->fresh()
        ]);
    }

    /**
     * Update a wishlist item.
     */
    public function updateWish(Request $request, Wishlist $wish)
    {
        if ($wish->user_id != auth()->id()) {
            return redirect('/');
        }

        $incomingFields = $request->validate([
            'itemname' => 'required|string',
            'url'  => 'required|url',
            'price' => 'nullable|numeric',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'user_wishlist_ids' => 'nullable|array',
            'user_wishlist_ids.*' => 'exists:user_wishlists,id', // Validate each ID in the array
            'sort_order' => 'nullable|integer|min:0',
            'image_file' => 'nullable|file|image|max:5120', // max 5MB
            'image' => 'nullable|file|image|max:5120', // also allow "image" field
        ]);

        // Sanitize required fields
        $incomingFields['itemname'] = strip_tags($incomingFields['itemname']);
        $incomingFields['url'] = strip_tags($incomingFields['url']);

        // Sanitize and cast optional fields
        if (isset($incomingFields['price'])) {
            $incomingFields['price'] = (float) $incomingFields['price'];
        }
        // Always set currency to EUR
        $incomingFields['currency'] = 'EUR';
        if (isset($incomingFields['description'])) {
            $incomingFields['description'] = strip_tags($incomingFields['description']);
        }
        // If image_url is present in incomingFields, it means it came from the hidden input
        // and we should use it unless a new file is uploaded.
        if (isset($incomingFields['image_url'])) {
            $incomingFields['image_url'] = strip_tags($incomingFields['image_url']);
        }


        // Handle file upload for product image (takes precedence over image_url)
        if ($request->hasFile('image_file') && $request->file('image_file')->isValid()) {
            $path = $request->file('image_file')->store('wishlist_images', 'public');
            $incomingFields['image_url'] = $path;
        } elseif ($request->hasFile('image') && $request->file('image')->isValid()) {
            // Also support "image" field for upload
            $path = $request->file('image')->store('wishlist_images', 'public');
            $incomingFields['image_url'] = $path;
        }

        // Shorten URL only if it has changed
        $shortUrlService = new ShortUrlService();

        // Normalize and sanitize incoming URL
        $incomingUrl = $incomingFields['url'];
        if (!preg_match("~^(?:f|ht)tps?://~i", $incomingUrl)) {
            $incomingUrl = "https://" . $incomingUrl;
        }
        $incomingUrl = strip_tags($incomingUrl);

        // Normalize and sanitize original URL for comparison
        $originalUrl = $wish->url;
        if (!preg_match("~^(?:f|ht)tps?://~i", $originalUrl)) {
            $originalUrl = "https://" . $originalUrl;
        }
        $originalUrl = strip_tags($originalUrl);

        if ($incomingUrl !== $originalUrl) {
            // Only generate a new short URL if the URL has changed
            $incomingFields['url'] = $shortUrlService->generate($incomingUrl);
        } else {
            // Keep the original short URL
            $incomingFields['url'] = $wish->url;
        }

        // Handle multiple wishlist assignment for updates
        $selectedWishlistIds = [];
        
        if (!empty($incomingFields['user_wishlist_ids'])) {
            $selectedWishlistIds = $incomingFields['user_wishlist_ids'];
            
            // Validate that all selected wishlists belong to the current user
            $userWishlists = auth()->user()->userWishlists()->whereIn('id', $selectedWishlistIds)->get();
            if ($userWishlists->count() !== count($selectedWishlistIds)) {
                return redirect()->back()->withErrors(['user_wishlist_ids' => 'Some selected wishlists are invalid.'])->withInput();
            }
            
            // Update the pivot table relationships
            $wish->userWishlists()->sync($selectedWishlistIds);
            \Log::info('Update Wish Debug - Updated item ID: ' . $wish->id . ' wishlists to: ' . json_encode($selectedWishlistIds));
        }

        // Remove wishlist-specific fields from the main item data
        // The user_wishlist_ids are handled by the sync method, so remove them from the main update
        unset($incomingFields['user_wishlist_ids']);
        
        if (isset($incomingFields['sort_order'])) {
            $incomingFields['sort_order'] = (int) $incomingFields['sort_order'];
        }

        $wish->update($incomingFields);

        return redirect('/profile/' . auth()->user()->username)->with('success', 'Wishlist item updated successfully.');
    }

    /**
     * Delete a wishlist item.
     */
    public function destroy($id)
    {
        \Log::info("Attempting to delete wishlist item with ID: {$id}");
        try {
            $wishlist = \App\Models\Wishlist::find($id);
            if (!$wishlist) {
                \Log::warning("Wishlist item with ID: {$id} not found.");
                return response()->json([
                    'message' => 'Wishlist item not found.'
                ], 404);
            }

            if ($wishlist->user_id !== auth()->id()) {
                \Log::warning("Unauthorized attempt to delete wishlist item with ID: {$id} by user: " . auth()->id());
                return response()->json([
                    'message' => 'You are not authorized to delete this wishlist item.'
                ], 403);
            }

            $wishlist->delete();
            \Log::info("Wishlist item with ID: {$id} deleted successfully.");
            return response()->noContent();
        } catch (\Exception $e) {
            \Log::error("Error deleting wishlist item with ID: {$id}. Error: " . $e->getMessage());
            return response()->json([
                'message' => 'Napaka pri brisanju.' // Error during deletion.
            ], 500);
        }
    }
    /**
     * Show the form for editing the specified wish.
     */
    public function edit(Wishlist $wish)
    {
        if ($wish->user_id !== auth()->id()) {
            abort(403, 'You are not authorized to edit this wish.');
        }
        return view('add-wish', ['wish' => $wish]);
    }

    /**
     * Store a newly created user wishlist in storage.
     */
    public function storeUserWishlist(UserWishlistRequest $request)
    {
        $validated = $request->validated();

        auth()->user()->userWishlists()->create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'visibility' => WishlistVisibility::from($validated['visibility']),
            'is_default' => false, // New wishlists are not default by creation
        ]);

        return redirect()->back()->with('success', 'Wishlist created successfully!');
    }

    /**
     * Update the specified user wishlist in storage.
     */
    public function updateUserWishlist(UserWishlistRequest $request, UserWishlist $userWishlist)
    {
        // Policy will handle authorization
        $this->authorize('update', $userWishlist);

        $validated = $request->validated();

        $userWishlist->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'visibility' => WishlistVisibility::from($validated['visibility']),
        ]);

        return redirect()->back()->with('success', 'Wishlist updated successfully!');
    }

    /**
     * Remove the specified user wishlist from storage.
     */
    public function destroyUserWishlist(UserWishlist $userWishlist)
    {
        // Policy will handle authorization
        $this->authorize('delete', $userWishlist);

        // Prevent deletion if it's the user's only wishlist
        if (auth()->user()->userWishlists()->count() === 1) {
            return redirect()->back()->with('error', 'Cannot delete the last wishlist. Please create another wishlist first.');
        }

        // If the deleted wishlist was the default, set another one as default
        if ($userWishlist->is_default) {
            $newDefault = auth()->user()->userWishlists()->where('id', '!=', $userWishlist->id)->first();
            if ($newDefault) {
                $newDefault->update(['is_default' => true]);
            }
        }

        $userWishlist->delete(); // This will also delete associated wishlist items due to cascade on delete in migration

        return redirect()->back()->with('success', 'Wishlist deleted successfully!');
    }

    /**
     * Store a newly created wish item to a specific user wishlist.
     */
    public function storeNewWishToSpecificWishlist(WishlistItemRequest $request, UserWishlist $userWishlist)
    {
        // Policy will handle authorization
        $this->authorize('addWishlistItem', $userWishlist);

        $incomingFields = $request->validated();

        // Sanitize required fields
        $incomingFields['itemname'] = strip_tags($incomingFields['itemname']);
        $incomingFields['url'] = strip_tags($incomingFields['url']);
        $incomingFields['user_id'] = auth()->id();
        $incomingFields['user_wishlist_id'] = $userWishlist->id; // Assign to the specific wishlist

        // Sanitize and cast optional fields
        if (isset($incomingFields['price'])) {
            $incomingFields['price'] = (float) $incomingFields['price'];
        }
        // Always set currency to EUR
        $incomingFields['currency'] = 'EUR';
        if (isset($incomingFields['description'])) {
            $incomingFields['description'] = strip_tags($incomingFields['description']);
        }

        // Scrape metadata
        $scraper = new \App\Services\MetadataScraperService();
        $metadata = $scraper->scrape($incomingFields['url']);

        // Handle file upload for product image (takes precedence over image_url)
        if ($request->hasFile('image_file') && $request->file('image_file')->isValid()) {
            $path = $request->file('image_file')->store('wishlist_images', 'public');
            $incomingFields['image_url'] = $path;
        } elseif ($request->hasFile('image') && $request->file('image')->isValid()) {
            $path = $request->file('image')->store('wishlist_images', 'public');
            $incomingFields['image_url'] = $path;
        } elseif (!empty($metadata['image_url'])) {
            // Only use scraped image_url if no file was uploaded
            $incomingFields['image_url'] = $metadata['image_url'];
        } else {
            // If no image is scraped or uploaded, ensure image_url is null
            $incomingFields['image_url'] = null;
        }

        // Shorten URL if applicable (existing logic)
        $shortUrlService = new ShortUrlService();
        // Ensure URL has a scheme before shortening
        if (!preg_match("~^(?:f|ht)tps?://~i", $incomingFields['url'])) {
            $incomingFields['url'] = "https://" . $incomingFields['url'];
        }
        $incomingFields['url'] = $shortUrlService->generate($incomingFields['url']);

        // Determine sort_order
        $maxSortOrder = $userWishlist->items()->max('sort_order');
        $incomingFields['sort_order'] = ($maxSortOrder ?? 0) + 1;

        $wishlist = Wishlist::create($incomingFields);

        // If an image_url was scraped and no file was uploaded, dispatch the job to download it
        // We check if it's a URL (not a local path) and if it was set from metadata (not user upload)
        if (isset($incomingFields['image_url']) && filter_var($incomingFields['image_url'], FILTER_VALIDATE_URL)) {
            // Ensure this only happens if no file was uploaded by the user
            // The logic above already prioritizes user upload, so if image_url is still an external URL,
            // it means it came from scraping and no user file was present.
            DownloadWishlistImageJob::dispatch($wishlist->id, $incomingFields['image_url']);
        }

        return redirect()->back()->with('success', 'Dodal si izdelek v tovj seznam želja!');
    }
}
