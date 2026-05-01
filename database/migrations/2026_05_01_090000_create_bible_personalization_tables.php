<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_bible_verse_engagements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bible_verse_id')->constrained()->cascadeOnDelete();
            $table->string('highlight_color', 32)->nullable()->index();
            $table->text('note')->nullable();
            $table->timestamp('bookmarked_at')->nullable()->index();
            $table->timestamp('highlighted_at')->nullable()->index();
            $table->timestamp('note_updated_at')->nullable()->index();
            $table->timestamp('shared_at')->nullable()->index();
            $table->timestamps();

            $table->unique(['user_id', 'bible_verse_id'], 'user_bible_verse_engagement_unique');
        });

        Schema::create('user_bible_reading_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bible_book_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('chapter');
            $table->string('language', 12)->default('en');
            $table->string('version', 32)->default('KJV');
            $table->unsignedInteger('read_count')->default(0);
            $table->timestamp('last_read_at')->index();
            $table->timestamps();

            $table->unique(['user_id', 'bible_book_id', 'chapter', 'language', 'version'], 'user_bible_reading_history_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_bible_reading_histories');
        Schema::dropIfExists('user_bible_verse_engagements');
    }
};
