<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PangkatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        DB::table('pangkat')->insert([
            [
                'id' => 1,
                'nama' => 'Juru Muda / (I/a)',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'nama' => 'Juru Muda Tingkat I / (I/b)',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'nama' => 'Juru / (I/c)',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'nama' => 'Juru Tingkat I / (I/d)',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 5,
                'nama' => 'Pengatur Muda / (II/a)',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 6,
                'nama' => 'Pengatur Muda Tingkat I / (II/b)',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 7,
                'nama' => 'Pengatur / (II/c)',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 8,
                'nama' => 'Pengatur Tingkat I / (II/d)',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 9,
                'nama' => 'Penata Muda / (III/a)',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 10,
                'nama' => 'Penata Muda TK. I / (III/b)',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 11,
                'nama' => 'Penata / (III/c)',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 12,
                'nama' => 'Penata TK. I / (III/d)',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 13,
                'nama' => 'Pembina / (IV/a)',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 14,
                'nama' => 'Pembina TK. I / (IV/b)',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 15,
                'nama' => 'Pembina Utama Muda / (IV/c)',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 16,
                'nama' => 'Pembina Utama Madya / (IV/d)',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 17,
                'nama' => 'Pembina Utama / (IV/e)',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
