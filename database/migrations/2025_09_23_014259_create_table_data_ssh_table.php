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
        Schema::create('table_data_ssh', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_kelompok_standar_harga');
            $table->unsignedBigInteger('id_satuan');

            $table->string('kode_standar_harga', 30)->unique();
            $table->text('nama_standar_harga');
            $table->text('spesifikasi')->nullable();
            $table->decimal('harga', 20, 2)->default(0);
            $table->decimal('tkdn', 5, 2)->nullable()->default(0);
            $table->tinyInteger('is_active')->nullable()->default(1);

            $table->timestamps();

            $table->foreign('id_kelompok_standar_harga')
                ->references('id')
                ->on('table_data_kelompok_standar_harga')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('id_satuan')
                ->references('id')
                ->on('table_data_satuan')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->index(['kode_standar_harga']);
            $table->index(['is_active']);
            $table->index(['id_kelompok_standar_harga', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_data_ssh');
    }
};
