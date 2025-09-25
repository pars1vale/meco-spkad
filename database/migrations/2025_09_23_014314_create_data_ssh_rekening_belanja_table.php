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
        Schema::create('data_ssh_rekening_belanja', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_ssh');
            $table->unsignedBigInteger('id_akun');

            $table->tinyInteger('active')->nullable()->default(1);
            $table->timestamps();

            $table->foreign('id_ssh')
                ->references('id')
                ->on('data_ssh')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('id_akun')
                ->references('id')
                ->on('akun')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->unique(['id_ssh', 'id_akun'], 'ssh_akun_unique');

            $table->index(['active']);
            $table->index(['id_ssh', 'active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('table_data_ssh_rekening_belanja');
    }
};
