<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use AshAllenDesign\ShortURL\Classes\Builder;
use App\Services\ShortUrlService;
use App\Jobs\DownloadWishlistImageJob;
use Illuminate\Support\Facades\Storage;

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
        $wishlists = auth()->user()->wishes()->latest()->get();
        return view('profile-wishlist', [
            'wishes' => $wishlists,  // Change key to match what the view expects
            'username' => auth()->user()->username
        ]);
    }

    public function storeNewWish(Request $request) {
        $incomingFields = $request->validate([
            'itemname' => 'required|string',
            'url'  => 'required|url',
            'price' => 'nullable|numeric',
            'description' => 'nullable|string',
            'image_url' => 'nullable|string',
            'image_file' => 'nullable|file|image|max:5120', // max 5MB
            'image' => 'nullable|file|image|max:5120', // also allow "image" field
        ]);

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

        $wishlist = Wishlist::create($incomingFields);

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

        $fields = $request->only(['title', 'description', 'price', 'currency', 'image_url']);

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
}
