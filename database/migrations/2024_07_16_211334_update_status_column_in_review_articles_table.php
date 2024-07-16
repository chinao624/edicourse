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
            $table->enum('status', ['pending', 'completed', 'expired', 'withdrawn', 'thanked'])->default('pending')->change();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('review_articles', function (Blueprint $table) {
            $table->enum('status', ['pending', 'completed', 'expired', 'withdrawn'])->default('pending')->change();
        });
    }
};
