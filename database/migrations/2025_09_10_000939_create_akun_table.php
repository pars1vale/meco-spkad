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
            $table->unsignedBigInteger('id')->primary();
            $table->string('kode_akun');
            $table->text('nama_akun');
            $table->text('keterangan_akun')->nullable();
            $table->tinyInteger('is_pendapatan');
            $table->tinyInteger('is_belanja');
            $table->tinyInteger('is_pembiayaan');
            $table->string('pendapatan', 50);
            $table->string('belanja', 50);
            $table->string('pembiayaan', 50);
            $table->timestamp('time_stamp')->nullable();
            $table->timestamp('updated_at')->nullable();
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
