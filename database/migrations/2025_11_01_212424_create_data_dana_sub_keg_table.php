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
        Schema::create('data_dana_sub_keg', function (Blueprint $table) {
            $table->id();

            // Nama & kode dana
            $table->text('namadana')->nullable();
            $table->string('kodedana', 50)->nullable();

            // Relasi dana
            $table->integer('iddana')->nullable();
            $table->integer('iddanasubbl')->nullable();

            // Nilai pagu dana (double precision 20,2)
            $table->double('pagudana', 20, 2)->nullable();

            // Kode & relasi sub bl
            $table->string('kode_sbl', 50)->nullable();
            $table->integer('idsubbl')->nullable();

            // Flag
            $table->tinyInteger('is_locked')->nullable();
            $table->tinyInteger('active')->default(1);

            // Timestamps (NOT NULL sesuai DDL)
            $table->dateTime('update_at');
            $table->year('tahun_anggaran');

            // Indexes (sesuai KEY di DDL)
            $table->index('tahun_anggaran', 'idx_tahun_anggaran');
            $table->index('kodedana', 'idx_kodedana');
            $table->index('kode_sbl', 'idx_kode_sbl');
            $table->index('iddana', 'idx_iddana');
            $table->index('active', 'idx_active');

            // Composite indexes tambahan
            $table->index(['kode_sbl', 'tahun_anggaran'], 'idx_kode_sbl_tahun');
            $table->index(['iddana', 'tahun_anggaran'], 'idx_iddana_tahun');
            $table->index(['idsubbl', 'tahun_anggaran'], 'idx_idsubbl_tahun');
            $table->index(['tahun_anggaran', 'active'], 'idx_tahun_active');
            $table->index(['kode_sbl', 'iddana', 'tahun_anggaran'], 'idx_kode_sbl_iddana_tahun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_dana_sub_keg');
    }
};
