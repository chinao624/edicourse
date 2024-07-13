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
        Schema::table('review_articles',function(Blueprint $table){

            $table->renameColumn('result','status');

            $table->dropForeign(['reviewer_id']);
            $table->foreign('reviewer_id')->references('id')->on('reviewers')->onDelete('cascade');

            $table->dropForeign(['article_id']);
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('review_articles', function (Blueprint $table) {
            // 変更を元に戻す
            $table->renameColumn('status', 'result');

            $table->dropForeign(['reviewer_id']);
            $table->foreign('reviewer_id')
                  ->references('id')
                  ->on('reviewers');

            $table->dropForeign(['article_id']);
            $table->foreign('article_id')
                  ->references('id')
                  ->on('articles');
        });
    }
};
