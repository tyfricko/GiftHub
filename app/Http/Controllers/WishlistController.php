<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserWishlistRequest;
use App\Http\Requests\WishlistItemRequest;
use App\Http\Requests\WishlistUpdateRequest;
use App\Models\UserWishlist;
use App\Models\Wishlist;
use App\Services\WishlistManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WishlistController extends Controller
{
    public function showCreateForm(): \Illuminate\View\View
    {
        return view('add-wish');
    }

    /**
     * Display the current user's wishlist items.
     */
    public function index(): \Illuminate\Http\RedirectResponse
    {
        return redirect()->route('profile.wishlist');
    }

    public function storeNewWish(WishlistItemRequest $request): \Illuminate\Http\RedirectResponse
    {
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

        // Handle multiple wishlist assignment
        $selectedWishlistIds = [];

        if (! empty($incomingFields['user_wishlist_ids'])) {
            // Use the selected wishlists from the form
            $selectedWishlistIds = $incomingFields['user_wishlist_ids'];
            \Log::info('Add Wish Debug - Using selected wishlists: '.json_encode($selectedWishlistIds));
        } elseif (! empty($incomingFields['wishlist_id'])) {
            // Backward compatibility: single wishlist from URL parameter
            $selectedWishlistIds = [$incomingFields['wishlist_id']];
            \Log::info('Add Wish Debug - Using URL wishlist_id: '.$incomingFields['wishlist_id']);
        } else {
            // Default to user's default wishlist
            $defaultWishlist = auth()->user()->getOrCreateDefaultWishlist();
            $selectedWishlistIds = [$defaultWishlist->id];
            \Log::info('Add Wish Debug - Using default wishlist: '.$defaultWishlist->id);
        }

        // Remove wishlist-specific fields from the main item data
        unset($incomingFields['wishlist_id'], $incomingFields['user_wishlist_ids']);

        // Use the service to create the item
        $service = new WishlistManagementService();
        $service->createItem($incomingFields, $selectedWishlistIds, $request);

        return redirect('/')->with('success', 'Dodal si izdelek v tovj seznam želja!');
    }

    /**
     * Scrape metadata from a given URL and return as JSON.
     */
    public function scrapeUrl(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            'url' => 'required|url',
        ]);

        $scraper = new \App\Services\MetadataScraperService();
        $metadata = $scraper->scrape($validated['url']);

        return response()->json($metadata);
    }

    /**
     * Update a wishlist item (partial update).
     */
    public function update(WishlistUpdateRequest $request, $id): \Illuminate\Http\JsonResponse
    {
        $wishlist = Wishlist::find($id);

        if (! $wishlist) {
            return response()->json([
                'message' => 'Wishlist item not found.',
            ], 404);
        }

        $validated = $request->validated();

        if (empty($validated)) {
            return response()->json([
                'message' => 'No fields provided for update.',
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
            'data' => $wishlist->fresh(),
        ]);
    }

    /**
     * Update a wishlist item.
     */
    public function updateWish(Request $request, Wishlist $wish): \Illuminate\Http\RedirectResponse
    {
        if ($wish->user_id != auth()->id()) {
            return redirect('/');
        }

        $incomingFields = $request->validate([
            'itemname' => 'required|string',
            'url' => 'required|url',
            'price' => 'nullable|numeric',
            'description' => 'nullable|string',
            'image_url' => 'nullable|url',
            'user_wishlist_ids' => 'nullable|array',
            'user_wishlist_ids.*' => 'exists:user_wishlists,id',
            'sort_order' => 'nullable|integer|min:0',
            'image_file' => 'nullable|file|image|max:5120',
            'image' => 'nullable|file|image|max:5120',
        ]);

        // Sanitize required fields
        $incomingFields['itemname'] = strip_tags($incomingFields['itemname']);
        $incomingFields['url'] = strip_tags($incomingFields['url']);

        // Sanitize and cast optional fields
        if (isset($incomingFields['price'])) {
            $incomingFields['price'] = (float) $incomingFields['price'];
        }
        $incomingFields['currency'] = 'EUR';
        if (isset($incomingFields['description'])) {
            $incomingFields['description'] = strip_tags($incomingFields['description']);
        }
        if (isset($incomingFields['image_url'])) {
            $incomingFields['image_url'] = strip_tags($incomingFields['image_url']);
        }

        // Get wishlist IDs (handle both array and single ID)
        $wishlistIds = ! empty($incomingFields['user_wishlist_ids'])
            ? $incomingFields['user_wishlist_ids']
            : null;

        // Remove wishlist-specific fields from the main item data
        unset($incomingFields['user_wishlist_ids']);

        if (isset($incomingFields['sort_order'])) {
            $incomingFields['sort_order'] = (int) $incomingFields['sort_order'];
        }

        // Use the service to update the item
        $service = new WishlistManagementService();
        $service->updateItem($wish, $incomingFields, $wishlistIds, $request);

        return redirect('/profile/'.auth()->user()->username)->with('success', 'Wishlist item updated successfully.');
    }

    /**
     * Delete a wishlist item.
     */
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        \Log::info("Attempting to delete wishlist item with ID: {$id} by user: ".auth()->id());

        try {
            $wishlist = Wishlist::find($id);
            if (! $wishlist) {
                \Log::warning("Wishlist item with ID: {$id} not found.");

                return response()->json([
                    'success' => false,
                    'message' => 'Wishlist item not found.',
                ], 404);
            }

            if ($wishlist->user_id !== auth()->id()) {
                \Log::warning("Unauthorized attempt to delete wishlist item with ID: {$id} by user: ".auth()->id());

                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to delete this wishlist item.',
                ], 403);
            }

            $itemName = $wishlist->itemname;
            $service = new WishlistManagementService();
            $service->deleteItem($wishlist);
            \Log::info("Wishlist item '{$itemName}' with ID: {$id} deleted successfully.");

            return response()->json([
                'success' => true,
                'message' => "Item '{$itemName}' deleted successfully!",
            ]);

        } catch (\Exception $e) {
            \Log::error("Error deleting wishlist item with ID: {$id}. Error: ".$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the item: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified wish.
     */
    public function edit(Wishlist $wish): \Illuminate\Http\RedirectResponse|\Illuminate\View\View
    {
        if ($wish->user_id !== auth()->id()) {
            abort(403, 'You are not authorized to edit this wish.');
        }

        return view('add-wish', ['wish' => $wish]);
    }

    /**
     * Store a newly created user wishlist in storage.
     */
    public function storeUserWishlist(UserWishlistRequest $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validated();

        $service = new WishlistManagementService();
        $service->createUserWishlist(
            auth()->user(),
            $validated['name'],
            $validated['description'],
            $validated['visibility']
        );

        return redirect()->back()->with('success', 'Wishlist created successfully!');
    }

    /**
     * Update the specified user wishlist in storage.
     */
    public function updateUserWishlist(UserWishlistRequest $request, UserWishlist $userWishlist): \Illuminate\Http\RedirectResponse
    {
        // Policy will handle authorization
        $this->authorize('update', $userWishlist);

        $validated = $request->validated();

        $service = new WishlistManagementService();
        $service->updateUserWishlist(
            $userWishlist,
            $validated['name'],
            $validated['description'],
            $validated['visibility']
        );

        return redirect()->back()->with('success', 'Wishlist updated successfully!');
    }

    /**
     * Remove the specified user wishlist from storage.
     */
    public function destroyUserWishlist(UserWishlist $userWishlist): \Illuminate\Http\JsonResponse
    {
        \Log::info("Attempting to delete user wishlist with ID: {$userWishlist->id} by user: ".auth()->id());

        try {
            // Policy will handle authorization
            $this->authorize('delete', $userWishlist);
            \Log::info("Authorization passed for wishlist deletion: {$userWishlist->id}");

            $service = new WishlistManagementService();
            $service->deleteUserWishlist($userWishlist, auth()->user());

            $wishlistName = $userWishlist->name;
            \Log::info("User wishlist '{$wishlistName}' deleted successfully");

            return response()->json([
                'success' => true,
                'message' => "Wishlist '{$wishlistName}' deleted successfully!",
            ]);

        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            \Log::warning("Authorization failed for wishlist deletion: {$userWishlist->id} by user: ".auth()->id());

            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to delete this wishlist.',
            ], 403);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        } catch (\Exception $e) {
            \Log::error("Error deleting user wishlist with ID: {$userWishlist->id}. Error: ".$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting the wishlist: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created wish item to a specific user wishlist.
     */
    public function storeNewWishToSpecificWishlist(WishlistItemRequest $request, UserWishlist $userWishlist): \Illuminate\Http\RedirectResponse
    {
        // Policy will handle authorization
        $this->authorize('addWishlistItem', $userWishlist);

        $incomingFields = $request->validated();

        // Sanitize required fields
        $incomingFields['itemname'] = strip_tags($incomingFields['itemname']);
        $incomingFields['url'] = strip_tags($incomingFields['url']);
        $incomingFields['user_id'] = auth()->id();

        // Sanitize and cast optional fields
        if (isset($incomingFields['price'])) {
            $incomingFields['price'] = (float) $incomingFields['price'];
        }
        $incomingFields['currency'] = 'EUR';
        if (isset($incomingFields['description'])) {
            $incomingFields['description'] = strip_tags($incomingFields['description']);
        }

        // Remove wishlist-specific fields from the main item data
        unset($incomingFields['wishlist_id'], $incomingFields['user_wishlist_ids']);

        // Use the service to create the item
        $service = new WishlistManagementService();
        $service->createItem($incomingFields, [$userWishlist->id], $request);

        return redirect()->back()->with('success', 'Dodal si izdelek v tovj seznam želja!');
    }
}
