<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up()
{
    Schema::table('questions', function (Blueprint $table) {
        // Menyimpan kode dimensi, misal: 'R', 'I', 'Gf', 'Gc'
        $table->string('kode_indikator')->nullable()->after('criteria_id'); 
        
        // Menyimpan kunci jawaban tes bakat, misal: '1' atau 'A'
        $table->string('kunci_jawaban')->nullable()->after('opsi_jawaban'); 
    });
}

public function down()
{
    Schema::table('questions', function (Blueprint $table) {
        $table->dropColumn(['kode_indikator', 'kunci_jawaban']);
    });
}
};
