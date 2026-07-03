<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prayer_partner_rooms', function (Blueprint $table) {
            $table->id();
            $table->string('token', 80)->unique();
            $table->string('source_type', 32)->index();
            $table->string('source_key')->nullable()->index();
            $table->string('language', 12)->nullable()->index();
            $table->string('share_id', 80)->nullable()->index();
            $table->string('title');
            $table->longText('summary');
            $table->string('scripture_reference')->nullable();
            $table->text('scripture_text')->nullable();
            $table->text('prayer_focus');
            $table->text('journal_prompt')->nullable();
            $table->string('source_url')->nullable();
            $table->unsignedInteger('visits_count')->default(0);
            $table->unsignedInteger('prayed_count')->default(0);
            $table->timestamp('last_visited_at')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prayer_partner_rooms');
    }
};
