<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('growth_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('event_type', 64)->index();
            $table->date('event_date')->index();
            $table->string('country_code', 2)->nullable()->index();
            $table->string('language', 12)->nullable()->index();
            $table->date('daily_date')->nullable()->index();
            $table->string('source', 64)->nullable()->index();
            $table->string('medium', 64)->nullable();
            $table->string('campaign', 128)->nullable();
            $table->string('share_channel', 64)->nullable()->index();
            $table->string('share_id', 80)->nullable()->index();
            $table->string('path')->nullable()->index();
            $table->text('url')->nullable();
            $table->text('referrer')->nullable();
            $table->string('ip_hash', 64)->nullable()->index();
            $table->string('user_agent', 255)->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['event_type', 'event_date']);
            $table->index(['event_type', 'language']);
            $table->index(['event_type', 'country_code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('growth_events');
    }
};
