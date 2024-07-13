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
        Schema::create('review_articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reviewer_id')->constrained('reviewers');
    $table->foreignId('article_id')->constrained('articles');
    $table->timestamp('limit_time');
    $table->enum('result', ['pending', 'completed', 'expired'])->default('pending');
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_articles');
    }
};
