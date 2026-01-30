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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('channel_id')->constrained()->onDelete('cascade');
            $table->string('video_id')->unique(); // YouTube Video ID
            $table->string('title')->nullable();
            $table->string('url')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->text('tags')->nullable(); // JSON stored as text
            $table->longText('subtitle_content')->nullable(); // The actual subtitle text
            $table->string('subtitle_lang')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->string('status')->default('pending'); // pending, fetched, failed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
