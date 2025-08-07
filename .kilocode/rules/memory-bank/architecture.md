# GiftHub Architecture

## High-Level Overview

This project is a monolithic web application built with the Laravel framework, following the Model-View-Controller (MVC) architectural pattern. The frontend is rendered using Blade templates and styled with Tailwind CSS, with minimal JavaScript for client-side interactions.

## Backend Architecture (MVC)

*   **Models:** Located in `app/Models/`, these Eloquent models define the database schema and relationships.
    *   `User.php`: Represents registered users.
    *   `Wishlist.php`: Represents a single item on a user's wish list.
    *   `GiftExchangeEvent.php`: Represents a gift exchange event.
    *   `GiftExchangeParticipant.php`: Manages users participating in an event.
    *   `GiftExchangeInvitation.php`: Handles invitations to events.

*   **Views:** Located in `resources/views/`, these Blade files are responsible for rendering the UI.
    *   `homepage.blade.php`: The main landing page.
    *   `profile-wishlist.blade.php`: Displays a user's wish list.
    *   `add-wish.blade.php`: The form for adding a new wish.
    *   `gift-exchange.blade.php`: The dashboard for managing gift exchange events.

*   **Controllers:** Located in `app/Http/Controllers/`, these classes handle the application's business logic.
    *   `UserController.php`: Manages user authentication and profiles.
    *   `WishlistController.php`: Handles CRUD operations for wish list items.
    *   `GiftExchangeController.php`: Manages the logic for gift exchange events.

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