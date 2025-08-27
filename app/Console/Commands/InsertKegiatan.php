<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InsertKegiatan extends Command
{
    protected $signature = 'insert:kegiatan {--truncate : Kosongkan tabel kegiatan terlebih dulu}';
    protected $description = 'Insert kegiatan data from SIPD-RI into the kegiatan (SPKAD) table';

    public function handle()
    {
        if ($this->option('truncate')) {
            DB::connection('mysql')->table('kegiatan')->truncate();
            $this->warn('Truncated kegiatan table.');
        }

        // Menggunakan query yang Anda berikan
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
                'nama_giat'
            )
            ->orderBy('kode_urusan')
            ->orderBy('kode_bidang_urusan')
            ->orderBy('kode_program')
            ->orderBy('kode_giat')
            ->get()
            ->unique(function ($item) {
                return $item->kode_giat . $item->nama_giat;
            })
            ->groupBy('id_urusan');

        $totalData = $data->flatten(1)->count();
        $this->info($totalData . " Data Kegiatan ditemukan.");

        $insertData = [];
        $notFoundProgram = [];

        foreach ($data as $idUrusan => $kegiatanList) {
            foreach ($kegiatanList as $row) {
                // Cek apakah id_program ada di tabel program
                $programExists = DB::connection('mysql')
                    ->table('program')
                    ->where('id', $row->id_program)
                    ->exists();

                if (!$programExists) {
                    $notFoundProgram[] = $row->id_program;
                    continue;
                }

                $insertData[] = [
                    'id' => $row->id_giat,
                    'id_program' => $row->id_program,
                    'kode_kegiatan' => $row->kode_giat,
                    'nama_kegiatan' => $row->nama_giat,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (!empty($notFoundProgram)) {
            $this->warn('ID Program berikut tidak ditemukan:');
            foreach (array_unique($notFoundProgram) as $id) {
                $this->warn("- ID: {$id}");
            }
        }

        if (!empty($insertData)) {
            DB::connection('mysql')->table('kegiatan')->upsert(
                $insertData,
                ['id'],
                ['id_program', 'kode_kegiatan', 'nama_kegiatan', 'time_stamp', 'updated_at']
            );

            $this->info(count($insertData) . " data berhasil dimasukkan/diupdate ke tabel kegiatan.");
        }

        $totalKegiatan = DB::connection('mysql')->table('kegiatan')->count();
        $this->info("Total kegiatan di database: {$totalKegiatan}");
    }
}
