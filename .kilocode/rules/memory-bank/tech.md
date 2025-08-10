# GiftHub Technology Stack

## Backend

- Framework: Laravel 10
- Language: PHP 8.1+
- Database: MySQL
- Dependencies:
  - ashallendesign/short-url (URL shortening)
  - guzzlehttp/guzzle (HTTP client for scraping)
  - laravel/sanctum (API token authentication)
- Services:
  - [app/Services/ShortUrlService.php](app/Services/ShortUrlService.php)
  - [app/Services/MetadataScraperService.php](app/Services/MetadataScraperService.php)
- Jobs:
  - [app/Jobs/DownloadWishlistImageJob.php](app/Jobs/DownloadWishlistImageJob.php) used to fetch scraped images asynchronously when no user upload is provided
- Enums:
  - [enum WishlistVisibility](app/Enums/WishlistVisibility.php:5) with values public and private (used by User Wishlist visibility)

## Frontend

- Build Tool: Vite
- CSS Framework: Tailwind CSS
- JavaScript: Vanilla JavaScript (no major SPA framework)
- Templating: Laravel Blade
- UI Components: Reusable Blade components under resources/views/components/ui (tabs, cards, buttons, avatar, wishlist cards)

## Development Environment

- Local Server: Laragon
- Local URL: https://GiftHub.test
- Package Managers: Composer (PHP), NPM (JavaScript)

## Notable Implementation Details

- Multi-wishlist data model:
  - Wishlist items are stored in table mapped by [app/Models/Wishlist.php](app/Models/Wishlist.php) and linked many-to-many to user wishlists via pivot
  - Pivot table: [2025_08_08_075630_create_wishlist_item_user_wishlist_pivot_table.php](database/migrations/2025_08_08_075630_create_wishlist_item_user_wishlist_pivot_table.php)
  - User wishlists table: [2025_08_07_211044_create_user_wishlists_table.php](database/migrations/2025_08_07_211044_create_user_wishlists_table.php)
  - Legacy rename and backfill:
    - [2025_08_07_211131_add_wishlist_reference_to_wishlists_table.php](database/migrations/2025_08_07_211131_add_wishlist_reference_to_wishlists_table.php)
    - [2025_08_07_211234_migrate_existing_wishlist_data.php](database/migrations/2025_08_07_211234_migrate_existing_wishlist_data.php)
    - [2025_08_07_211320_rename_wishlists_to_wishlist_items.php](database/migrations/2025_08_07_211320_rename_wishlists_to_wishlist_items.php)
    - [2025_08_08_083525_make_wishlist_id_nullable_in_wishlist_items_table.php](database/migrations/2025_08_08_083525_make_wishlist_id_nullable_in_wishlist_items_table.php)

- Controllers:
  - Multi-wishlist create/update, URL normalization/shortening, image precedence handled in [app/Http/Controllers/WishlistController.php](app/Http/Controllers/WishlistController.php)
  - Profile tabs and homepage routing in [app/Http/Controllers/UserController.php](app/Http/Controllers/UserController.php)

## Testing Credentials

- Test User: matej:test123
- Note: Use these existing credentials for local testing