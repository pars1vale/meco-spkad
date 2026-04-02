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
        Schema::create('data_daerah', function (Blueprint $table) {
            $table->id();
            $table->integer('id_daerah');

            
            // Kode wilayah
            $table->string('kode_prop', 10)->nullable();
            $table->string('kode_kab', 10)->nullable();
            $table->string('nama_daerah', 255)->nullable();
            $table->string('logo', 255)->nullable();

            // Kode DDN
            $table->string('kode_ddn', 20)->nullable();
            $table->string('kode_ddn_2', 20)->nullable();

            // Flag / status
            $table->tinyInteger('is_pusat')->default(0);
            $table->tinyInteger('is_prop')->default(0);
            $table->integer('id_prop')->nullable();

            // JQM
            $table->string('jqm_code', 100)->nullable();
            $table->string('jqm_path', 255)->nullable();

            // Sub domain & soft delete flag
            $table->string('sub_domain', 255)->nullable();
            $table->tinyInteger('is_deleted')->default(0);
            $table->tinyInteger('is_rekap')->default(0);

            // Zona waktu
            $table->string('set_zona', 10)->nullable();
            $table->integer('set_waktu_zona')->nullable();
            $table->integer('set_gmt_zona')->nullable();

            // Kode satker & DJPK
            $table->unsignedBigInteger('kode_satker')->nullable();
            $table->string('kode_prov_djpk', 10)->nullable();
            $table->string('kode_kab_djpk', 10)->nullable();

            // Migration & jenis pemda
            $table->tinyInteger('will_migrated')->default(0);
            $table->tinyInteger('jns_pemda')->nullable();

            // Otonomi khusus & DTPK
            $table->tinyInteger('is_otsus_papua')->default(0);
            $table->tinyInteger('is_otsus_aceh')->default(0);
            $table->tinyInteger('is_dtpk')->default(0);

            // Indexes
            $table->index('kode_prop', 'idx_kode_prop');
            $table->index('kode_kab', 'idx_kode_kab');
            $table->index('id_prop', 'idx_id_prop');
            $table->index('kode_ddn', 'idx_kode_ddn');
            $table->index('kode_ddn_2', 'idx_kode_ddn_2');
            $table->index('is_pusat', 'idx_is_pusat');
            $table->index('is_prop', 'idx_is_prop');
            $table->index('is_deleted', 'idx_is_deleted');
            $table->index('is_rekap', 'idx_is_rekap');
            $table->index('kode_satker', 'idx_kode_satker');
            $table->index('kode_prov_djpk', 'idx_kode_prov_djpk');
            $table->index('kode_kab_djpk', 'idx_kode_kab_djpk');
            $table->index('will_migrated', 'idx_will_migrated');
            $table->index('jns_pemda', 'idx_jns_pemda');
            $table->index('is_otsus_papua', 'idx_is_otsus_papua');
            $table->index('is_otsus_aceh', 'idx_is_otsus_aceh');
            $table->index('is_dtpk', 'idx_is_dtpk');
            $table->index('sub_domain', 'idx_sub_domain');

            // Composite index yang umum digunakan bersama
            $table->index(['kode_prop', 'kode_kab'], 'idx_prop_kab');
            $table->index(['is_deleted', 'is_prop'], 'idx_deleted_prop');
            $table->index(['is_deleted', 'is_pusat'], 'idx_deleted_pusat');
            $table->index(['kode_prov_djpk', 'kode_kab_djpk'], 'idx_djpk_prop_kab');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_daerah');
    }
};
