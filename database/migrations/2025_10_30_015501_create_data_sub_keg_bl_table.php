<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('data_sub_keg_bl', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('id_sub_skpd');
            $table->integer('id_lokasi')->nullable();
            $table->integer('id_label_kokab')->nullable();
            $table->text('nama_dana')->nullable();
            $table->string('no_sub_giat', 20);
            $table->string('kode_giat', 50);
            $table->integer('id_program');
            $table->text('nama_lokasi')->nullable();
            $table->integer('waktu_akhir');
            $table->double('pagu_n_lalu', 20, 2)->nullable();
            $table->integer('id_urusan');
            $table->text('id_unik_sub_bl');
            $table->integer('id_sub_giat');
            $table->text('label_prov')->nullable();
            $table->string('kode_program', 50);
            $table->string('kode_sub_giat', 50);
            $table->string('no_program', 20);
            $table->string('kode_urusan', 20);
            $table->string('kode_bidang_urusan', 20);
            $table->text('nama_program');
            $table->text('target_4')->nullable();
            $table->text('target_5')->nullable();
            $table->integer('id_bidang_urusan')->nullable();
            $table->text('nama_bidang_urusan')->nullable();
            $table->text('target_3')->nullable();
            $table->string('no_giat', 50);
            $table->integer('id_label_prov');
            $table->integer('waktu_awal');
            $table->double('pagumurni', 20, 2)->nullable();
            $table->double('pagu', 20, 2);
            $table->double('pagu_simda', 20, 2)->nullable();
            $table->text('output_sub_giat')->nullable();
            $table->text('sasaran')->nullable();
            $table->string('indikator', 512)->nullable();
            $table->integer('id_dana')->nullable();
            $table->text('nama_sub_giat');
            $table->double('pagu_n_depan', 20, 2);
            $table->text('satuan')->nullable();
            $table->integer('id_rpjmd');
            $table->integer('id_giat');
            $table->integer('id_label_pusat');
            $table->text('nama_giat');
            $table->string('kode_skpd', 50);
            $table->text('nama_skpd');
            $table->string('kode_sub_skpd', 50);
            $table->integer('id_skpd');
            $table->integer('id_sub_bl')->nullable();
            $table->text('nama_sub_skpd');
            $table->text('target_1')->nullable();
            $table->text('nama_urusan');
            $table->text('target_2')->nullable();
            $table->text('label_kokab')->nullable();
            $table->text('label_pusat')->nullable();
            $table->double('pagu_keg', 20, 2);
            $table->double('pagu_fmis', 20, 2)->nullable();
            $table->integer('id_bl')->nullable();
            $table->string('kode_bl', 50);
            $table->string('kode_sbl', 50);
            $table->tinyInteger('active')->default(1);
            $table->dateTime('update_at');
            $table->year('tahun_anggaran')->default(2021);
        });
    }

    /**
     * Reverse migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_sub_keg_bl');
    }
};
