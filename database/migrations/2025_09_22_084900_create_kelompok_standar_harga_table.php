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
        Schema::create('kelompok_standar_harga', function (Blueprint $table) {
            $table->id();

            $table->string('kode_kelompok_standar_harga', 30)
                ->unique()
                ->index();

            $table->text('nama_kelompok_standar_harga');

            $table->enum('tipe_kelompok', ['SSH', 'HSPK', 'ASB', 'SBU'])
                ->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompok_standar_harga');
    }
};
