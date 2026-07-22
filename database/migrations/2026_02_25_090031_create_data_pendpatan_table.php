<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_pendapatan', function (Blueprint $table) {
            $table->integer('id')->primary();

            // Audit
            $table->integer('created_user')->nullable();
            $table->string('createddate', 50)->nullable();
            $table->string('createdtime', 50)->nullable();

            $table->integer('updated_user')->nullable();
            $table->string('updateddate', 50)->nullable();
            $table->string('updatedtime', 50)->nullable();

            // Relasi & Identitas
            $table->integer('id_pendapatan')->nullable();
            $table->integer('id_skpd')->nullable();
            $table->integer('id_akun')->nullable();
            $table->integer('id_jadwal_murni')->nullable();
            $table->integer('program_koordinator')->nullable();
            $table->integer('skpd_koordinator')->nullable();
            $table->integer('urusan_koordinator')->nullable();

            // Data Akun
            $table->string('kode_akun', 50)->nullable();
            $table->text('nama_akun')->nullable();
            $table->text('rekening')->nullable();

            // Nilai
            $table->double('nilaimurni')->nullable();
            $table->double('total')->nullable();
            $table->double('pagu_fmis', 20, 2)->nullable();

            // Perencanaan
            $table->string('koefisien', 50)->nullable();
            $table->string('kua_murni', 50)->nullable();
            $table->string('kua_pak', 50)->nullable();
            $table->string('rkpd_murni', 50)->nullable();
            $table->string('rkpd_pak', 50)->nullable();
            $table->string('satuan', 50)->nullable();
            $table->string('volume', 50)->nullable();

            // Keterangan
            $table->text('keterangan')->nullable();
            $table->text('uraian')->nullable();
            $table->text('user1')->nullable();
            $table->text('user2')->nullable();

            // Status & Waktu
            $table->tinyInteger('active');
            $table->dateTime('update_at');
            $table->year('tahun_anggaran');

            // indexing
            $table->index('id_pendapatan');
            $table->index('id_skpd');
            $table->index('id_akun');
            $table->index('id_jadwal_murni');
            $table->index('program_koordinator');
            $table->index('skpd_koordinator');
            $table->index('urusan_koordinator');
            $table->index('kode_akun');
            $table->index('tahun_anggaran');
            $table->index('active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_pendapatan');
    }
};
