<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('community_groups', function (Blueprint $table) {
            $table->unsignedSmallInteger('weekly_prayer_goal')->default(7)->after('invite_enabled');
            $table->string('reminder_day', 16)->nullable()->after('weekly_prayer_goal');
            $table->string('reminder_time', 16)->nullable()->after('reminder_day');
        });

        Schema::create('community_group_discussion_prompts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('week_start')->index();
            $table->string('title')->nullable();
            $table->text('prompt');
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('community_group_discussion_prompts');

        Schema::table('community_groups', function (Blueprint $table) {
            $table->dropColumn(['weekly_prayer_goal', 'reminder_day', 'reminder_time']);
        });
    }
};
