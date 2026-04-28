<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prayer_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description');
            $table->string('scripture_reference')->nullable();
            $table->string('accent')->default('rose');
            $table->unsignedSmallInteger('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });

        Schema::table('prayer_requests', function (Blueprint $table) {
            $table->foreignId('prayer_room_id')
                ->nullable()
                ->after('user_id')
                ->constrained('prayer_rooms')
                ->nullOnDelete();
        });

        Schema::create('prayer_room_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('prayer_room_id')->constrained('prayer_rooms')->cascadeOnDelete();
            $table->timestamp('joined_at')->nullable();
            $table->date('last_prayed_on')->nullable()->index();
            $table->unsignedInteger('current_streak')->default(0);
            $table->unsignedInteger('longest_streak')->default(0);
            $table->unsignedInteger('total_prayers')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'prayer_room_id']);
        });

        Schema::create('prayer_room_prayers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('prayer_room_id')->constrained('prayer_rooms')->cascadeOnDelete();
            $table->foreignId('prayer_request_id')->nullable()->constrained('prayer_requests')->nullOnDelete();
            $table->date('prayed_on')->index();
            $table->timestamps();
        });

        Schema::create('prayer_request_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prayer_request_id')->constrained('prayer_requests')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->longText('body');
            $table->boolean('is_answered_update')->default(false)->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prayer_request_updates');
        Schema::dropIfExists('prayer_room_prayers');
        Schema::dropIfExists('prayer_room_memberships');

        Schema::table('prayer_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('prayer_room_id');
        });

        Schema::dropIfExists('prayer_rooms');
    }
};
