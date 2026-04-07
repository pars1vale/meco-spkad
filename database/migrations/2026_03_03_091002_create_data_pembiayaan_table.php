<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_pembiayaan', function (Blueprint $table) {
            $table->id();
            $table->integer('created_user')->nullable();
            $table->string('createddate', 50)->nullable();
            $table->string('createdtime', 50)->nullable();
            $table->integer('id_pembiayaan')->nullable()->index();
            $table->text('keterangan')->nullable();
            $table->string('kode_akun', 50)->nullable()->index();
            $table->text('nama_akun')->nullable();
            $table->double('nilaimurni')->nullable();
            $table->integer('program_koordinator')->nullable();
            $table->text('rekening')->nullable();
            $table->integer('skpd_koordinator')->nullable();
            $table->double('total')->nullable();
            $table->double('pagu_fmis', 20, 2)->nullable();
            $table->integer('updated_user')->nullable();
            $table->string('updateddate', 50)->nullable();
            $table->string('updatedtime', 50)->nullable();
            $table->text('uraian')->nullable();
            $table->integer('urusan_koordinator')->nullable();
            $table->string('type', 50)->nullable()->index();
            $table->text('user1')->nullable();
            $table->text('user2')->nullable();
            $table->integer('id_skpd')->nullable()->index();
            $table->integer('id_akun')->nullable()->index();
            $table->integer('id_jadwal_murni')->nullable();
            $table->string('koefisien', 50)->nullable();
            $table->string('kua_murni', 50)->nullable();
            $table->string('kua_pak', 50)->nullable();
            $table->string('rkpd_murni', 50)->nullable();
            $table->string('rkpd_pak', 50)->nullable();
            $table->string('satuan', 50)->nullable();
            $table->string('volume', 50)->nullable();
            $table->tinyInteger('active')->nullable(false)->index();
            $table->dateTime('update_at');
            $table->year('tahun_anggaran')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_pembiayaan');
    }
};
