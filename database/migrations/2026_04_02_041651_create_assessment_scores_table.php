<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('assessment_scores', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        // Skor RIASEC (Minat) [cite: 132]
        $table->integer('realistic')->default(0);
        $table->integer('investigative')->default(0);
        $table->integer('artistic')->default(0);
        $table->integer('social')->default(0);
        $table->integer('enterprising')->default(0);
        $table->integer('conventional')->default(0);
        // Skor DAT (Bakat) [cite: 166]
        $table->integer('verbal_reasoning')->default(0);
        $table->integer('numerical_ability')->default(0);
        $table->integer('mechanical_reasoning')->default(0);

        $table->string('recommended_cluster')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_scores');
    }
};
