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
            $table->id();
            $table->integer('id_lurah');

            // Tahun (nullable sesuai DDL)
            $table->integer('tahun')->nullable();

            // Relasi wilayah
            $table->integer('id_prop')->nullable();
            $table->integer('id_kab_kota')->nullable();
            $table->integer('id_camat')->nullable();

            // Kode & nama kelurahan
            $table->string('kode_lurah', 20)->nullable();
            $table->string('lurah_teks', 255)->nullable();

            // Kode DDN
            $table->string('kode_ddn', 20)->nullable();
            $table->string('kode_ddn_2', 20)->nullable();

            // Flag
            $table->tinyInteger('is_desa')->default(0);
            $table->tinyInteger('is_locked')->default(0);

            // Single-column indexes
            $table->index('tahun', 'idx_tahun');
            $table->index('id_prop', 'idx_id_prop');
            $table->index('id_kab_kota', 'idx_id_kab_kota');
            $table->index('id_camat', 'idx_id_camat');
            $table->index('kode_lurah', 'idx_kode_lurah');
            $table->index('kode_ddn', 'idx_kode_ddn');
            $table->index('kode_ddn_2', 'idx_kode_ddn_2');
            $table->index('is_desa', 'idx_is_desa');
            $table->index('is_locked', 'idx_is_locked');

            // Composite indexes
            $table->index(['id_prop', 'id_kab_kota'], 'idx_prop_kab');
            $table->index(['id_kab_kota', 'id_camat'], 'idx_kab_camat');
            $table->index(['id_camat', 'is_locked'], 'idx_camat_locked');
            $table->index(['tahun', 'id_prop', 'id_kab_kota'], 'idx_tahun_prop_kab');
            $table->index(['tahun', 'id_prop', 'id_kab_kota', 'id_camat'], 'idx_tahun_prop_kab_camat');
            $table->index(['is_desa', 'is_locked'], 'idx_desa_locked');
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
