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
        Schema::create('data_sub_keg_indikator', function (Blueprint $table) {
            $table->id();
            $table->text('outputteks');
            $table->string('targetoutput', 50);
            $table->text('satuanoutput');
            $table->integer('idoutputbl');
            $table->text('targetoutputteks');
            $table->string('kode_sbl', 50);
            $table->integer('idsubbl')->nullable();
            $table->string('bobot_kinerja', 50)->default('1')->nullable();
            $table->tinyInteger('active')->default(1)->nullable();
            $table->dateTime('update_at');
            $table->year('tahun_anggaran');

            // Indexes
            $table->index('kode_sbl');
            $table->index('tahun_anggaran');
            $table->index('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_sub_keg_indikator');
    }
};
