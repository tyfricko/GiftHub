# GiftHub Application

GiftHub is a web application designed to simplify gift exchanges and manage personal wishlists. It allows users to create gift exchange events, invite participants, assign gifts, and maintain a personal wishlist of desired items, complete with metadata scraping for easy item addition.

## Features

*   **User Management:** Secure user authentication and profile management.
*   **Wishlist Management:** Create, view, and manage personal wishlists.
*   **Gift Exchange Events:** Organize gift exchange events with custom settings, including shipping requirements.
*   **Participant Invitations:** Invite friends and family to join gift exchange events via email.
*   **Automated Gift Assignment:** Randomly assign gift recipients among participants.
*   **Metadata Scraping:** Easily add items to wishlists by providing a URL, which automatically fetches product details.
*   **Short URLs:** Generate concise URLs for sharing wishlist items.
*   **Email Notifications:** Receive notifications for invitations, gift assignments, and other event updates.

## Technologies Used

*   **Backend:** Laravel (PHP Framework)
*   **Frontend:** Blade, JavaScript, Tailwind CSS
*   **Database:** MySQL
*   **Build Tools:** Composer, Node.js, Vite

## Installation

To get GiftHub up and running on your local machine, follow these steps:

### Prerequisites

Make sure you have the following installed:

*   PHP >= 8.1
*   Composer
*   Node.js & npm (or Yarn)
*   MySQL (or another compatible database)

### Setup Steps

1.  **Clone the Repository:**
    ```bash
    git clone https://github.com/your-username/GiftHub.git
    cd GiftHub
    ```

2.  **Install PHP Dependencies:**
    ```bash
    composer install
    ```

3.  **Environment Configuration:**
    *   Copy the example environment file:
        ```bash
        cp .env.example .env
        ```
    *   Open `.env` and configure your database connection and other settings (e.g., `APP_NAME`, `APP_URL`, `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
    *   Generate an application key:
        ```bash
        php artisan key:generate
        ```

4.  **Run Database Migrations:**
    ```bash
    php artisan migrate
    ```

5.  **Seed the Database (Optional):**
    If you have seeders to populate initial data:
    ```bash
    php artisan db:seed
    ```

6.  **Install Node Dependencies:**
    ```bash
    npm install
    # or yarn install
    ```

7.  **Compile Assets and Start Development Server:**
    ```bash
    npm run dev
    # or yarn dev
    ```

8.  **Serve the Application:**
    ```bash
    php artisan serve
    ```

    Your application should now be accessible at `http://127.0.0.1:8000` (or the URL specified in your `.env`).

## Usage

1.  Register a new user account or log in.
2.  Create your personal wishlist by adding items from various online stores.
3.  Organize a new gift exchange event and invite participants.
4.  Manage participants and see assigned gifts after the event is started.

## Contributing

Thank you for considering contributing to the GiftHub application! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within GiftHub, please send an e-mail to [info@matejzlatic.com](mailto:info@matejzlatic.com). All security vulnerabilities will be promptly addressed.

## License

The GiftHub application is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).