# GiftHub Architecture

## High-Level Overview

This project is a monolithic web application built with the Laravel framework, following the Model-View-Controller (MVC) architectural pattern. The frontend is rendered using Blade templates and styled with Tailwind CSS, with minimal JavaScript for client-side interactions.

## Backend Architecture (MVC)

*   **Models:** Located in `app/Models/`, these Eloquent models define the database schema and relationships.
    *   `User.php`: Registered users; hasMany `user_wishlists` and `wishlist_items` via [`public function userWishlists()`](app/Models/User.php:64) and [`public function wishlistItems()`](app/Models/User.php:69). Helpers [`public function getDefaultWishlist()`](app/Models/User.php:74) and [`public function getOrCreateDefaultWishlist()`](app/Models/User.php:79).
    *   `Wishlist.php`: Maps to `wishlist_items`; belongsTo `User` via [`public function user()`](app/Models/Wishlist.php:27); belongsToMany `UserWishlist` via pivot `wishlist_item_user_wishlist` in [`public function userWishlists()`](app/Models/Wishlist.php:32). Retains legacy [`public function userWishlist()`](app/Models/Wishlist.php:39) for migration compatibility.
    *   `UserWishlist.php`: Represents a user's wishlist group; belongsTo `User` via [`public function user()`](app/Models/UserWishlist.php:27); belongsToMany `Wishlist` via pivot in [`public function items()`](app/Models/UserWishlist.php:32); casts `visibility` to enum; enforces single default per user in [`public static function boot()`](app/Models/UserWishlist.php:54).
    *   `GiftExchangeEvent.php`: Represents a gift exchange event.
    *   `GiftExchangeParticipant.php`: Manages users participating in an event.
    *   `GiftExchangeInvitation.php`: Handles invitations to events.
    *   Enums: [`enum WishlistVisibility`](app/Enums/WishlistVisibility.php:5) provides public/private visibility for user wishlists.

*   **Views:** Located in `resources/views/`, these Blade files are responsible for rendering the UI.
    *   `homepage-redesigned.blade.php`: The main landing page for guests.
    *   `homepage-unverified.blade.php`: Unverified user homepage with verification prompts.
    *   `profile-wishlist.blade.php`: Displays a user's multi-wishlist view with grouped items.
    *   `profile-events.blade.php`: Profile tab page for events summary with full functionality.
    *   `profile-friends.blade.php`, `profile-settings.blade.php`: Profile tab pages showing removal notices (features temporarily removed).
    *   `add-wish.blade.php`: Add/edit wish form with multi-select of user wishlists and URL metadata scrape.
    *   `gift-exchange.blade.php`: Gift exchange dashboard (base).
    *   Components (resources/views/components/ui/): `tabs.blade.php`, `wishlist-group-card.blade.php`, `wishlist-item-card.blade.php`, `wishlist-management-toolbar.blade.php`, `avatar.blade.php`, `button.blade.php`, `card.blade.php`, `verification-banner.blade.php`, `navbar-enhanced.blade.php`, `profile-header.blade.php`, `event-card.blade.php`, `hero.blade.php`, `form-group.blade.php`, `input.blade.php`, `alert.blade.php`, `notification.blade.php`, `empty-state.blade.php`, etc.
    *   Modals (resources/views/components/modals/): `create-wishlist-modal.blade.php`, `edit-wishlist-modal.blade.php`.

*   **Controllers:** Located in `app/Http/Controllers/`, these classes handle the application's business logic.
    *   `UserController.php`: Authentication and profile management; tab actions [`public function profileWishlist()`](app/Http/Controllers/UserController.php:146), [`public function events()`](app/Http/Controllers/UserController.php:183), [`public function friends()`](app/Http/Controllers/UserController.php:232), [`public function settings()`](app/Http/Controllers/UserController.php:267); homepage routing [`public function showCorrectHomepage()`](app/Http/Controllers/UserController.php:100); creates default wishlist on register in [`public function register()`](app/Http/Controllers/UserController.php:82).
    *   `WishlistController.php`: Wishlist item creation/editing with metadata scrape, image upload precedence, URL normalization/shortening in [`public function storeNewWish()`](app/Http/Controllers/WishlistController.php:36) and [`public function updateWish()`](app/Http/Controllers/WishlistController.php:237); many-to-many attach/sync via pivot; user wishlist CRUD in [`public function storeUserWishlist()`](app/Http/Controllers/WishlistController.php:387), [`public function updateUserWishlist()`](app/Http/Controllers/WishlistController.php:404), [`public function destroyUserWishlist()`](app/Http/Controllers/WishlistController.php:423); add to specific wishlist in [`public function storeNewWishToSpecificWishlist()`](app/Http/Controllers/WishlistController.php:449).
    *   `GiftExchangeController.php`: Gift exchange dashboard and invitation flows.
    *   `MatchingController.php`: Matching logic for gift assignment (present for future/related flows).
*   **Policies:** Located in `app/Policies/`.
    *   `UserWishlistPolicy.php`: Authorizes update/delete/addWishlistItem operations on user wishlists.

## Middleware

*   `RequireEmailVerification.php` (custom): Redirects unverified users, preserves intended URL, and flashes a friendly message in [`public function handle()`](app/Http/Middleware/RequireEmailVerification.php:20). Aliased as `requireVerified` in [`protected $middlewareAliases`](app/Http/Kernel.php:55) (see line 69).
*   Built-in `verified` middleware: Available via alias `verified` in [`protected $middlewareAliases`](app/Http/Kernel.php:55) (see line 67) to gate routes requiring verified emails.

## Key Services

*   `MetadataScraperService.php`: A service located in `app/Services/` responsible for fetching metadata (title, image, price) from a given URL when a user adds a wish.
*   `ShortUrlService.php`: A service in `app/Services/` that integrates with the `ashallendesign/short-url` package to create shortened links for wish list items.

## Component Interaction Diagram

```mermaid
graph TD
    subgraph "Browser"
        A[User]
    end

    subgraph "Laravel Application"
        B[Web Routes - routes/web.php]
        C[API Routes - routes/api.php]
        D[Controllers]
        E[Models]
        F[Views - Blade Templates]
        G[Services]
        H[Database - MySQL]
    end

    A --> B
    A --> C

    B --> D
    C --> D

    D --> E
    D --> F
    D --> G

    E --> H
    G --> E

    F --> A