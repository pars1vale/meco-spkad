<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InsertSumberDana extends Command
{
    protected $signature = 'insert:sumberdana {--truncate : Kosongkan tabel sumber_dana terlebih dulu}';
    protected $description = 'Insert sumber dana data from SIPD-RI into the sumber_dana (SPKAD) table';

    public function handle()
    {
        try {
            if ($this->option('truncate')) {
                DB::connection('mysql')->table('sumber_dana')->truncate();
                $this->warn('Truncated sumber_dana table.');
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

            $this->info('Insert sumber dana command completed successfully.');
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
            ->table('u405304318_yahukimo2025.data_sumber_dana')
            ->select(
                'id',
                'kode_dana',
                'nama_dana',
                'sumber_dana',
                'set_input',
                'created_at',
                'updated_at'
            )
            ->where('active', 1) // Only get active records
            ->orderBy('kode_dana', 'asc')
            ->get();
    }

    private function prepareInsertData($data): array
    {
        $insertData = [];

        foreach ($data as $row) {
            $insertData[] = [
                'id'           => $row->id,
                'kode_dana'    => $row->kode_dana,
                'nama_dana'    => $row->nama_dana,
                'sumber_dana'  => $row->sumber_dana ?? null,
                'set_input'    => $this->normalizeSetInput($row->set_input),
                'time_stamp'   => $row->created_at ?? now(),
                'updated_at'   => $row->updated_at ?? now(),
            ];
        }

        return $insertData;
    }

    private function normalizeSetInput($value): string
    {
        // Normalize set_input to match your database structure
        if (empty($value)) {
            return 'Ya';
        }

        $value = strtolower(trim($value));

        // Convert various inputs to 'Ya' or 'Tidak'
        if (in_array($value, ['ya', 'yes', '1', 'true', 'aktif'])) {
            return 'Ya';
        }

        if (in_array($value, ['tidak', 'no', '0', 'false', 'tidak aktif'])) {
            return 'Tidak';
        }

        return 'Ya'; // Default to 'Ya'
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
            DB::connection('mysql')->table('sumber_dana')->upsert(
                $chunk,
                ['id'], // Unique key for upsert
                [
                    'kode_dana',
                    'nama_dana',
                    'sumber_dana',
                    'set_input',
                    'updated_at',
                ]
            );

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
        $this->info(count($insertData) . " records successfully inserted/updated in " . count($chunks) . " batches.");
    }
}
