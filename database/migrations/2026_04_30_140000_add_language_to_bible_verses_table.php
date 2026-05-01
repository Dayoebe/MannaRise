<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bible_verses', function (Blueprint $table) {
            $table->dropUnique('bible_verse_unique');
        });

        Schema::table('bible_verses', function (Blueprint $table) {
            if (! Schema::hasColumn('bible_verses', 'language')) {
                $table->string('language', 12)->default('en')->after('version')->index();
            }

            $table->unique(['language', 'version', 'bible_book_id', 'chapter', 'verse'], 'bible_verse_language_unique');
        });
    }

    public function down(): void
    {
        Schema::table('bible_verses', function (Blueprint $table) {
            $table->dropUnique('bible_verse_language_unique');
        });

        Schema::table('bible_verses', function (Blueprint $table) {
            if (Schema::hasColumn('bible_verses', 'language')) {
                $table->dropColumn('language');
            }

            $table->unique(['version', 'bible_book_id', 'chapter', 'verse'], 'bible_verse_unique');
        });
    }
};
