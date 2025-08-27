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
        Schema::create('bidang_urusan', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary(); // Manual ID, bukan auto-increment
            $table->unsignedBigInteger('id_urusan'); // Foreign key ke urusan
            $table->string('kode_bidang_urusan')->unique();
            $table->string('nama_bidang_urusan');
            $table->timestamp('time_stamp')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_urusan')
                ->references('id')
                ->on('urusan')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bidang_urusan');
    }
};
