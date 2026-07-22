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
        Schema::create('data_master_indikator_subgiat', function (Blueprint $table) {
            $table->id();
            // Relasi SKPD & sub kegiatan (NOT NULL sesuai DDL)
            $table->integer('id_skpd');
            $table->integer('id_sub_keg');

            // Indikator & satuan (NOT NULL sesuai DDL)
            $table->string('indikator', 512);
            $table->text('satuan');

            // Flag & timestamps (NOT NULL sesuai DDL)
            $table->tinyInteger('active');
            $table->year('tahun_anggaran');
            $table->dateTime('updated_at');

            // Indexes (sesuai KEY di DDL)
            $table->index('id_skpd', 'idx_id_skpd');
            $table->index('id_sub_keg', 'idx_id_sub_keg');
            $table->index('indikator', 'idx_indikator');
            $table->index('tahun_anggaran', 'idx_tahun_anggaran');
            $table->index('active', 'idx_active');

            // Composite indexes tambahan (logis untuk query umum)
            $table->index(['id_skpd', 'id_sub_keg'], 'idx_skpd_subkeg');
            $table->index(['id_skpd', 'tahun_anggaran'], 'idx_skpd_tahun');
            $table->index(['id_sub_keg', 'tahun_anggaran'], 'idx_subkeg_tahun');
            $table->index(['tahun_anggaran', 'active'], 'idx_tahun_active');
            $table->index(['id_skpd', 'id_sub_keg', 'tahun_anggaran'], 'idx_skpd_subkeg_tahun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_master_indikator_subgiat');
    }
};
