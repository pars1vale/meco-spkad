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
        Schema::create('sub_tahap_penjadwalan', function (Blueprint $table) {
            $table->id('id_sub_tahap');
            $table->unsignedBigInteger('id_tahap');
            $table->string('nama_sub_tahap', 255);
            $table->timestamps();

            $table->foreign('id_tahap')
                ->references('id_tahap')
                ->on('tahap_penjadwalan')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_tahap_penjadwalan');
    }
};
