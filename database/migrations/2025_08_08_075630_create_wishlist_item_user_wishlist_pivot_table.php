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
        Schema::create('wishlist_item_user_wishlist', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wishlist_item_id')->constrained('wishlist_items')->onDelete('cascade');
            $table->foreignId('user_wishlist_id')->constrained('user_wishlists')->onDelete('cascade');
            $table->timestamps();

            // Ensure unique combinations (prevent duplicate entries)
            $table->unique(['wishlist_item_id', 'user_wishlist_id'], 'unique_item_wishlist');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlist_item_user_wishlist');
    }
};
