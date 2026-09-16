<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('criteria_id')->constrained('criteria')->onDelete('cascade');
            $table->text('teks_pertanyaan');
            $table->unsignedTinyInteger('fase')->default(1);
            $table->string('tipe_opsi')->default('text');
            $table->json('opsi_jawaban')->nullable();
            $table->unsignedTinyInteger('bobot')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};