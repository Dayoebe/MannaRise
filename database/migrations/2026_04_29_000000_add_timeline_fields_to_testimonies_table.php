<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('testimonies', function (Blueprint $table) {
            $table->string('category')->default('breakthrough')->after('body')->index();
            $table->longText('before_body')->nullable()->after('category');
            $table->longText('after_body')->nullable()->after('before_body');
            $table->date('answered_on')->nullable()->after('after_body')->index();
        });
    }

    public function down(): void
    {
        Schema::table('testimonies', function (Blueprint $table) {
            $table->dropIndex(['category']);
            $table->dropIndex(['answered_on']);
            $table->dropColumn(['category', 'before_body', 'after_body', 'answered_on']);
        });
    }
};
