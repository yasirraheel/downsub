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
        // Drop tables in reverse order of dependency
        Schema::dropIfExists('campaign_contacts');
        Schema::dropIfExists('campaigns');
        Schema::dropIfExists('auto_replies');
        Schema::dropIfExists('whats_app_accounts');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tables are permanently deleted.
        // To reverse, restore from previous migrations or backups.
    }
};
