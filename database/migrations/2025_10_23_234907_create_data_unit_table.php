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
        Schema::create('data_unit', function (Blueprint $table) {
            $table->id();
            $table->integer('id_setup_unit')->nullable();
            $table->integer('id_unit')->nullable();
            $table->tinyInteger('is_skpd')->nullable();
            $table->string('kode_skpd', 50)->nullable();
            $table->integer('kunci_skpd')->nullable();
            $table->text('nama_skpd')->nullable();
            $table->string('posisi', 30)->nullable();
            $table->string('status', 30)->nullable();
            $table->integer('id_skpd')->nullable();
            $table->smallInteger('bidur_1')->nullable();
            $table->smallInteger('bidur_2')->nullable();
            $table->smallInteger('bidur_3')->nullable();
            $table->integer('idinduk')->nullable();
            $table->tinyInteger('ispendapatan')->nullable();
            $table->tinyInteger('isskpd')->nullable();
            $table->string('kode_skpd_1', 10)->nullable();
            $table->string('kode_skpd_2', 10)->nullable();
            $table->string('kodeunit', 30)->nullable();
            $table->integer('komisi')->nullable();
            $table->text('namabendahara')->nullable();
            $table->text('namakepala')->nullable();
            $table->text('namaunit')->nullable();
            $table->string('nipbendahara', 30)->nullable();
            $table->string('nipkepala', 30)->nullable();
            $table->string('pangkatkepala', 50)->nullable();
            $table->integer('setupunit')->nullable();
            $table->string('statuskepala', 20)->nullable();
            $table->string('mapping', 10)->nullable();
            $table->integer('id_kecamatan')->nullable();
            $table->integer('id_strategi')->nullable();
            $table->tinyInteger('is_dpa_khusus')->nullable();
            $table->tinyInteger('is_ppkd')->nullable();
            $table->tinyInteger('set_input')->nullable();
            $table->year('tahun_anggaran')->default(2025);
            $table->tinyInteger('active')->default(1);

            // Tambahan timestamp standar Laravel
            $table->timestamps(); // otomatis membuat created_at dan updated_at

            // Indexes
            $table->index('tahun_anggaran');
            $table->index('id_skpd');
            $table->index('is_skpd');
            $table->index('id_unit');
            $table->index('idinduk');
            $table->index('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_unit');
    }
};
