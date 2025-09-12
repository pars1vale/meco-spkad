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
        Schema::create('sumber_dana', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('kode_dana');
            $table->text('nama_dana');
            $table->text('sumber_dana')->nullable();
            $table->string('set_input')->default('Ya');
            $table->timestamp('time_stamp')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sumber_dana');
    }
};
