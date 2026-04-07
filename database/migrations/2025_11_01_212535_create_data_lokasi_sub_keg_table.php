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
        Schema::create('data_lokasi_sub_keg', function (Blueprint $table) {
            $table->id();

            // Teks wilayah (text = nullable by nature, sesuai DDL DEFAULT NULL)
            $table->text('camatteks')->nullable();
            $table->text('daerahteks')->nullable();

            // Relasi wilayah
            $table->integer('idcamat')->nullable();
            $table->double('iddetillokasi')->nullable();
            $table->integer('idkabkota')->nullable();
            $table->integer('idlurah')->nullable();

            // Teks kelurahan
            $table->text('lurahteks')->nullable();

            // Kode & relasi sub bl
            $table->string('kode_sbl', 50)->nullable();
            $table->integer('idsubbl')->nullable();

            // Flag & timestamps
            $table->tinyInteger('active')->nullable();
            $table->dateTime('update_at');          // NOT NULL sesuai DDL
            $table->year('tahun_anggaran');         // NOT NULL sesuai DDL

            // Indexes (sesuai KEY di DDL)
            $table->index('kode_sbl', 'idx_kode_sbl');
            $table->index('idsubbl', 'idx_idsubbl');
            $table->index('active', 'idx_active');
            $table->index('tahun_anggaran', 'idx_tahun_anggaran');

            // Composite indexes tambahan (logis untuk query umum)
            $table->index(['idkabkota', 'idcamat'], 'idx_kab_camat');
            $table->index(['idcamat', 'idlurah'], 'idx_camat_lurah');
            $table->index(['tahun_anggaran', 'active'], 'idx_tahun_active');
            $table->index(['tahun_anggaran', 'idsubbl'], 'idx_tahun_idsubbl');
            $table->index(['kode_sbl', 'tahun_anggaran'], 'idx_kode_sbl_tahun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_lokasi_sub_keg');
    }
};
