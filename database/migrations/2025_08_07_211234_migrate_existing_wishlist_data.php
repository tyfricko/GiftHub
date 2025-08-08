<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create a default wishlist for each user and link existing items
        $users = User::all();

        foreach ($users as $user) {
            $defaultWishlistId = DB::table('user_wishlists')->insertGetId([
                'user_id' => $user->id,
                'name' => 'My Wishlist',
                'description' => 'My default wishlist',
                'visibility' => 'public',
                'is_default' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('wishlists')
                ->where('user_id', $user->id)
                ->update(['wishlist_id' => $defaultWishlistId]);
        }

        // Make wishlist_id required
        Schema::table('wishlists', function (Blueprint $table) {
            $table->foreignId('wishlist_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the changes: remove the default wishlists and set wishlist_id back to nullable
        Schema::table('wishlists', function (Blueprint $table) {
            $table->foreignId('wishlist_id')->nullable()->change();
        });

        DB::table('user_wishlists')->truncate();
    }
};
