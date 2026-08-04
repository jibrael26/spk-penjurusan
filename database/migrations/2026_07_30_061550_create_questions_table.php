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
            $table->text('question_text');
            // Menambahkan kolom kategori untuk mengelompokkan nilai pertanyaan ke jurusan tertentu
            $table->string('kategori')->nullable(); // contoh isi: 'IPA', 'IPS', 'Logika', 'Hafalan'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};