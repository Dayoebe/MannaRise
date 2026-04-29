<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('community_groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('ministry_type', 40)->default('small_group')->index();
            $table->text('description')->nullable();
            $table->string('visibility', 24)->default('private')->index();
            $table->boolean('invite_enabled')->default(true)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('community_group_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role', 24)->default('member')->index();
            $table->timestamp('joined_at')->nullable();
            $table->date('last_read_on')->nullable()->index();
            $table->unsignedInteger('current_reading_streak')->default(0);
            $table->unsignedInteger('longest_reading_streak')->default(0);
            $table->unsignedInteger('completed_chapters_count')->default(0);
            $table->timestamps();

            $table->unique(['community_group_id', 'user_id'], 'community_members_unique');
        });

        Schema::create('community_group_invites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('token')->unique();
            $table->string('label')->nullable();
            $table->unsignedInteger('max_uses')->nullable();
            $table->unsignedInteger('uses_count')->default(0);
            $table->timestamp('expires_at')->nullable()->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('community_group_reading_challenges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_group_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('starts_on')->index();
            $table->date('ends_on')->nullable()->index();
            $table->unsignedSmallInteger('daily_chapter_goal')->default(1);
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::create('community_group_reading_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_group_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('community_group_reading_challenge_id')->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bible_book_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedSmallInteger('chapter')->nullable();
            $table->date('read_on')->index();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('community_group_reading_challenge_id', 'community_reading_challenge_fk')
                ->references('id')
                ->on('community_group_reading_challenges')
                ->nullOnDelete();

            $table->unique([
                'community_group_id',
                'community_group_reading_challenge_id',
                'user_id',
                'bible_book_id',
                'chapter',
                'read_on',
            ], 'community_reading_log_unique');
        });

        Schema::create('community_group_prayers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->longText('body');
            $table->boolean('is_answered')->default(false)->index();
            $table->unsignedInteger('prayed_count')->default(0);
            $table->timestamps();
        });

        Schema::create('community_group_prayer_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('community_group_prayer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->date('prayed_on')->index();
            $table->timestamps();

            $table->unique(['community_group_prayer_id', 'user_id', 'prayed_on'], 'community_prayer_log_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('community_group_prayer_logs');
        Schema::dropIfExists('community_group_prayers');
        Schema::dropIfExists('community_group_reading_logs');
        Schema::dropIfExists('community_group_reading_challenges');
        Schema::dropIfExists('community_group_invites');
        Schema::dropIfExists('community_group_memberships');
        Schema::dropIfExists('community_groups');
    }
};
