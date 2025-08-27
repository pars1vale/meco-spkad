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
        Schema::create('sub_kegiatan', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary(); // Manual ID dari data source
            $table->unsignedBigInteger('id_kegiatan'); // Foreign key ke kegiatan
            $table->string('kode_sub_kegiatan')->unique();
            $table->text('nama_sub_kegiatan');
            $table->timestamp('time_stamp')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_kegiatan')
                ->references('id')
                ->on('kegiatan')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_kegiatan');
    }
};
