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
        Schema::rename('wishlists', 'wishlist_items');

        Schema::table('wishlist_items', function (Blueprint $table) {
            $table->index('wishlist_id', 'idx_wishlist_items_wishlist_id');
            $table->index('user_id', 'idx_wishlist_items_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wishlist_items', function (Blueprint $table) {
            $table->dropIndex('idx_wishlist_items_wishlist_id');
            $table->dropIndex('idx_wishlist_items_user_id');
        });

        Schema::rename('wishlist_items', 'wishlists');
    }
};
