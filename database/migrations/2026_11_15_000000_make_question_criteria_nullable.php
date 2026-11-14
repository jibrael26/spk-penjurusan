<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['criteria_id']);
            $table->foreignId('criteria_id')->nullable()->change();
            $table->foreign('criteria_id')->references('id')->on('criteria')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['criteria_id']);
            $table->foreignId('criteria_id')->nullable(false)->change();
            $table->foreign('criteria_id')->references('id')->on('criteria')->cascadeOnDelete();
        });
    }
};
