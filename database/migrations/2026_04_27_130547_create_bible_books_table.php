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
        Schema::create('bible_books', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('book_order')->unique();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('abbreviation', 12);
            $table->string('testament', 16)->index();
            $table->unsignedTinyInteger('chapters');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bible_books');
    }
};
