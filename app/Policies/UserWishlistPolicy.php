<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserWishlist;

class UserWishlistPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view wishlists
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UserWishlist $userWishlist): bool
    {
        return $user->id === $userWishlist->user_id || $userWishlist->visibility === \App\Enums\WishlistVisibility::Public;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // All authenticated users can create wishlists
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserWishlist $userWishlist): bool
    {
        return $user->id === $userWishlist->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserWishlist $userWishlist): bool
    {
        return $user->id === $userWishlist->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserWishlist $userWishlist): bool
    {
        return $user->id === $userWishlist->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserWishlist $userWishlist): bool
    {
        return $user->id === $userWishlist->user_id;
    }

    /**
     * Determine whether the user can add a wishlist item to the model.
     */
    public function addWishlistItem(User $user, UserWishlist $userWishlist): bool
    {
        return $user->id === $userWishlist->user_id;
    }
}
