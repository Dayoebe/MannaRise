<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prayer_requests', function (Blueprint $table) {
            $table->string('moderation_status', 32)->default('approved')->index();
            $table->text('moderation_notes')->nullable();
            $table->foreignId('moderated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('moderated_at')->nullable()->index();
        });

        Schema::table('testimonies', function (Blueprint $table) {
            $table->string('moderation_status', 32)->default('pending')->index();
            $table->text('moderation_notes')->nullable();
            $table->foreignId('moderated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('moderated_at')->nullable()->index();
        });

        DB::table('testimonies')
            ->where('is_approved', true)
            ->update([
                'moderation_status' => 'approved',
                'moderated_at' => now(),
            ]);

        Schema::create('featured_content_slots', function (Blueprint $table) {
            $table->id();
            $table->string('slot_key')->unique();
            $table->foreignId('devotional_id')->nullable()->constrained()->nullOnDelete();
            $table->string('label');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamp('starts_at')->nullable()->index();
            $table->timestamp('ends_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('featured_content_slots');

        Schema::table('testimonies', function (Blueprint $table) {
            $table->dropConstrainedForeignId('moderated_by');
            $table->dropColumn(['moderation_status', 'moderation_notes', 'moderated_at']);
        });

        Schema::table('prayer_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('moderated_by');
            $table->dropColumn(['moderation_status', 'moderation_notes', 'moderated_at']);
        });
    }
};
