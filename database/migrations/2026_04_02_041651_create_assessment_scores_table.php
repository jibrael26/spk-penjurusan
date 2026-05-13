<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Pastikan kodenya dimulai seperti ini (return new class extends Migration)
return new class extends Migration
{
    public function up(): void
{
    Schema::create('assessment_scores', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        
        // Indikator Minat RIASEC [cite: 36, 132]
        $table->integer('realistic')->default(0);
        $table->integer('investigative')->default(0);
        $table->integer('artistic')->default(0);
        $table->integer('social')->default(0);
        $table->integer('enterprising')->default(0);
        $table->integer('conventional')->default(0);
        
        // Indikator Bakat DAT [cite: 163, 166]
        $table->integer('verbal_reasoning')->default(0);
        $table->integer('numerical_ability')->default(0);
        $table->integer('mechanical_reasoning')->default(0);
        
        $table->string('recommended_cluster')->nullable(); // Untuk hasil K-Means [cite: 75, 240]
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('assessment_scores');
    }
};
