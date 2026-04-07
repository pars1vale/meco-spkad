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
        Schema::create('data_rka', function (Blueprint $table) {
            $table->id();
            // Audit pembuatan
            $table->integer('created_user')->nullable();
            $table->string('createddate', 10)->nullable();
            $table->string('createdtime', 10)->nullable();

            // Harga satuan
            $table->double('harga_satuan', 20, 2)->nullable();
            $table->double('harga_satuan_murni', 20, 2)->nullable();

            // Relasi utama
            $table->integer('id_daerah')->nullable();
            $table->integer('id_rinci_sub_bl')->nullable();
            $table->tinyInteger('id_standar_nfs')->nullable();
            $table->tinyInteger('is_locked')->nullable();

            // Jenis & keterangan BL
            $table->string('jenis_bl', 50)->nullable();
            $table->text('ket_bl_teks')->nullable();
            $table->text('substeks')->nullable();

            // Dana
            $table->integer('id_dana')->nullable();
            $table->text('nama_dana')->nullable();
            $table->tinyInteger('is_paket')->nullable();
            $table->string('kode_dana', 30)->nullable();

            // Subtitle & akun
            $table->text('subtitle_teks')->nullable();
            $table->string('kode_akun', 50)->nullable();

            // Koefisien
            $table->text('koefisien')->nullable();
            $table->text('koefisien_murni')->nullable();

            // Teks akun & komponen
            $table->text('lokus_akun_teks')->nullable();
            $table->text('nama_akun')->nullable();
            $table->text('nama_komponen')->nullable();
            $table->text('spek_komponen')->nullable();

            // Satuan & spesifikasi
            $table->string('satuan', 150)->nullable();
            $table->text('spek')->nullable();

            // Satuan 1-4
            $table->text('sat1')->nullable();
            $table->text('sat2')->nullable();
            $table->text('sat3')->nullable();
            $table->text('sat4')->nullable();

            // Volume 1-4 & total
            $table->text('volum1')->nullable();
            $table->text('volum2')->nullable();
            $table->text('volum3')->nullable();
            $table->text('volum4')->nullable();
            $table->text('volume')->nullable();
            $table->text('volume_murni')->nullable();

            // Teks sub BL
            $table->text('subs_bl_teks')->nullable();

            // Nilai keuangan
            $table->double('total_harga', 20, 2)->nullable();
            $table->double('rincian', 20, 2)->nullable();
            $table->double('rincian_murni', 20, 2)->nullable();
            $table->double('totalpajak', 20, 2)->nullable();
            $table->double('pajak', 20, 2)->nullable();
            $table->double('pajak_murni', 20, 2)->nullable();

            // Audit update
            $table->integer('updated_user')->nullable();
            $table->string('updateddate', 20)->nullable();
            $table->string('updatedtime', 20)->nullable();
            $table->text('user1')->nullable();
            $table->text('user2')->nullable();

            // Flag status
            $table->tinyInteger('active')->default(1);
            $table->tinyInteger('akun_locked')->nullable();
            $table->tinyInteger('ssh_locked')->nullable();

            // Timestamp update (nullable sesuai DDL)
            $table->dateTime('update_at')->nullable();

            // Tahun anggaran (NOT NULL, default 2021 sesuai DDL)
            $table->year('tahun_anggaran')->default(2021);

            // Relasi BL & sub BL
            $table->integer('idbl')->nullable();
            $table->integer('idsubbl')->nullable();

            // Kode BL & sub BL (NOT NULL sesuai DDL)
            $table->string('kode_bl', 50);
            $table->string('kode_sbl', 50);

            // Relasi penerima
            $table->integer('id_prop_penerima')->nullable();
            $table->integer('id_camat_penerima')->nullable();
            $table->integer('id_kokab_penerima')->nullable();
            $table->integer('id_lurah_penerima')->nullable();
            $table->integer('id_penerima')->nullable();

            // Komponen & keterangan
            $table->double('idkomponen', 20, 2)->nullable();
            $table->integer('idketerangan')->nullable();
            $table->integer('idsubtitle')->nullable();

            // Indexes (sesuai KEY di DDL)
            $table->index('tahun_anggaran', 'idx_tahun_anggaran');
            $table->index('id_rinci_sub_bl', 'idx_id_rinci_sub_bl');
            $table->index('kode_akun', 'idx_kode_akun');
            $table->index('kode_sbl', 'idx_kode_sbl');
            $table->index('id_dana', 'idx_id_dana');
            $table->index('active', 'idx_active');

            // Composite indexes tambahan
            $table->index(['kode_sbl', 'tahun_anggaran'], 'idx_kode_sbl_tahun');
            $table->index(['kode_bl', 'tahun_anggaran'], 'idx_kode_bl_tahun');
            $table->index(['id_daerah', 'tahun_anggaran'], 'idx_daerah_tahun');
            $table->index(['id_dana', 'tahun_anggaran'], 'idx_dana_tahun');
            $table->index(['tahun_anggaran', 'active'], 'idx_tahun_active');
            $table->index(['idsubbl', 'tahun_anggaran'], 'idx_idsubbl_tahun');
            $table->index(['kode_akun', 'tahun_anggaran'], 'idx_akun_tahun');
            $table->index(['id_daerah', 'kode_sbl', 'tahun_anggaran'], 'idx_daerah_sbl_tahun');
            $table->index(['kode_bl', 'kode_sbl', 'tahun_anggaran'], 'idx_bl_sbl_tahun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_rka');
    }
};
