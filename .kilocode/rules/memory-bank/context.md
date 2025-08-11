# Context

*   **Current status:** Multi-wishlist functionality is fully implemented and operational. Profile tab navigation is scaffolded and routed for wishlists, events, friends, and settings. Gift exchange system is fully functional with complete invitation flow, event editing, and secure access controls. Avatar display fixes and auth-based navigation are completed. Homepage logic directs signed-in users with items to profile wishlists, otherwise to a feed page.

*   **Recent changes:**
    *   **Gift Exchange System (August 2025):**
        *   Complete invitation system with email verification and multi-state user handling
        *   Owner-only event editing functionality with proper authorization
        *   Avatar display fixes in event participant lists
        *   Custom error pages for unauthorized invitation access
        *   Guest invitation pages for non-registered users
        *   Public invitation routes moved outside auth middleware for accessibility
        *   Debugging infrastructure with comprehensive logging
    *   **Data model and migrations:**
        *   `2025_08_07_211044_create_user_wishlists_table.php` created `user_wishlists` with visibility, is_default, sort_order and unique default per user.
        *   `2025_08_07_211131_add_wishlist_reference_to_wishlists_table.php` added `wishlist_id` and `sort_order` to legacy `wishlists` prior to rename.
        *   `2025_08_07_211234_migrate_existing_wishlist_data.php` backfilled default wishlists for all users and linked existing items, then enforced non-null `wishlist_id`.
        *   `2025_08_07_211320_rename_wishlists_to_wishlist_items.php` renamed `wishlists` to `wishlist_items` and added indexes.
        *   `2025_08_08_075630_create_wishlist_item_user_wishlist_pivot_table.php` created pivot `wishlist_item_user_wishlist` with unique constraint and cascade deletes.
        *   `2025_08_08_083525_make_wishlist_id_nullable_in_wishlist_items_table.php` made `wishlist_id` nullable to support many-to-many.
    *   **Models and enums:**
        *   `App/Models/UserWishlist.php` has many items via pivot, casts `visibility` to `App\Enums\WishlistVisibility`, enforces single default per user, includes `public` and `private` scopes.
        *   `App/Models/Wishlist.php` maps to `wishlist_items`, belongsTo `User`, belongsToMany `UserWishlist` via pivot; legacy relationships retained for migration compatibility.
        *   `App/Models/User.php` hasMany `userWishlists` and `wishlistItems`; helpers `getDefaultWishlist` and `getOrCreateDefaultWishlist`; name accessor and mutator.
        *   `App/Enums/WishlistVisibility.php` defines `public` and `private`.
        *   Gift exchange models: `GiftExchangeEvent`, `GiftExchangeParticipant`, `GiftExchangeInvitation`, `GiftAssignment` with proper relationships.
    *   **Controllers and routes:**
        *   `WishlistController` now handles multi-wishlist create and update (attach/sync pivot), image upload precedence, URL normalization and shortening, CRUD for user wishlists, and storing to a specific wishlist.
        *   `UserController` creates a default wishlist on register, exposes tab actions `profileWishlist`, `events`, `friends`, `settings`, and `showCorrectHomepage` routes users appropriately.
        *   `GiftExchangeController` handles complete event lifecycle: creation, editing (owner-only), invitation management, participant handling, and secure access controls.
        *   `routes/web.php` adds named routes for profile tabs, wishlist management routes, and gift-exchange routes with proper middleware separation.
    *   **Views and components:**
        *   `resources/views/add-wish.blade.php` includes multi-select checkbox list of user wishlists with defaults and edit support.
        *   New tab views: `profile-events.blade.php`, `profile-friends.blade.php`, `profile-settings.blade.php`.
        *   Gift exchange views: `gift-exchange.blade.php`, `gift-exchange/show.blade.php`, `gift-exchange/edit.blade.php`, `gift-exchange/invitation.blade.php`, `gift-exchange/invitation-guest.blade.php`, `gift-exchange/invitation-error.blade.php`.
        *   UI components: `components/ui/tabs.blade.php`, `components/ui/wishlist-group-card.blade.php`, `components/ui/wishlist-management-toolbar.blade.php`.
    *   **Services and jobs:**
        *   `MetadataScraperService` used during create/edit.
        *   `ShortUrlService` normalizes and shortens URLs.
        *   `DownloadWishlistImageJob` fetches scraped images when no user upload is present.

*   **Next steps:**
    *   Phase 2: Enhanced Navigation Tabs
        *   Wire Create Event CTA to the gift exchange create flow and preserve dashboard links.
        *   Implement settings persistence and validation; connect default wishlist visibility to `App\Enums\WishlistVisibility`.
        *   Add improved empty states, small UX polish, and links across tabs.
    *   Phase 3: Social Features
        *   Implement follow system and activity feed according to drafted SQL schemas.
        *   Add wishlist sharing and gift purchase coordination features.
    *   Phase 4: Gift Exchange Enhancements
        *   Implement gift assignment functionality and Secret Santa matching
        *   Add email notifications for assignments and reminders
        *   Implement post-registration invitation acceptance flow
    *   Cleanup and QA
        *   Remove remaining debug labels and CSS utilities used during testing.
        *   Remove debugging logs from production code.
        *   Add unit and feature tests for models, policies, controllers, and key user flows.
        *   Validate cascade deletes and pivot integrity during destructive actions.