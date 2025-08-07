# GiftHub Technology Stack

## Backend

*   **Framework:** Laravel 10
*   **Language:** PHP 8.1+
*   **Database:** MySQL (inferred from standard Laravel setup)
*   **Dependencies:**
    *   `ashallendesign/short-url`: For creating shortened URLs for wish list items.
    *   `guzzlehttp/guzzle`: For making HTTP requests, likely used by the metadata scraper.
    *   `laravel/sanctum`: For API token authentication.

## Frontend

*   **Build Tool:** Vite
*   **CSS Framework:** Tailwind CSS
*   **JavaScript:** Vanilla JavaScript, as no major framework is specified in `package.json`.
*   **Templating:** Laravel Blade

## Development Environment

*   **Local Server:** Laragon
*   **Package Managers:** Composer (PHP), NPM (JavaScript)