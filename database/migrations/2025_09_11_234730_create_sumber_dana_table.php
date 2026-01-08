<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('sumber_dana', function (Blueprint $table) {
            $table->increments('id');

            $table->dateTime('created_at')->nullable();
            $table->integer('created_user')->nullable();

            $table->integer('id_daerah')->nullable();
            $table->integer('id_dana')->nullable()->index();

            $table->string('id_unik', 512)->nullable();

            $table->integer('is_locked')->nullable()->index();

            $table->string('kode_dana', 50)->index();
            $table->text('nama_dana');
            $table->text('sumber_dana')->nullable();

            $table->string('set_input', 50)->nullable()->index();
            $table->string('status', 50)->nullable();

            $table->year('tahun')->default(2021);
            $table->year('tahun_anggaran')->nullable()->index();

            $table->dateTime('updated_at')->nullable();
            $table->integer('updated_user')->default(0);

            $table->tinyInteger('active')
                ->default(1)
                ->comment('0=hapus, 1=aktif')
                ->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sumber_dana');
    }
};
