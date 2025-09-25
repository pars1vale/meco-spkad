<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InsertSubKegiatan extends Command
{
    protected $signature = 'insert:sub-kegiatan {--truncate : Kosongkan tabel sub_kegiatan terlebih dulu}';
    protected $description = 'Insert sub kegiatan data from SIPD-RI into the sub_kegiatan (SPKAD) table';

    public function handle()
    {
        if ($this->option('truncate')) {
            DB::connection('mysql')->table('sub_kegiatan')->truncate();
            $this->warn('Truncated sub_kegiatan table.');
        }

        // Ambil data dari sumber eksternal
        $data = DB::connection('data_sources')
            ->table('u405304318_yahukimo2025.data_prog_keg')
            ->select(
                'id_urusan',
                'kode_urusan',
                'nama_urusan',
                'id_bidang_urusan',
                'kode_bidang_urusan',
                'nama_bidang_urusan',
                'id_program',
                'kode_program',
                'nama_program',
                'id_giat',
                'kode_giat',
                'nama_giat',
                'id_sub_giat',
                'kode_sub_giat',
                'nama_sub_giat'
            )
            ->distinct()
            ->orderBy('kode_urusan')
            ->orderBy('kode_bidang_urusan')
            ->orderBy('kode_program')
            ->orderBy('kode_giat')
            ->orderBy('kode_sub_giat')
            ->get()
            ->groupBy('id_urusan');

        $totalData = $data->flatten(1)->count();
        $this->info($totalData . " Data Sub Kegiatan ditemukan.");

        $insertData = [];
        $notFoundKegiatan = [];

        foreach ($data as $idUrusan => $subKegiatanList) {
            foreach ($subKegiatanList as $row) {
                // Cek apakah id_giat ada di tabel kegiatan
                $kegiatanExists = DB::connection('mysql')
                    ->table('kegiatan')
                    ->where('id', $row->id_giat)
                    ->exists();

                if (!$kegiatanExists) {
                    $notFoundKegiatan[] = $row->id_giat;
                    continue;
                }

                $insertData[] = [
                    'id' => $row->id_sub_giat,
                    'id_kegiatan' => $row->id_giat,
                    'kode_sub_kegiatan' => $row->kode_sub_giat,
                    'nama_sub_kegiatan' => $row->nama_sub_giat,
                    'time_stamp' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Jika ada kegiatan yang tidak ditemukan
        if (!empty($notFoundKegiatan)) {
            $this->warn('ID Kegiatan berikut tidak ditemukan:');
            foreach (array_unique($notFoundKegiatan) as $id) {
                $this->warn("- ID: {$id}");
            }
        }

        // Insert data dengan batch dinamis
        if (!empty($insertData)) {
            // Hitung jumlah kolom yang diinsert
            $columnCount = count($insertData[0]);

            // Batas maksimal placeholder MySQL (default 65535)
            $maxPlaceholders = 65535;

            // Hitung batch size aman
            $batchSize = floor($maxPlaceholders / $columnCount);

            // Pecah data jadi beberapa chunk sesuai batchSize
            $chunks = array_chunk($insertData, $batchSize);

            foreach ($chunks as $chunk) {
                DB::connection('mysql')->table('sub_kegiatan')->upsert(
                    $chunk,
                    ['id'],
                    ['id_kegiatan', 'kode_sub_kegiatan', 'nama_sub_kegiatan', 'time_stamp', 'updated_at']
                );
            }

            $this->info(count($insertData) . " data berhasil dimasukkan/diupdate ke tabel sub_kegiatan (dalam " . count($chunks) . " batch).");
        }

        // Hitung total setelah insert
        $totalSubKegiatan = DB::connection('mysql')->table('sub_kegiatan')->count();
        $this->info("Total sub kegiatan di database: {$totalSubKegiatan}");
    }
}
