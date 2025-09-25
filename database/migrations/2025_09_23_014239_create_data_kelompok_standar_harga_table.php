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
        Schema::create('data_kelompok_standar_harga', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kelompok_standar_harga', 30)->unique();
            $table->text('nama_kelompok_standar_harga');
            $table->timestamps();

            $table->index(['kode_kelompok_standar_harga']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_data_kelompok_standar_harga');
    }
};
