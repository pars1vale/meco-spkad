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
            if ($this->option('truncate') && !$dryRun) {
                DB::connection('mysql')->table('akun')->truncate();
                $this->warn('Truncated akun table.');
            }

            // Cek koneksi ke sumber data
            if (!$this->checkSourceConnection()) {
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
            $this->error('❌ Error occurred: ' . $e->getMessage());
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
            ->table('u405304318_yahukimo2025.data_akun')
            ->select(
                'id_akun',
                'kode_akun',
                'nama_akun',
                'ket_akun',
                'is_pendapatan',
                'is_pembiayaan',
                'pendapatan',
                'belanja',
                'pembiayaan'
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
                'id'              => $row->id_akun,
                'kode_akun'       => $row->kode_akun,
                'nama_akun'       => $row->nama_akun,
                'keterangan_akun' => $row->ket_akun ?? null,
                'is_pendapatan'   => (int) $row->is_pendapatan,
                'is_belanja'      => $this->determineIsBelanja($row),
                'is_pembiayaan'   => (int) $row->is_pembiayaan,
                'pendapatan'      => $row->pendapatan ?? 'tidak',
                'belanja'         => $row->belanja ?? 'tidak',
                'pembiayaan'      => $row->pembiayaan ?? 'tidak',
                'created_at'      => now(),
                'updated_at'      => now(),
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
        if (!empty($batch)) {
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
            ['id'], // Unique key
            [
                'kode_akun',
                'nama_akun',
                'keterangan_akun',
                'is_pendapatan',
                'is_belanja',
                'is_pembiayaan',
                'pendapatan',
                'belanja',
                'pembiayaan',
                'updated_at',
            ]
        );
    }

    private function outputDryRunSample(array $batch, int $batchCount): void
    {
        $this->line("🔍 Dry-run batch {$batchCount}: showing first 2 records:");
        foreach (array_slice($batch, 0, 2) as $item) {
            $this->line(" - [{$item['kode_akun']}] {$item['nama_akun']}");
        }
    }

    private function determineIsBelanja($row): int
    {
        if (isset($row->belanja) && strtolower($row->belanja) === 'ya') {
            return 1;
        }

        if (!(int)$row->is_pendapatan && !(int)$row->is_pembiayaan) {
            return 1;
        }

        return 0;
    }
}
