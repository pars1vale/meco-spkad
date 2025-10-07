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
       Schema::create('jadwal_penganggaran', function (Blueprint $table) {
            $table->unsignedBigInteger('id_jadwal'); // FK ke jadwal_rkpd
            $table->string('kua_murni', 255)->nullable();
            $table->string('kua_pak', 255)->nullable();
            $table->string('rollback_jadwal', 255)->nullable();
            $table->text('rollback_teks')->nullable();
            $table->tinyInteger('geser_khusus')->default(0);
            $table->timestamps();

            $table->primary('id_jadwal');
            $table->foreign('id_jadwal')
                ->references('id_jadwal')
                ->on('jadwal_rkpd')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_penganggaran');
    }
};
