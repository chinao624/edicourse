<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('review_articles', function (Blueprint $table) {
            $table->timestamp('limit_time')->default(now()->addHours(48))->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('review_articles', function (Blueprint $table) {
            $table->timestamp('limit_time')->default(null)->change();
        });
    }
};
