<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InsertUnit extends Command
{
    protected $signature = 'insert:unit {--truncate : Kosongkan tabel terlebih dulu}';

    protected $description = 'Insert Unit data from SIPD-RI into data_unit table';

    public function handle()
    {
        if ($this->option('truncate')) {
            DB::table('data_unit')->truncate();
            $this->warn('data_unit table truncated.');
        }

        $sourceData = DB::connection('data_sources')
            // ->table('u405304318_yahukimo2025.data_unit')
            ->table('yahukimo2026_20260107.data_unit')
            ->orderBy('kode_skpd', 'asc')
            ->get();

        $this->info($sourceData->count().' data ditemukan.');

        // Ambil kode_skpd yang sudah ada di DB lokal
        $existingKode = DB::table('data_unit')
            ->pluck('kode_skpd')
            ->filter()
            ->toArray();

        $existingKode = array_flip($existingKode); // biar lookup O(1)

        $insertData = [];
        $seenSourceKode = [];
        $skippedSourceDuplicate = 0;
        $skippedExisting = 0;

        $bar = $this->output->createProgressBar($sourceData->count());
        $bar->start();

        foreach ($sourceData as $row) {
            $kode = $row->kode_skpd;

            // Skip duplikat di source
            if ($kode && isset($seenSourceKode[$kode])) {
                $skippedSourceDuplicate++;
                $bar->advance();

                continue;
            }

            // Skip jika sudah ada di DB lokal
            if ($kode && isset($existingKode[$kode])) {
                $skippedExisting++;
                $bar->advance();

                continue;
            }

            $seenSourceKode[$kode] = true;

            $insertData[] = [
                'id' => $row->id,
                'id_setup_unit' => $row->id_setup_unit,
                'id_unit' => $row->id_unit,
                'is_skpd' => $row->is_skpd,
                'kode_skpd' => $row->kode_skpd,
                'kunci_skpd' => $row->kunci_skpd,
                'nama_skpd' => $row->nama_skpd,
                'posisi' => $row->posisi,
                'status' => $row->status,
                'id_skpd' => $row->id_skpd,
                'bidur_1' => $row->bidur_1,
                'bidur_2' => $row->bidur_2,
                'bidur_3' => $row->bidur_3,
                'idinduk' => $row->idinduk,
                'ispendapatan' => $row->ispendapatan,
                'isskpd' => $row->isskpd,
                'kode_skpd_1' => $row->kode_skpd_1,
                'kode_skpd_2' => $row->kode_skpd_2,
                'kodeunit' => $row->kodeunit,
                'komisi' => $row->komisi,
                'namabendahara' => $row->namabendahara,
                'namakepala' => $row->namakepala,
                'namaunit' => $row->namaunit,
                'nipbendahara' => $row->nipbendahara,
                'nipkepala' => $row->nipkepala,
                'pangkatkepala' => $row->pangkatkepala,
                'setupunit' => $row->setupunit,
                'statuskepala' => $row->statuskepala,
                'mapping' => $row->mapping,
                'id_kecamatan' => $row->id_kecamatan,
                'id_strategi' => $row->id_strategi,
                'is_dpa_khusus' => $row->is_dpa_khusus,
                'is_ppkd' => $row->is_ppkd,
                'set_input' => $row->set_input,
                'tahun_anggaran' => $row->tahun_anggaran ?? 2025,
                'active' => $row->active ?? 1,
                'created_at' => now(),
                'updated_at' => $row->update_at ?? now(),
            ];

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        if (! empty($insertData)) {
            DB::table('data_unit')->insert($insertData);
        }

        $this->info(count($insertData).' data berhasil diinsert.');
        $this->warn($skippedSourceDuplicate.' duplikat kode_skpd di source dilewati.');
        $this->warn($skippedExisting.' data sudah ada di database dilewati.');
    }
}
