# Wishlist Item Feature Design

This document outlines the proposed database and API changes for enhancing the "Add Wishlist Item" feature to include optional fields for price, currency, description, and product image URL.

## 1. Database Schema Changes

The `wishlists` table (currently defined in `database/migrations/2023_10_02_113559_create_wishlists_table.php`) needs to be modified to include the new optional fields.

**Current `wishlists` table schema:**

```sql
CREATE TABLE `wishlists` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `itemname` VARCHAR(255) NOT NULL,
    `url` VARCHAR(255) NOT NULL,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
);
```

**Proposed Modifications (New Migration):**

A new migration file should be created (e.g., `YYYY_MM_DD_HHMMSS_add_optional_fields_to_wishlists_table.php`) to add the following nullable columns to the `wishlists` table:

*   `price`: DECIMAL(8, 2) - To store the price of the item.
*   `currency`: VARCHAR(10) - To store the currency code (e.g., "USD", "EUR").
*   `description`: TEXT - To store a short description of the item.
*   `image_url`: VARCHAR(255) - To store the URL of the product image.

**Example Migration Structure:**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('wishlists', function (Blueprint $table) {
            $table->decimal('price', 8, 2)->nullable()->after('url');
            $table->string('currency', 10)->nullable()->after('price');
            $table->text('description')->nullable()->after('currency');
            $table->string('image_url')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wishlists', function (Blueprint $table) {
            $table->dropColumn(['price', 'currency', 'description', 'image_url']);
        });
    }
};
```

## 2. API Endpoint Design

The existing API endpoint for adding wishlist items, handled by the `storeNewWish` method in `app/Http/Controllers/WishlistController.php`, needs to be updated to accept the new optional fields.

**Current `storeNewWish` method signature and validation:**

```php
public function storeNewWish(Request $request) {
    $incomingFields = $request->validate([
        'itemname' => 'required',
        'url'  => 'required'
    ]);
    // ...
}
```

**Proposed API Endpoint Changes:**

The `storeNewWish` method should be modified to include the new optional fields in its validation rules. These fields should be marked as `nullable`.

**Request Method:** POST
**Endpoint:** `/store-new-wish` (assuming this is the current route for `storeNewWish`)

**Request Body Parameters:**

*   `itemname`: (string, required) - The name of the wishlist item.
*   `url`: (string, required) - The URL of the product.
*   `price`: (decimal, optional) - The price of the item.
*   `currency`: (string, optional) - The currency code for the price.
*   `description`: (string, optional) - A short description of the item.
*   `image_url`: (string, optional) - The URL of the product image.

**Updated `storeNewWish` method (conceptual):**

```php
public function storeNewWish(Request $request) {
    $incomingFields = $request->validate([
        'itemname' => 'required|string',
        'url'  => 'required|url', // Assuming 'url' should be validated as a URL
        'price' => 'nullable|numeric',
        'currency' => 'nullable|string|max:10',
        'description' => 'nullable|string',
        'image_url' => 'nullable|url', // Assuming 'image_url' should be validated as a URL
    ]);

    // Sanitize incoming fields (example, adjust as needed)
    $incomingFields['itemname'] = strip_tags($incomingFields['itemname']);
    $incomingFields['url'] = strip_tags($incomingFields['url']);
    $incomingFields['user_id'] = auth()->id();

    // Handle optional fields
    if (isset($incomingFields['price'])) {
        $incomingFields['price'] = (float) $incomingFields['price'];
    }
    if (isset($incomingFields['currency'])) {
        $incomingFields['currency'] = strip_tags($incomingFields['currency']);
    }
    if (isset($incomingFields['description'])) {
        $incomingFields['description'] = strip_tags($incomingFields['description']);
    }
    if (isset($incomingFields['image_url'])) {
        $incomingFields['image_url'] = strip_tags($incomingFields['image_url']);
    }

    // Shorten URL if applicable (existing logic)
    $shortUrlService = new ShortUrlService();
    $incomingFields['url'] = $shortUrlService->generate($incomingFields['url']);

    Wishlist::create($incomingFields);

    return redirect("/")->with('success', 'Dodal si izdelek v tovj seznam želja!');
}
```

**Considerations:**

*   **Validation Rules:** The validation rules for `price`, `currency`, `description`, and `image_url` should be adjusted based on specific business logic and constraints (e.g., `max` length for strings, `min/max` for numeric values).
*   **Sanitization:** Ensure proper sanitization of all incoming fields to prevent XSS or other injection attacks. `strip_tags` is used as an example, but more robust sanitization might be required.
*   **Model Fillable Properties:** The `Wishlist` model will need to have the new fields (`price`, `currency`, `description`, `image_url`) added to its `$fillable` array to allow mass assignment.
## Wishlist Item Management API

This section details the API endpoints for managing individual wishlist items, including updating and deleting them.

### 3.1 Update Wishlist Item

This endpoint allows for updating specific fields of an existing wishlist item.

*   **Endpoint:** `PATCH /api/wishlist/{id}`
*   **Method:** `PATCH`
*   **Description:** Updates one or more fields of a specific wishlist item identified by its ID.
*   **Authentication:** Requires authentication (e.g., Bearer Token). The user must own the wishlist item.

#### Request

*   **Headers:**
    *   `Content-Type: application/json`
    *   `Authorization: Bearer <token>`
*   **Path Parameters:**
    *   `id`: (integer, required) - The unique identifier of the wishlist item.
*   **Body Parameters (JSON):**
    *   `title`: (string, optional) - The new name of the wishlist item.
    *   `description`: (string, optional) - A new description for the item.
    *   `price`: (decimal, optional) - The new price of the item.
    *   `currency`: (string, optional) - The new currency code for the price (e.g., "USD", "EUR").
    *   `image_url`: (string, optional) - The new URL of the product image.

*   **Example Request Body:**
    ```json
    {
        "title": "Updated Item Name",
        "description": "A new description for the item.",
        "price": 29.99,
        "currency": "EUR",
        "image_url": "https://example.com/new-image.jpg"
    }
    ```

#### Validation Rules

*   `title`: `nullable|string|max:255`
*   `description`: `nullable|string`
*   `price`: `nullable|numeric|min:0`
*   `currency`: `nullable|string|max:10` (e.g., ISO 4217 codes like USD, EUR)
*   `image_url`: `nullable|url`
*   **Note:** At least one of the optional fields (`title`, `description`, `price`, `currency`, `image_url`) must be present in the request body.

#### Responses

*   **`200 OK` - Success**
    *   **Description:** Wishlist item updated successfully.
    *   **Body (JSON):**
        ```json
        {
            "message": "Wishlist item updated successfully.",
            "data": {
                "id": 123,
                "itemname": "Updated Item Name",
                "url": "https://example.com/product-page",
                "price": 29.99,
                "currency": "EUR",
                "description": "A new description for the item.",
                "image_url": "https://example.com/new-image.jpg",
                "user_id": 456,
                "created_at": "2023-01-01T10:00:00Z",
                "updated_at": "2023-01-01T10:30:00Z"
            }
        }
        ```

*   **`400 Bad Request` - No fields provided for update**
    *   **Description:** No valid fields were provided in the request body for update.
    *   **Body (JSON):**
        ```json
        {
            "message": "No fields provided for update."
        }
        ```

*   **`403 Forbidden` - Unauthorized**
    *   **Description:** The authenticated user does not have permission to update this wishlist item.
    *   **Body (JSON):**
        ```json
        {
            "message": "You are not authorized to update this wishlist item."
        }
        ```

*   **`404 Not Found` - Item not found**
    *   **Description:** The specified wishlist item was not found.
    *   **Body (JSON):**
        ```json
        {
            "message": "Wishlist item not found."
        }
        ```

*   **`422 Unprocessable Entity` - Validation Failure**
    *   **Description:** The request body contains invalid data.
    *   **Body (JSON):**
        ```json
        {
            "message": "The given data was invalid.",
            "errors": {
                "price": [
                    "The price field must be a number."
                ],
                "image_url": [
                    "The image url field must be a valid URL."
                ]
            }
        }
        ```

### 3.2 Delete Wishlist Item

This endpoint allows for deleting an existing wishlist item.

*   **Endpoint:** `DELETE /api/wishlist/{id}`
*   **Method:** `DELETE`
*   **Description:** Deletes a specific wishlist item identified by its ID.
*   **Authentication:** Requires authentication (e.g., Bearer Token). The user must own the wishlist item.

#### Request

*   **Headers:**
    *   `Authorization: Bearer <token>`
*   **Path Parameters:**
    *   `id`: (integer, required) - The unique identifier of the wishlist item.

#### Responses

*   **`204 No Content` - Success**
    *   **Description:** Wishlist item deleted successfully. No content is returned for a successful deletion.

*   **`403 Forbidden` - Unauthorized**
    *   **Description:** The authenticated user does not have permission to delete this wishlist item.
    *   **Body (JSON):**
        ```json
        {
            "message": "You are not authorized to delete this wishlist item."
        }
        ```

*   **`404 Not Found` - Item not found**
    *   **Description:** The specified wishlist item was not found.
    *   **Body (JSON):**
        ```json
        {
            "message": "Wishlist item not found."
        }
        ```