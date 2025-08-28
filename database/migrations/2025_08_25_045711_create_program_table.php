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
        Schema::create('program', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary(); // Manual ID dari data source
            $table->unsignedBigInteger('id_bidang_urusan'); // Foreign key ke bidang_urusan
            $table->string('kode_program');
            $table->string('nama_program');
            $table->timestamp('time_stamp')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_bidang_urusan')
                ->references('id')
                ->on('bidang_urusan')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program');
    }
};
