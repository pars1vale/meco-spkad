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
        Schema::create('standar_harga', function (Blueprint $table) {
            $table->id();
            $table->string('kode_standar_harga', 50)->unique();
            $table->enum('tipe_standar_harga', ['SSH', 'HSPK', 'ASB', 'SBU']);
            $table->foreignId('id_kelompok_standar_harga')->constrained('kelompok_standar_harga')->onDelete('cascade');
            $table->foreignId('id_satuan')->constrained('data_satuan')->onDelete('cascade');
            $table->text('nama_standar_harga');
            $table->text('spesifikasi')->nullable();
            $table->decimal('harga', 15, 2);
            $table->decimal('nilai_tkdn', 5, 2)->default(0);
            $table->boolean('is_pdn')->default(false);
            $table->timestamps();

            $table->index(['kode_standar_harga']);
            $table->index(['tipe_standar_harga']);
            $table->index(['id_kelompok_standar_harga']);
            $table->index(['id_satuan']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('standar_harga');
    }
};
