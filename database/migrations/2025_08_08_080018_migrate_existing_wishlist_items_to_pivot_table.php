<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Migrate existing wishlist items to the new pivot table structure
        // This ensures backward compatibility with existing data

        DB::statement('
            INSERT INTO wishlist_item_user_wishlist (wishlist_item_id, user_wishlist_id, created_at, updated_at)
            SELECT
                wi.id as wishlist_item_id,
                wi.wishlist_id as user_wishlist_id,
                NOW() as created_at,
                NOW() as updated_at
            FROM wishlist_items wi
            WHERE wi.wishlist_id IS NOT NULL
            AND NOT EXISTS (
                SELECT 1 FROM wishlist_item_user_wishlist pivot
                WHERE pivot.wishlist_item_id = wi.id
                AND pivot.user_wishlist_id = wi.wishlist_id
            )
        ');

        \Log::info('Migrated existing wishlist items to pivot table structure');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove all pivot table entries that were created from the old structure
        DB::statement('
            DELETE pivot FROM wishlist_item_user_wishlist pivot
            INNER JOIN wishlist_items wi ON pivot.wishlist_item_id = wi.id
            WHERE pivot.user_wishlist_id = wi.wishlist_id
        ');

        \Log::info('Removed migrated pivot table entries');
    }
};
