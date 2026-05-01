<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resource_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('type', 40)->nullable()->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('resource_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resource_category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('type', 40)->index();
            $table->string('source_name')->nullable()->index();
            $table->text('source_url')->nullable();
            $table->string('external_id')->nullable();
            $table->string('author')->nullable();
            $table->text('thumbnail_url')->nullable();
            $table->text('media_url')->nullable();
            $table->text('embed_url')->nullable();
            $table->string('language', 12)->default('en')->index();
            $table->string('license')->nullable();
            $table->json('tags')->nullable();
            $table->json('metadata')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_published')->default(true)->index();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();

            $table->index(['type', 'is_published', 'published_at']);
            $table->unique(['source_name', 'external_id']);
        });

        Schema::create('daily_devotions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('bible_reference')->nullable();
            $table->longText('bible_text')->nullable();
            $table->text('memory_verse')->nullable();
            $table->longText('devotion_text');
            $table->longText('prayer')->nullable();
            $table->json('reflection_questions')->nullable();
            $table->text('action_point')->nullable();
            $table->date('devotion_date')->unique();
            $table->string('author')->nullable();
            $table->boolean('is_published')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('user_resource_bookmarks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('resource_item_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('daily_devotion_id')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->index(['user_id', 'resource_item_id']);
            $table->index(['user_id', 'daily_devotion_id']);
        });

        Schema::create('user_resource_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('resource_item_id')->constrained()->cascadeOnDelete();
            $table->string('progress_type', 40)->nullable()->index();
            $table->unsignedTinyInteger('progress_value')->default(0);
            $table->timestamp('completed_at')->nullable()->index();
            $table->timestamp('last_accessed_at')->nullable()->index();
            $table->timestamps();

            $table->unique(['user_id', 'resource_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_resource_progress');
        Schema::dropIfExists('user_resource_bookmarks');
        Schema::dropIfExists('daily_devotions');
        Schema::dropIfExists('resource_items');
        Schema::dropIfExists('resource_categories');
    }
};
