<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InsertBidangUrusan extends Command
{
    protected $signature = 'insert:bidang-urusan {--truncate : Kosongkan tabel bidang_urusan terlebih dulu}';
    protected $description = 'Insert bidang urusan data from SIPD-RI into the bidang_urusan (SPKAD) table';

    public function handle()
    {
        if ($this->option('truncate')) {
            DB::connection('mysql')->table('bidang_urusan')->truncate();
            $this->warn('Truncated bidang_urusan table.');
        }

        $data = DB::connection('data_sources')
            ->table('u405304318_yahukimo2025.data_prog_keg')
            ->select('nama_urusan', 'id_bidang_urusan', 'kode_bidang_urusan', 'nama_bidang_urusan', 'id_urusan')
            ->distinct()
            ->orderBy('nama_urusan')
            ->orderBy('kode_bidang_urusan')
            ->get()
            ->groupBy('nama_urusan');

        $totalData = $data->flatten(1)->count();
        $this->info($totalData . " Data Bidang Urusan ditemukan.");

        $insertData = [];
        $notFoundUrusan = [];

        foreach ($data as $namaUrusan => $bidangUrusanList) {
            foreach ($bidangUrusanList as $row) {
                // Cek apakah id_urusan ada di tabel urusan
                $urusanExists = DB::connection('mysql')
                    ->table('urusan')
                    ->where('id', $row->id_urusan)
                    ->exists();

                if (!$urusanExists) {
                    $notFoundUrusan[] = $row->id_urusan;
                    continue;
                }

                $insertData[] = [
                    'id' => $row->id_bidang_urusan,
                    'id_urusan' => $row->id_urusan,
                    'kode_bidang_urusan' => $row->kode_bidang_urusan,
                    'nama_bidang_urusan' => $row->nama_bidang_urusan,
                    'time_stamp' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (!empty($notFoundUrusan)) {
            $this->warn('ID Urusan berikut tidak ditemukan:');
            foreach (array_unique($notFoundUrusan) as $id) {
                $this->warn("- ID: {$id}");
            }
        }

        if (!empty($insertData)) {
            DB::connection('mysql')->table('bidang_urusan')->upsert(
                $insertData,
                ['id'],
                ['id_urusan', 'kode_bidang_urusan', 'nama_bidang_urusan', 'time_stamp', 'updated_at']
            );

            $this->info(count($insertData) . " data berhasil dimasukkan/diupdate ke tabel bidang_urusan.");
        }

        $totalBidangUrusan = DB::connection('mysql')->table('bidang_urusan')->count();
        $this->info("Total bidang urusan di database: {$totalBidangUrusan}");
    }
}
