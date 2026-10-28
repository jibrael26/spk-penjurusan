<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->unsignedSmallInteger('bobot')->nullable()->change();
            $table->boolean('sembunyikan_teks')->default(false)->after('bobot');
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('sembunyikan_teks');
            $table->unsignedTinyInteger('bobot')->nullable()->change();
        });
    }
};
