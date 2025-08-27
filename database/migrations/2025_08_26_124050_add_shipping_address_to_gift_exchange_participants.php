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
        Schema::table('gift_exchange_participants', function (Blueprint $table) {
            $table->json('shipping_address')->nullable()->after('joined_at');
            $table->timestamp('shipping_address_completed_at')->nullable()->after('shipping_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gift_exchange_participants', function (Blueprint $table) {
            $table->dropColumn('shipping_address');
            $table->dropColumn('shipping_address_completed_at');
        });
    }
};
