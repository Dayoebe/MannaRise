<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_spiritual_profiles', function (Blueprint $table) {
            $table->json('seasons')->nullable()->after('season');
            $table->string('path_goal')->nullable()->after('seasons');
            $table->text('support_note')->nullable()->after('path_goal');
            $table->string('preferred_time', 32)->nullable()->after('support_note');
        });

        Schema::create('personalized_daily_path_check_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('checked_on')->index();
            $table->string('season_key', 64)->index();
            $table->foreignId('devotional_id')->nullable()->constrained()->nullOnDelete();
            $table->string('bible_reference')->nullable();
            $table->timestamp('devotional_completed_at')->nullable();
            $table->timestamp('scripture_completed_at')->nullable();
            $table->timestamp('affirmation_completed_at')->nullable();
            $table->timestamp('prayer_completed_at')->nullable();
            $table->timestamp('journal_completed_at')->nullable();
            $table->timestamp('action_completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'checked_on'], 'personalized_path_daily_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personalized_daily_path_check_ins');

        Schema::table('user_spiritual_profiles', function (Blueprint $table) {
            $table->dropColumn(['seasons', 'path_goal', 'support_note', 'preferred_time']);
        });
    }
};
