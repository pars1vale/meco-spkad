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
        Schema::create('data_kecamatan', function (Blueprint $table) {
            $table->integer('id_camat')->primary();
            $table->integer('tahun')->default(0);
            $table->integer('id_prop')->nullable();
            $table->integer('id_kab_kota')->nullable();
            $table->string('kode_camat', 20)->nullable();
            $table->string('camat_teks', 255)->nullable();
            $table->string('kode_ddn', 20)->nullable();
            $table->string('kode_ddn_2', 20)->nullable();
            $table->tinyInteger('is_locked')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_kecamatan');
    }
};
