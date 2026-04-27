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
        Schema::create('bible_verses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bible_book_id')->constrained()->cascadeOnDelete();
            $table->string('version', 12)->default('KJV')->index();
            $table->unsignedTinyInteger('chapter');
            $table->unsignedTinyInteger('verse');
            $table->text('text');
            $table->timestamps();

            $table->unique(['version', 'bible_book_id', 'chapter', 'verse'], 'bible_verse_unique');
            $table->fullText(['text']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bible_verses');
    }
};
