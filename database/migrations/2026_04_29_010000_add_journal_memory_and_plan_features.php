<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('journal_entries', function (Blueprint $table) {
            $table->string('mood')->nullable()->after('content')->index();
            $table->json('topics')->nullable()->after('mood');
        });

        Schema::create('memory_verse_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('week_start')->index();
            $table->foreignId('bible_verse_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reference');
            $table->longText('verse_text');
            $table->unsignedInteger('practiced_count')->default(0);
            $table->boolean('reminder_enabled')->default(false)->index();
            $table->timestamp('completed_at')->nullable()->index();
            $table->timestamps();

            $table->unique(['user_id', 'week_start']);
        });

        Schema::create('devotional_plan_completions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('plan_slug')->index();
            $table->unsignedSmallInteger('day_number');
            $table->foreignId('devotional_id')->nullable()->constrained()->nullOnDelete();
            $table->date('completed_on')->index();
            $table->timestamps();

            $table->unique(['user_id', 'plan_slug', 'day_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devotional_plan_completions');
        Schema::dropIfExists('memory_verse_progress');

        Schema::table('journal_entries', function (Blueprint $table) {
            $table->dropIndex(['mood']);
            $table->dropColumn(['mood', 'topics']);
        });
    }
};
