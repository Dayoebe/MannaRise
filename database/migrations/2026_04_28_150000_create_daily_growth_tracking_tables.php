<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_rhythm_check_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('checked_on')->index();
            $table->string('verse_reference')->nullable();
            $table->string('affirmation_reference')->nullable();
            $table->string('bible_reading_label')->nullable();
            $table->timestamp('verse_completed_at')->nullable();
            $table->timestamp('affirmation_completed_at')->nullable();
            $table->timestamp('challenge_completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'checked_on']);
        });

        Schema::create('bible_chapter_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bible_book_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('chapter');
            $table->date('assigned_on')->nullable()->index();
            $table->string('source', 48)->default('bible-in-a-year')->index();
            $table->timestamp('completed_at');
            $table->timestamps();

            $table->unique(['user_id', 'bible_book_id', 'chapter'], 'bible_chapter_completion_unique');
        });

        Schema::create('user_spiritual_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('season', 64)->default('peace')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_spiritual_profiles');
        Schema::dropIfExists('bible_chapter_completions');
        Schema::dropIfExists('daily_rhythm_check_ins');
    }
};
