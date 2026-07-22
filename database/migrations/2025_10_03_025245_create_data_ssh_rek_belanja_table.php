<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_ssh_rek_belanja', function (Blueprint $table) {
            $table->id();
            $table->integer('id_akun');
            $table->string('kode_akun', 50);
            $table->text('nama_akun');
            $table->integer('id_standar_harga');
            $table->tinyInteger('active')->default(1);
            $table->timestamp('update_at')->nullable();
            $table->year('tahun_anggaran')->default(2021);

            // Indexes
            $table->index('id_standar_harga', 'id_standar_harga');
            $table->index('kode_akun', 'kode_akun');
            $table->index('tahun_anggaran', 'tahun_anggaran');
            $table->index('active', 'active');

            // Foreign key
            $table->foreign('id_standar_harga', 'data_ssh_rek_belanja_ibfk_1')
                ->references('id_standar_harga')
                ->on('data_ssh')
                ->onDelete('cascade');
        });

        // Set engine and charset
        DB::statement('ALTER TABLE `data_ssh_rek_belanja` ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC');
    }

    public function down(): void
    {
        Schema::dropIfExists('data_ssh_rek_belanja');
    }
};
