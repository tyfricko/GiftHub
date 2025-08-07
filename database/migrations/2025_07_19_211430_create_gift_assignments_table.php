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
        Schema::create('gift_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('gift_exchange_events')->onDelete('cascade');
            $table->foreignId('giver_id')->constrained('gift_exchange_participants')->onDelete('cascade');
            $table->foreignId('recipient_id')->constrained('gift_exchange_participants')->onDelete('cascade');
            $table->dateTime('assigned_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gift_assignments');
    }
};