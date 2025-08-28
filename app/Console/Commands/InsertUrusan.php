<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InsertUrusan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:urusan {--truncate : Kosongkan tabel urusan terlebih dulu}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert urusan data from SIPD-RI into the urusan (SPKAD) table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('truncate')) {
            DB::connection('mysql')->table('urusan')->truncate();
            $this->warn('Truncated urusan table.');
        }

        $data = DB::connection('data_sources')
            ->table('u405304318_yahukimo2025.data_prog_keg')
            ->select('id_urusan', 'kode_urusan', 'nama_urusan')
            ->distinct()
            ->orderBy('kode_urusan', 'asc')
            ->orderBy('nama_urusan', 'asc')
            ->get();

        // dd($data);
        $this->info($data->count() . " Data Ditemukan.");

        $insertData = [];
        foreach ($data as $row) {
            $insertData[] = [
                'id' => $row->id_urusan, // Simpan ID dari SIPD-RI
                'kode_urusan' => $row->kode_urusan,
                'nama_urusan' => $row->nama_urusan,
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }
        if (!empty($insertData)) {
            // gunakan upsert agar tidak error kalau id_urusan sudah ada
            DB::connection('mysql')->table('urusan')->upsert(
                $insertData,
                ['id'], // kolom unik
                ['kode_urusan', 'nama_urusan', 'updated_at'] // kolom yang diupdate jika duplikat
            );
        }

        $this->info("Data -Urusan- has been inserted successfully.");
    }
}
