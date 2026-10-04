<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessment_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Kolom skor untuk 5 Jurusan SMK (K01 - K05)
            $table->integer('k01')->default(1); // ATPH
            $table->integer('k02')->default(2); // APHP
            $table->integer('k03')->default(3); // AKL
            $table->integer('k04')->default(4); // TKRO
            $table->integer('k05')->default(5); // TKJ
            
            // Hasil K-Means
            $table->string('recommended_cluster')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessment_scores');
    }
};