<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InsertAkun extends Command
{
    protected $signature = 'insert:akun 
                            {--truncate : Kosongkan tabel akun terlebih dulu}
                            {--dry-run : Jalankan tanpa menyimpan data ke database}';

    protected $description = 'Insert akun data from SIPD-RI into the akun (SPKAD) table';

    public function handle()
    {
        // memory limit
        ini_set('memory_limit', '1024M');

        $dryRun = $this->option('dry-run');

        try {
            if ($this->option('truncate') && ! $dryRun) {
                DB::connection('mysql')->table('akun')->truncate();
                $this->warn('Truncated akun table.');
            }

            // Cek koneksi ke sumber data
            if (! $this->checkSourceConnection()) {
                $this->error('Cannot connect to data_sources database.');

                return Command::FAILURE;
            }

            $this->info('Fetching data from source...');
            $data = $this->fetchSourceData();

            if ($data === null) {
                $this->error('Failed to fetch data.');

                return Command::FAILURE;
            }

            if ($dryRun) {
                $this->warn('⚙️  Running in DRY-RUN mode — no data will be inserted.');
            }

            $this->info('Starting insert process (streamed)...');
            $this->insertDataInBatches($data, $dryRun);

            if ($dryRun) {
                $this->info('Dry-run completed successfully. No changes were made.');
            } else {
                $this->info('Insert akun command completed successfully.');
            }

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('❌ Error occurred: '.$e->getMessage());

            return Command::FAILURE;
        }
    }

    private function checkSourceConnection(): bool
    {
        try {
            DB::connection('data_sources')->getPdo();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function fetchSourceData()
    {
        return DB::connection('data_sources')
            // ->table('u405304318_yahukimo2025.data_akun')
            ->table('yahukimo2026_20260107.data_akun')
            ->select(
                'id_akun',
                'belanja',
                'is_bagi_hasil',
                'is_bankeu_khusus',
                'is_bankeu_umum',
                'is_barjas',
                'is_bos',
                'is_btt',
                'is_bunga',
                'is_gaji_asn',
                'is_hibah_brg',
                'is_hibah_uang',
                'is_locked',
                'is_modal_tanah',
                'is_pembiayaan',
                'is_pendapatan',
                'is_sosial_brg',
                'is_sosial_uang',
                'is_subsidi',
                'kode_akun',
                'nama_akun',
                'set_input',
                'set_lokus',
                'ket_akun',
                'kode_akun_lama',
                'kode_akun_revisi',
                'kunci_tahun',
                'level',
                'mulai_tahun',
                'pembiayaan',
                'pendapatan',
                'set_kab_kota',
                'set_prov',
                'status',
                'active',
                'update_at',
                'tahun_anggaran'
            )
            ->orderBy('kode_akun', 'asc')
            ->cursor();
    }

    private function insertDataInBatches($cursor, bool $dryRun = false): void
    {
        $batch = [];
        $batchSize = 500;
        $count = 0;
        $batchCount = 0;

        foreach ($cursor as $row) {
            $batch[] = [
                'id_akun' => $row->id_akun ?? null,
                'belanja' => $row->belanja ?? null,
                'is_bagi_hasil' => $row->is_bagi_hasil ?? null,
                'is_bankeu_khusus' => $row->is_bankeu_khusus ?? null,
                'is_bankeu_umum' => $row->is_bankeu_umum ?? null,
                'is_barjas' => $row->is_barjas ?? null,
                'is_bl' => $this->determineIsBl($row), // Logic bisnis custom
                'is_bos' => $row->is_bos ?? null,
                'is_btt' => $row->is_btt ?? null,
                'is_bunga' => $row->is_bunga ?? null,
                'is_gaji_asn' => $row->is_gaji_asn ?? null,
                'is_hibah_brg' => $row->is_hibah_brg ?? null,
                'is_hibah_uang' => $row->is_hibah_uang ?? null,
                'is_locked' => $row->is_locked ?? null,
                'is_modal_tanah' => $row->is_modal_tanah ?? null,
                'is_pembiayaan' => $row->is_pembiayaan ?? null,
                'is_pendapatan' => $row->is_pendapatan ?? null,
                'is_sosial_brg' => $row->is_sosial_brg ?? null,
                'is_sosial_uang' => $row->is_sosial_uang ?? null,
                'is_subsidi' => $row->is_subsidi ?? null,
                'kode_akun' => $row->kode_akun ?? null,
                'nama_akun' => $row->nama_akun ?? null,
                'set_input' => $row->set_input ?? null,
                'set_lokus' => $row->set_lokus ?? null,
                'ket_akun' => $row->ket_akun ?? null,
                'kode_akun_lama' => $row->kode_akun_lama ?? null,
                'kode_akun_revisi' => $row->kode_akun_revisi ?? null,
                'kunci_tahun' => $row->kunci_tahun ?? null,
                'level' => $row->level ?? null,
                'mulai_tahun' => $row->mulai_tahun ?? null,
                'pembiayaan' => $row->pembiayaan ?? null,
                'pendapatan' => $row->pendapatan ?? null,
                'set_kab_kota' => $row->set_kab_kota ?? null,
                'set_prov' => $row->set_prov ?? null,
                'status' => $row->status ?? null,
                'active' => $row->active ?? 1,
                'update_at' => $row->update_at ?? null,
                'tahun_anggaran' => $row->tahun_anggaran ?? 2021,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($batch) >= $batchSize) {
                $count += count($batch);
                $batchCount++;

                if ($dryRun) {
                    $this->outputDryRunSample($batch, $batchCount);
                } else {
                    $this->upsertBatch($batch);
                }

                $this->info("Processed batch {$batchCount} ({$count} records total)");
                $batch = [];
            }
        }

        // Sisa batch terakhir
        if (! empty($batch)) {
            $count += count($batch);
            $batchCount++;

            if ($dryRun) {
                $this->outputDryRunSample($batch, $batchCount);
            } else {
                $this->upsertBatch($batch);
            }

            $this->info("Processed final batch {$batchCount} ({$count} total)");
        }

        $this->info("Done processing {$count} records in {$batchCount} batches.");
    }

    private function upsertBatch(array $batch): void
    {
        DB::connection('mysql')->table('akun')->upsert(
            $batch,
            ['id_akun'], // Unique key
            [
                'belanja',
                'is_bagi_hasil',
                'is_bankeu_khusus',
                'is_bankeu_umum',
                'is_barjas',
                'is_bl', // Belanja dengan logic custom
                'is_bos',
                'is_btt',
                'is_bunga',
                'is_gaji_asn',
                'is_hibah_brg',
                'is_hibah_uang',
                'is_locked',
                'is_modal_tanah',
                'is_pembiayaan',
                'is_pendapatan',
                'is_sosial_brg',
                'is_sosial_uang',
                'is_subsidi',
                'kode_akun',
                'nama_akun',
                'set_input',
                'set_lokus',
                'ket_akun',
                'kode_akun_lama',
                'kode_akun_revisi',
                'kunci_tahun',
                'level',
                'mulai_tahun',
                'pembiayaan',
                'pendapatan',
                'set_kab_kota',
                'set_prov',
                'status',
                'active',
                'update_at',
                'tahun_anggaran',
                'updated_at',
            ]
        );
    }

    private function outputDryRunSample(array $batch, int $batchCount): void
    {
        $this->line("📋 Dry-run batch {$batchCount}: showing first 2 records:");
        foreach (array_slice($batch, 0, 2) as $item) {
            $belanjaFlag = $item['is_bl'] ? '✓ Belanja' : '✗';
            $this->line(" - [{$item['kode_akun']}] {$item['nama_akun']} ({$belanjaFlag})");
        }
    }

    /**
     * Logic bisnis untuk menentukan is_bl (belanja)
     *
     * Logic:
     * 1. Jika field 'belanja' = 'ya', maka is_bl = 1
     * 2. Jika bukan pendapatan DAN bukan pembiayaan, maka is_bl = 1
     * 3. Selain itu is_bl = 0
     */
    private function determineIsBl($row): int
    {
        // Cek field belanja text
        if (isset($row->belanja) && strtolower($row->belanja) === 'ya') {
            return 1;
        }

        // Jika bukan pendapatan dan bukan pembiayaan, maka belanja
        if (! (int) ($row->is_pendapatan ?? 0) && ! (int) ($row->is_pembiayaan ?? 0)) {
            return 1;
        }

        return 0;
    }
}
