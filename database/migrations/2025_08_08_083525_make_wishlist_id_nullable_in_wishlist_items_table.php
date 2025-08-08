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
        Schema::table('wishlist_items', function (Blueprint $table) {
            // Make wishlist_id nullable since we're now using the pivot table for many-to-many relationships
            $table->unsignedBigInteger('wishlist_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wishlist_items', function (Blueprint $table) {
            // Revert wishlist_id back to not nullable
            $table->unsignedBigInteger('wishlist_id')->nullable(false)->change();
        });
    }
};
