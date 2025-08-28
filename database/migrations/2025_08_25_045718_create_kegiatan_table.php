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
        Schema::create('kegiatan', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary(); // Manual ID dari data source
            $table->unsignedBigInteger('id_program'); // Foreign key ke program
            $table->string('kode_kegiatan');
            $table->text('nama_kegiatan');
            $table->timestamp('time_stamp')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_program')
                ->references('id')
                ->on('program')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kegiatan');
    }
};
