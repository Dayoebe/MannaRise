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
        Schema::create('spiritual_book_chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spiritual_book_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('chapter_number');
            $table->string('title');
            $table->longText('content');
            $table->timestamps();

            $table->unique(['spiritual_book_id', 'chapter_number'], 'spiritual_book_chapter_unique');
            $table->fullText(['title', 'content']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spiritual_book_chapters');
    }
};
