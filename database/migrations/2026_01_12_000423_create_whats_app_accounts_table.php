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
        Schema::create('whats_app_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique();
            $table->string('name')->nullable(); // e.g., "Support", "Marketing"
            $table->string('phone_number')->nullable();
            $table->string('status')->default('disconnected'); // disconnected, connected, scan_qr
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whats_app_accounts');
    }
};
