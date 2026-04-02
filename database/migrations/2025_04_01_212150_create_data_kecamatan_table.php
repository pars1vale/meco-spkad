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
            $table->id();
            $table->integer('id_camat');

            // Tahun (default 0 sesuai DDL)
            $table->integer('tahun')->default(0);

            // Relasi wilayah
            $table->integer('id_prop')->nullable();
            $table->integer('id_kab_kota')->nullable();

            // Kode & nama kecamatan
            $table->string('kode_camat', 20)->nullable();
            $table->string('camat_teks', 255)->nullable();

            // Kode DDN
            $table->string('kode_ddn', 20)->nullable();
            $table->string('kode_ddn_2', 20)->nullable();

            // Flag
            $table->tinyInteger('is_locked')->default(0);

            // Single-column indexes
            $table->index('tahun', 'idx_tahun');
            $table->index('id_prop', 'idx_id_prop');
            $table->index('id_kab_kota', 'idx_id_kab_kota');
            $table->index('kode_camat', 'idx_kode_camat');
            $table->index('kode_ddn', 'idx_kode_ddn');
            $table->index('kode_ddn_2', 'idx_kode_ddn_2');
            $table->index('is_locked', 'idx_is_locked');

            // Composite indexes
            $table->index(['id_prop', 'id_kab_kota'], 'idx_prop_kab');
            $table->index(['id_kab_kota', 'is_locked'], 'idx_kab_locked');
            $table->index(['tahun', 'id_prop', 'id_kab_kota'], 'idx_tahun_prop_kab');
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
