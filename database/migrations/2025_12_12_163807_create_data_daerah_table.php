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
            $table->integer('id_daerah')->primary();
            $table->string('kode_prop', 10)->nullable();
            $table->string('kode_kab', 10)->nullable();
            $table->string('nama_daerah', 255)->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('kode_ddn', 20)->nullable();
            $table->string('kode_ddn_2', 20)->nullable();
            $table->tinyInteger('is_pusat')->default(0);
            $table->tinyInteger('is_prop')->default(0);
            $table->integer('id_prop')->nullable();
            $table->string('jqm_code', 100)->nullable();
            $table->string('jqm_path', 255)->nullable();
            $table->string('sub_domain', 255)->nullable();
            $table->tinyInteger('is_deleted')->default(0);
            $table->tinyInteger('is_rekap')->default(0);
            $table->string('set_zona', 10)->nullable();
            $table->integer('set_waktu_zona')->nullable();
            $table->integer('set_gmt_zona')->nullable();
            $table->bigInteger('kode_satker')->nullable();
            $table->string('kode_prov_djpk', 10)->nullable();
            $table->string('kode_kab_djpk', 10)->nullable();
            $table->tinyInteger('will_migrated')->default(0);
            $table->tinyInteger('jns_pemda')->nullable();
            $table->tinyInteger('is_otsus_papua')->default(0);
            $table->tinyInteger('is_otsus_aceh')->default(0);
            $table->tinyInteger('is_dtpk')->default(0);
            $table->timestamps();
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
