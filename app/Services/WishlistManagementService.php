<?php

namespace App\Services;

use App\Jobs\DownloadWishlistImageJob;
use App\Models\UserWishlist;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistManagementService
{
    /**
     * Create a new wishlist item.
     */
    public function createItem(array $data, array $wishlistIds, Request $request): Wishlist
    {
        // Scrape metadata
        $scraper = new MetadataScraperService();
        $metadata = $scraper->scrape($data['url']);

        // Handle file upload for product image (takes precedence over image_url)
        if ($request->hasFile('image_file') && $request->file('image_file')->isValid()) {
            $path = $request->file('image_file')->store('wishlist_images', 'public');
            $data['image_url'] = $path;
        } elseif ($request->hasFile('image') && $request->file('image')->isValid()) {
            $path = $request->file('image')->store('wishlist_images', 'public');
            $data['image_url'] = $path;
        } elseif (! empty($metadata['image_url'])) {
            $data['image_url'] = $metadata['image_url'];
        } else {
            $data['image_url'] = null;
        }

        // Shorten URL if applicable
        $shortUrlService = new ShortUrlService();
        if (! preg_match('~^(?:f|ht)tps?://~i', $data['url'])) {
            $data['url'] = 'https://'.$data['url'];
        }
        $data['url'] = $shortUrlService->generate($data['url']);

        // Determine sort_order (use the highest from all selected wishlists)
        $maxSortOrder = 0;
        foreach ($wishlistIds as $wishlistId) {
            $wishlist = UserWishlist::find($wishlistId);
            if ($wishlist) {
                $wishlistMaxSort = $wishlist->items()->max('sort_order') ?? 0;
                $maxSortOrder = max($maxSortOrder, $wishlistMaxSort);
            }
        }
        $data['sort_order'] = $maxSortOrder + 1;

        // Create the wishlist item
        $wishlist = Wishlist::create($data);

        // Attach the item to all selected wishlists using the pivot table
        $wishlist->userWishlists()->attach($wishlistIds);

        // If an image_url was scraped and no file was uploaded, dispatch the job to download it
        if (isset($data['image_url']) && filter_var($data['image_url'], FILTER_VALIDATE_URL)) {
            DownloadWishlistImageJob::dispatch($wishlist->id, $data['image_url']);
        }

        return $wishlist;
    }

    /**
     * Update a wishlist item.
     */
    public function updateItem(Wishlist $wish, array $data, ?array $wishlistIds, Request $request): Wishlist
    {
        // Handle file upload for product image (takes precedence over image_url)
        if ($request->hasFile('image_file') && $request->file('image_file')->isValid()) {
            $path = $request->file('image_file')->store('wishlist_images', 'public');
            $data['image_url'] = $path;
        } elseif ($request->hasFile('image') && $request->file('image')->isValid()) {
            $path = $request->file('image')->store('wishlist_images', 'public');
            $data['image_url'] = $path;
        }

        // Shorten URL only if it has changed
        $shortUrlService = new ShortUrlService();

        // Normalize and sanitize incoming URL
        $incomingUrl = $data['url'] ?? $wish->url;
        if (! preg_match('~^(?:f|ht)tps?://~i', $incomingUrl)) {
            $incomingUrl = 'https://'.$incomingUrl;
        }
        $incomingUrl = strip_tags($incomingUrl);

        // Normalize and sanitize original URL for comparison
        $originalUrl = $wish->url;
        if (! preg_match('~^(?:f|ht)tps?://~i', $originalUrl)) {
            $originalUrl = 'https://'.$originalUrl;
        }
        $originalUrl = strip_tags($originalUrl);

        if ($incomingUrl !== $originalUrl) {
            $data['url'] = $shortUrlService->generate($incomingUrl);
        } else {
            $data['url'] = $wish->url;
        }

        // Handle multiple wishlist assignment for updates
        if (! empty($wishlistIds)) {
            // Validate that all selected wishlists belong to the current user
            $userWishlists = $request->user()->userWishlists()->whereIn('id', $wishlistIds)->get();
            if ($userWishlists->count() !== count($wishlistIds)) {
                throw new \InvalidArgumentException('Some selected wishlists are invalid.');
            }

            // Update the pivot table relationships
            $wish->userWishlists()->sync($wishlistIds);
        }

        $wish->update($data);

        return $wish->fresh();
    }

    /**
     * Delete a wishlist item.
     */
    public function deleteItem(Wishlist $wish): bool
    {
        return $wish->delete();
    }

    /**
     * Create a new user wishlist.
     */
    public function createUserWishlist(\App\Models\User $user, string $name, ?string $description, string $visibility): UserWishlist
    {
        return $user->userWishlists()->create([
            'name' => $name,
            'description' => $description,
            'visibility' => \App\Enums\WishlistVisibility::from($visibility),
            'is_default' => false,
        ]);
    }

    /**
     * Update a user wishlist.
     */
    public function updateUserWishlist(UserWishlist $userWishlist, string $name, ?string $description, string $visibility): UserWishlist
    {
        $userWishlist->update([
            'name' => $name,
            'description' => $description,
            'visibility' => \App\Enums\WishlistVisibility::from($visibility),
        ]);

        return $userWishlist->fresh();
    }

    /**
     * Delete a user wishlist.
     *
     * @throws \InvalidArgumentException
     */
    public function deleteUserWishlist(UserWishlist $userWishlist, \App\Models\User $user): bool
    {
        // Prevent deletion if it's the user's only wishlist
        $userWishlistCount = $user->userWishlists()->count();

        if ($userWishlistCount === 1) {
            throw new \InvalidArgumentException('Cannot delete the last wishlist. Please create another wishlist first.');
        }

        // If the deleted wishlist was the default, set another one as default
        if ($userWishlist->is_default) {
            $newDefault = $user->userWishlists()->where('id', '!=', $userWishlist->id)->first();
            if ($newDefault) {
                $newDefault->update(['is_default' => true]);
            }
        }

        return $userWishlist->delete();
    }

    /**
     * Get validated wishlist IDs for the current user.
     */
    public function getValidatedWishlistIds(array $selectedIds, \App\Models\User $user): array
    {
        $userWishlists = $user->userWishlists()->whereIn('id', $selectedIds)->get();

        if ($userWishlists->count() !== count($selectedIds)) {
            throw new \InvalidArgumentException('Some selected wishlists are invalid.');
        }

        return $selectedIds;
    }
}
