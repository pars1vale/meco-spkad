<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InsertProgram extends Command
{
    protected $signature = 'insert:program {--truncate : Kosongkan tabel program terlebih dulu}';
    protected $description = 'Insert program data from SIPD-RI into the program (SPKAD) table';

    public function handle()
    {
        if ($this->option('truncate')) {
            DB::connection('mysql')->table('program')->truncate();
            $this->warn('Truncated program table.');
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
                'nama_program'
            )
            ->distinct()
            ->orderBy('kode_urusan')
            ->orderBy('kode_bidang_urusan')
            ->orderBy('kode_program')
            ->get()
            ->groupBy('id_urusan');

        $totalData = $data->flatten(1)->count();
        $this->info($totalData . " Data Program ditemukan.");

        $insertData = [];
        $notFoundBidangUrusan = [];

        foreach ($data as $idUrusan => $programList) {
            foreach ($programList as $row) {
                // Cek apakah id_bidang_urusan ada di tabel bidang_urusan
                $bidangUrusanExists = DB::connection('mysql')
                    ->table('bidang_urusan')
                    ->where('id', $row->id_bidang_urusan)
                    ->exists();

                if (!$bidangUrusanExists) {
                    $notFoundBidangUrusan[] = $row->id_bidang_urusan;
                    continue;
                }

                $insertData[] = [
                    'id' => $row->id_program,
                    'id_bidang_urusan' => $row->id_bidang_urusan,
                    'kode_program' => $row->kode_program,
                    'nama_program' => $row->nama_program,
                    'time_stamp' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (!empty($notFoundBidangUrusan)) {
            $this->warn('ID Bidang Urusan berikut tidak ditemukan:');
            foreach (array_unique($notFoundBidangUrusan) as $id) {
                $this->warn("- ID: {$id}");
            }
        }

        if (!empty($insertData)) {
            DB::connection('mysql')->table('program')->upsert(
                $insertData,
                ['id'],
                ['id_bidang_urusan', 'kode_program', 'nama_program', 'time_stamp', 'updated_at']
            );

            $this->info(count($insertData) . " data berhasil dimasukkan/diupdate ke tabel program.");
        }

        $totalProgram = DB::connection('mysql')->table('program')->count();
        $this->info("Total program di database: {$totalProgram}");
    }
}
