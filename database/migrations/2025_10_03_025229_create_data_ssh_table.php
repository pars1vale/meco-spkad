<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_ssh', function (Blueprint $table) {
            $table->integer('id_standar_harga')->primary();
            $table->string('id_unik', 50);
            $table->integer('id_kel_standar_harga');
            $table->string('kode_kel_standar_harga', 50);
            $table->string('nama_kel_standar_harga', 255);
            $table->string('tipe_standar_harga', 20)->comment('SSH, ASB, SBU, HSPK');
            $table->string('kode_standar_harga', 50);
            $table->string('nama_standar_harga', 255);
            $table->string('satuan', 50);
            $table->decimal('harga', 15, 2)->default(0.00);
            $table->text('spek')->nullable();
            $table->decimal('nilai_tkdn', 10, 2)->nullable()->comment('Tingkat Komponen Dalam Negeri');
            $table->text('ket_teks')->nullable();
            $table->integer('tahun');
            $table->integer('id_daerah');
            $table->boolean('is_pdn')->default(0)->comment('Produk Dalam Negeri');
            $table->boolean('is_locked')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index(['tahun', 'id_daerah'], 'idx_tahun_daerah');
            $table->index('kode_standar_harga', 'idx_kode_standar_harga');
            $table->index('tipe_standar_harga', 'idx_tipe_standar_harga');
            $table->index('id_kel_standar_harga', 'idx_kel_standar_harga');
        });
        DB::statement("ALTER TABLE `data_ssh` COMMENT='Tabel Standar Satuan Harga (SSH, ASB, SBU, HSPK)'");
        DB::statement("ALTER TABLE `data_ssh` ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC");
    }

    public function down(): void
    {
        Schema::dropIfExists('data_ssh');
    }
};
