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
        Schema::create('ui_elements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('class_name')->nullable(); // For reference, e.g., .btn-custom
            $table->longText('html_code');
            $table->longText('css_code');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ui_elements');
    }
};
