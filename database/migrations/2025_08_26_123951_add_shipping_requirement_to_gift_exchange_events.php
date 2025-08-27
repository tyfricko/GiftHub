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
        Schema::table('gift_exchange_events', function (Blueprint $table) {
            $table->boolean('requires_shipping_address')->default(false)->after('budget_max');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gift_exchange_events', function (Blueprint $table) {
            $table->dropColumn('requires_shipping_address');
        });
    }
};
