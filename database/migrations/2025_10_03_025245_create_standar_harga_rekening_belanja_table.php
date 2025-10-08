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
        Schema::create('standar_harga_rekening_belanja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_standar_harga')->constrained('standar_harga')->onDelete('cascade');
            $table->foreignId('id_akun')->constrained('akun')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['id_standar_harga', 'id_akun']);
            $table->index(['id_standar_harga']);
            $table->index(['id_akun']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('standar_harga_rekening_belanja');
    }
};
