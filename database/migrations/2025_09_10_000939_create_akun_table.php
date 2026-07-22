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
        Schema::create('akun', function (Blueprint $table) {
            $table->id();
            $table->string('belanja', 10)->nullable();
            $table->integer('id_akun')->nullable();
            $table->tinyInteger('is_bagi_hasil')->nullable();
            $table->tinyInteger('is_bankeu_khusus')->nullable();
            $table->tinyInteger('is_bankeu_umum')->nullable();
            $table->tinyInteger('is_barjas')->nullable();
            $table->tinyInteger('is_bl')->nullable();
            $table->tinyInteger('is_bos')->nullable();
            $table->tinyInteger('is_btt')->nullable();
            $table->tinyInteger('is_bunga')->nullable();
            $table->tinyInteger('is_gaji_asn')->nullable();
            $table->tinyInteger('is_hibah_brg')->nullable();
            $table->tinyInteger('is_hibah_uang')->nullable();
            $table->tinyInteger('is_locked')->nullable();
            $table->tinyInteger('is_modal_tanah')->nullable();
            $table->tinyInteger('is_pembiayaan')->nullable();
            $table->tinyInteger('is_pendapatan')->nullable();
            $table->tinyInteger('is_sosial_brg')->nullable();
            $table->tinyInteger('is_sosial_uang')->nullable();
            $table->tinyInteger('is_subsidi')->nullable();
            $table->string('kode_akun', 50)->nullable();
            $table->text('nama_akun')->nullable();
            $table->tinyInteger('set_input')->nullable();
            $table->tinyInteger('set_lokus')->nullable();
            $table->string('ket_akun', 255)->nullable();
            $table->string('kode_akun_lama', 50)->nullable();
            $table->string('kode_akun_revisi', 50)->nullable();
            $table->tinyInteger('kunci_tahun')->nullable();
            $table->tinyInteger('level')->nullable();
            $table->text('mulai_tahun')->nullable();
            $table->string('pembiayaan', 50)->nullable();
            $table->string('pendapatan', 50)->nullable();
            $table->tinyInteger('set_kab_kota')->nullable();
            $table->tinyInteger('set_prov')->nullable();
            $table->string('status', 20)->nullable();
            $table->tinyInteger('active')->default(1)->comment('0=hapus, 1=aktif');
            $table->datetime('update_at')->nullable();
            $table->year('tahun_anggaran')->default(2021);
            $table->timestamps();

            // Indexes
            $table->index('tahun_anggaran');
            $table->index('kode_akun');
            $table->index('id_akun');
            $table->index('active');
            $table->index('is_bl');
            $table->index('is_pendapatan');
            $table->index('is_pembiayaan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akun');
    }
};
