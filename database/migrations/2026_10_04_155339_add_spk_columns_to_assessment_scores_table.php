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
        Schema::table('assessment_scores', function (Blueprint $table) {
            // Menambahkan kolom baru setelah kolom 'recommended_cluster'
            $table->unsignedBigInteger('criteria_id')->nullable()->after('recommended_cluster');
            $table->decimal('score', 8, 2)->nullable()->after('criteria_id');
            $table->json('details')->nullable()->after('score'); // Jika MySQL versi lama tidak support 'json', ganti dengan 'text'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessment_scores', function (Blueprint $table) {
            // Menghapus kolom jika di-rollback
            $table->dropColumn(['criteria_id', 'score', 'details']);
        });
    }
};