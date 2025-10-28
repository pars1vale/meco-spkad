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
        Schema::create('jadwal_rkpd', function (Blueprint $table) {
            $table->id('id_jadwal');
            $table->char('id_unik', 36);
            $table->integer('tahun');
            $table->integer('id_daerah');
            $table->unsignedBigInteger('id_sub_tahap');
            $table->dateTime('waktu_mulai')->nullable();
            $table->dateTime('waktu_selesai')->nullable();
            $table->tinyInteger('is_perubahan')->default(0);
            $table->unsignedBigInteger('id_jadwal_murni')->nullable();
            $table->tinyInteger('is_pembahasan')->default(0);
            $table->unsignedBigInteger('id_jadwal_pembahasan')->nullable();
            $table->tinyInteger('is_locked')->default(0);
            $table->tinyInteger('is_public')->default(0);
            $table->tinyInteger('is_rinci_bl')->default(0);
            $table->integer('id_sub_rkpd')->nullable();
            $table->string('no_registrasi', 50)->nullable();
            $table->string('no_perda', 100)->nullable();
            $table->date('tgl_perda')->nullable();
            $table->string('no_perkada', 100)->nullable();
            $table->dateTime('tgl_perkada')->nullable();
            $table->dateTime('tgl_rka')->nullable();
            $table->tinyInteger('tandai_jadwal')->default(0);
            $table->integer('id_jadwal_rpjmd')->nullable();
            $table->integer('rkpd_murni')->nullable();
            $table->integer('rkpd_pak')->nullable();
            $table->integer('created_user')->nullable();
            $table->integer('updated_user')->nullable();
            $table->timestamps();

            // FK ke sub_tahap
            $table->foreign('id_sub_tahap')
                ->references('id_sub_tahap')
                ->on('sub_tahap_penjadwalan')
                ->onDelete('restrict');

            // Self reference ke jadwal RKPD
            $table->foreign('id_jadwal_murni')
                ->references('id_jadwal')
                ->on('jadwal_rkpd')
                ->onDelete('set null');
        });

        // Index tambahan
        Schema::table('jadwal_rkpd', function (Blueprint $table) {
            $table->index('tahun');
            $table->index('id_daerah');
            $table->index('id_sub_tahap');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_rkpd');
    }
};
