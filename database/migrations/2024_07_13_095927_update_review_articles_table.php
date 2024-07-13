<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('review_articles', function (Blueprint $table) {
            $table->timestamp('withdrawn_at')->nullable()->after('limit_time');
        });

        DB::statement("ALTER TABLE review_articles MODIFY COLUMN status ENUM('pending', 'completed', 'expired', 'withdrawn') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('review_articles', function (Blueprint $table) {
            $table->dropColumn('withdrawn_at');
        });

        DB::statement("ALTER TABLE review_articles MODIFY COLUMN status ENUM('pending', 'completed', 'expired') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }
};
