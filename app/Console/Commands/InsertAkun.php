<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InsertAkun extends Command // Fixed class name
{
    protected $signature = 'insert:akun {--truncate : Kosongkan tabel akun terlebih dulu}';
    protected $description = 'Insert akun data from SIPD-RI into the akun (SPKAD) table';

    public function handle()
    {
        try {
            if ($this->option('truncate')) {
                DB::connection('mysql')->table('akun')->truncate();
                $this->warn('Truncated akun table.');
            }

            // Check if data_sources connection is available
            if (!$this->checkSourceConnection()) {
                $this->error('Cannot connect to data_sources database.');
                return Command::FAILURE;
            }

            $data = $this->fetchSourceData();

            if ($data->isEmpty()) {
                $this->info('No data found in source table.');
                return Command::SUCCESS;
            }

            $this->info($data->count() . " records found.");

            $insertData = $this->prepareInsertData($data);
            $this->insertDataInBatches($insertData);

            $this->info('Insert akun command completed successfully.');
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error occurred: ' . $e->getMessage());
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
                // Add 'is_belanja' here if it exists in source
            )
            ->orderBy('kode_akun', 'asc')
            ->get();
    }

    private function prepareInsertData($data): array
    {
        $insertData = [];

        foreach ($data as $row) {
            // Determine is_belanja based on belanja field or other logic
            $isBelanja = $this->determineIsBelanja($row);

            $insertData[] = [
                'id'             => $row->id_akun,
                'kode_akun'      => $row->kode_akun,
                'nama_akun'      => $row->nama_akun,
                'keterangan_akun' => $row->ket_akun ?? null, // Handle null values
                'is_pendapatan'  => (int) $row->is_pendapatan,
                'is_belanja'     => $isBelanja,
                'is_pembiayaan'  => (int) $row->is_pembiayaan,
                'pendapatan'     => $row->pendapatan ?? 'tidak',
                'belanja'        => $row->belanja ?? 'tidak',
                'pembiayaan'     => $row->pembiayaan ?? 'tidak',
                'created_at'     => now(),
                'updated_at'     => now(),
            ];
        }

        return $insertData;
    }

    private function determineIsBelanja($row): int
    {
        // Option 1: If source has is_belanja field
        // return (int) ($row->is_belanja ?? 0);

        // Option 2: Derive from belanja field
        if (isset($row->belanja)) {
            return $row->belanja === 'ya' ? 1 : 0;
        }

        // Option 3: Derive from account code pattern (example)
        // if (str_starts_with($row->kode_akun, '5')) {
        //     return 1; // Belanja accounts typically start with 5
        // }

        // Option 4: Check if not pendapatan and not pembiayaan
        if (!$row->is_pendapatan && !$row->is_pembiayaan) {
            return 1; // Assume it's belanja if not the other two
        }

        return 0; // Default to 0
    }

    private function insertDataInBatches(array $insertData): void
    {
        if (empty($insertData)) {
            $this->info("No data to insert.");
            return;
        }

        $columnCount = count($insertData[0]);
        $maxPlaceholders = 65535;
        $batchSize = (int) floor(($maxPlaceholders / $columnCount) * 0.9);

        $chunks = array_chunk($insertData, $batchSize);

        $this->output->progressStart(count($chunks));

        foreach ($chunks as $chunk) {
            DB::connection('mysql')->table('akun')->upsert(
                $chunk,
                ['id'], // Unique key for upsert
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

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
        $this->info(count($insertData) . " records successfully inserted/updated in " . count($chunks) . " batches.");
    }
}
