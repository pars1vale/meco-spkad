<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_kelompok_satuan_harga', function (Blueprint $table) {
            $table->id();
            $table->integer('id_kategori')->unique();
            $table->string('kode_kategori', 50)->unique();
            $table->text('uraian_kategori');
            $table->string('tipe_kelompok', 20)->comment('SSH, ASB, SBU, HSPK');
            $table->tinyInteger('active')->default(1);
            $table->integer('tahun_anggaran');
            $table->timestamps();

            $table->index(['kode_kategori']);
            $table->index(['tipe_kelompok']);
            $table->index(['tahun_anggaran']);
            $table->index(['active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_kelompok_satuan_harga');
    }
};
