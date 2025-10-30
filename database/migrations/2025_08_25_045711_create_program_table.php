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
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedBigInteger('id_bidang_urusan');
            $table->string('kode_program');
            $table->string('nama_program');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamp('time_stamp')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('id_bidang_urusan')
                ->references('id')
                ->on('bidang_urusan')
                ->cascadeOnDelete();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
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
