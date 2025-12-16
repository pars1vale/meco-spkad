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
        Schema::create('data_kelurahan', function (Blueprint $table) {
            $table->integer('id_lurah')->primary();
            $table->integer('tahun')->nullable();
            $table->integer('id_prop')->nullable();
            $table->integer('id_kab_kota')->nullable();
            $table->integer('id_camat')->nullable();
            $table->string('kode_lurah', 20)->nullable();
            $table->string('lurah_teks', 255)->nullable();
            $table->string('kode_ddn', 20)->nullable();
            $table->string('kode_ddn_2', 20)->nullable();
            $table->tinyInteger('is_desa')->default(0);
            $table->tinyInteger('is_locked')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_kelurahan');
    }
};
