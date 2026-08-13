<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InsertKegiatan extends Command
{
    protected $signature = 'insert:kegiatan 
                            {--truncate : Kosongkan tabel kegiatan terlebih dulu}
                            {--dry-run : Preview data tanpa melakukan insert}
                            {--debug : Tampilkan informasi debug detail}';

    protected $description = 'Insert kegiatan data from SIPD-RI into the kegiatan (SPKAD) table';

    private $notFoundPrograms = [];

    private $duplicateWarnings = [];

    private $startTime;

    public function handle()
    {
        $this->startTime = microtime(true);

        try {
            $this->info("=== INSERT KEGIATAN COMMAND STARTED ===\n");

            // 1. Check source connection
            if (! $this->checkSourceConnection()) {
                return Command::FAILURE;
            }

            // 2. Handle truncate option
            $this->handleTruncate();

            // 3. Fetch source data
            $rawData = $this->fetchSourceData();
            if ($rawData->isEmpty()) {
                $this->info('No data found in source table.');

                return Command::SUCCESS;
            }

            $this->info('Total raw records fetched: '.$rawData->count());

            // 4. Process and deduplicate data
            $processedData = $this->processData($rawData);
            $this->info('Records after deduplication: '.$processedData->count());

            // 5. Analyze data quality (if debug mode)
            if ($this->option('debug')) {
                $this->analyzeDataQuality($rawData, $processedData);
            }

            // 6. Prepare insert data
            $insertData = $this->prepareInsertData($processedData);

            if (empty($insertData)) {
                $this->warn('No valid data to insert after processing.');

                return Command::SUCCESS;
            }

            $this->info('Valid records ready for insert: '.count($insertData));

            // 7. Dry run mode
            if ($this->option('dry-run')) {
                $this->handleDryRun($insertData);

                return Command::SUCCESS;
            }

            // 8. Insert data in batches
            $beforeCount = $this->getRecordCount();
            $this->insertDataInBatches($insertData);
            $afterCount = $this->getRecordCount();

            // 9. Show summary report
            $this->showSummaryReport($beforeCount, $afterCount, count($insertData));

            // 10. Show warnings if any
            $this->showWarnings();

            $this->info("\n=== INSERT KEGIATAN COMMAND COMPLETED ===");

            return Command::SUCCESS;
        } catch (Exception $e) {
            $this->error("\n=== ERROR OCCURRED ===");
            $this->error('Message: '.$e->getMessage());
            $this->error('File: '.$e->getFile().':'.$e->getLine());

            if ($this->option('debug')) {
                $this->error("\nStack Trace:");
                $this->error($e->getTraceAsString());
            }

            return Command::FAILURE;
        }
    }

    /**
     * Check if source database connection is available
     */
    private function checkSourceConnection(): bool
    {
        try {
            DB::connection('data_sources')->getPdo();
            $this->info('✓ Source database connection OK');

            return true;
        } catch (Exception $e) {
            $this->error('✗ Cannot connect to data_sources database');
            $this->error('Error: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Handle truncate option
     */
    private function handleTruncate(): void
    {
        if ($this->option('truncate')) {
            if ($this->confirm('Are you sure you want to truncate the kegiatan table?', false)) {
                DB::connection('mysql')->table('kegiatan')->truncate();
                $this->warn('✓ Kegiatan table truncated');
            } else {
                $this->info('Truncate cancelled by user');
            }
        }
    }

    /**
     * Fetch data from source database
     */
    private function fetchSourceData()
    {
        $this->info('Fetching data from source...');

        return DB::connection('data_sources')
            // ->table('u405304318_yahukimo2025.data_prog_keg')
            ->table('yahukimo2026_20260107.data_prog_keg')
            ->select(
                'nama_urusan',
                'nama_bidang_urusan',
                'id_program',
                'nama_program',
                'id_giat',
                'kode_giat',
                'nama_giat'
            )
            ->whereNotNull('id_giat')
            ->whereNotNull('kode_giat')
            ->whereNotNull('nama_giat')
            ->whereNotNull('id_program')
            ->orderBy('nama_urusan')
            ->orderBy('nama_bidang_urusan')
            ->orderBy('nama_program')
            ->orderBy('kode_giat')
            ->orderBy('nama_giat')
            ->get();
    }

    /**
     * Process and deduplicate data
     */
    private function processData($rawData)
    {
        $this->info('Processing and deduplicating data...');

        // Remove duplicates based on id_giat
        $processedData = $rawData->unique(function ($item) {
            return $item->id_giat;
        })->values();

        // Check for duplicate codes with different names
        $this->checkDuplicateCodes($processedData);

        return $processedData;
    }

    /**
     * Check for duplicate codes with different names
     */
    private function checkDuplicateCodes($data): void
    {
        $grouped = $data->groupBy('kode_giat');

        $duplicates = $grouped->filter(function ($group) {
            return $group->pluck('nama_giat')->unique()->count() > 1;
        });

        if ($duplicates->count() > 0) {
            $this->duplicateWarnings[] = "Found {$duplicates->count()} codes with different names";

            if ($this->option('debug')) {
                $this->warn("\nCodes with different names:");
                foreach ($duplicates->take(5) as $code => $group) {
                    $names = $group->pluck('nama_giat')->unique();
                    $this->line("  Code {$code}: ".$names->implode(' | '));
                }
                if ($duplicates->count() > 5) {
                    $this->line('  ... and '.($duplicates->count() - 5).' more');
                }
            }
        }
    }

    /**
     * Analyze data quality for debugging
     */
    private function analyzeDataQuality($rawData, $processedData): void
    {
        $this->info("\n=== DATA QUALITY ANALYSIS ===");

        $totalRaw = $rawData->count();
        $totalProcessed = $processedData->count();
        $removed = $totalRaw - $totalProcessed;

        $this->line("Raw records: {$totalRaw}");
        $this->line("Processed records: {$totalProcessed}");
        $this->line("Duplicates removed: {$removed}");

        // Unique codes count
        $uniqueCodes = $processedData->pluck('kode_giat')->unique()->count();
        $this->line("Unique codes: {$uniqueCodes}");

        // Unique programs
        $uniquePrograms = $processedData->pluck('id_program')->unique()->count();
        $this->line("Unique programs referenced: {$uniquePrograms}");

        $this->line("===========================\n");
    }

    /**
     * Prepare data for insertion
     */
    private function prepareInsertData($data): array
    {
        $this->info('Preparing insert data...');

        $insertData = [];

        $bar = $this->output->createProgressBar($data->count());
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% - Preparing data');

        foreach ($data as $row) {
            // Validate program exists
            if (! $this->validateProgramExists($row->id_program)) {
                $this->notFoundPrograms[] = $row->id_program;
                $bar->advance();

                continue;
            }

            $insertData[] = [
                'id' => $row->id_giat,
                'id_program' => $row->id_program,
                'kode_kegiatan' => $row->kode_giat,
                'nama_kegiatan' => $row->nama_giat,
                'time_stamp' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $bar->advance();
        }

        $bar->finish();
        $this->line("\n");

        return $insertData;
    }

    /**
     * Validate if program exists in database
     */
    private function validateProgramExists($programId): bool
    {
        static $cache = [];

        if (! isset($cache[$programId])) {
            $cache[$programId] = DB::connection('mysql')
                ->table('program')
                ->where('id', $programId)
                ->exists();
        }

        return $cache[$programId];
    }

    /**
     * Insert data in optimized batches
     */
    private function insertDataInBatches(array $insertData): void
    {
        if (empty($insertData)) {
            $this->warn('No data to insert.');

            return;
        }

        $this->info('Inserting data in batches...');

        // Calculate optimal batch size based on column count
        $columnCount = count($insertData[0]);
        $maxPlaceholders = 65535;
        $batchSize = (int) floor(($maxPlaceholders / $columnCount) * 0.9);

        $chunks = array_chunk($insertData, $batchSize);

        $bar = $this->output->createProgressBar(count($chunks));
        $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% - %message%');
        $bar->setMessage('Starting batch insert...');

        try {
            foreach ($chunks as $index => $chunk) {
                $bar->setMessage('Batch '.($index + 1).' of '.count($chunks));

                DB::connection('mysql')->table('kegiatan')->upsert(
                    $chunk,
                    ['id'], // Unique key for upsert
                    [
                        'id_program',
                        'kode_kegiatan',
                        'nama_kegiatan',
                        'time_stamp',
                        'updated_at',
                    ]
                );

                $bar->advance();
            }

            $bar->setMessage('Batch insert completed');
            $bar->finish();
            $this->line("\n");

            $this->info('✓ Successfully processed '.count($insertData).' records in '.count($chunks).' batches');
        } catch (Exception $e) {
            $bar->finish();
            $this->line("\n");
            throw new Exception('Batch insert failed: '.$e->getMessage());
        }
    }

    /**
     * Get current record count from kegiatan table
     */
    private function getRecordCount(): int
    {
        return DB::connection('mysql')->table('kegiatan')->count();
    }

    /**
     * Handle dry run mode
     */
    private function handleDryRun(array $insertData): void
    {
        $this->warn("\n=== DRY RUN MODE - NO DATA WILL BE INSERTED ===\n");

        $this->info('Records that would be inserted: '.count($insertData));

        // Show sample data
        $sample = array_slice($insertData, 0, 10);
        $tableData = array_map(function ($item) {
            return [
                $item['id'],
                $item['id_program'],
                $item['kode_kegiatan'],
                substr($item['nama_kegiatan'], 0, 50).'...',
            ];
        }, $sample);

        $this->table(
            ['ID', 'ID Program', 'Kode', 'Nama Kegiatan'],
            $tableData
        );

        if (count($insertData) > 10) {
            $this->line('... and '.(count($insertData) - 10).' more records');
        }

        $this->info("\nRun without --dry-run flag to insert data");
    }

    private function showSummaryReport(int $beforeCount, int $afterCount, int $processedCount): void
    {
        $executionTime = round(microtime(true) - $this->startTime, 2);

        $this->info("\n╔════════════════════════════════════════╗");
        $this->info('║         SUMMARY REPORT                 ║');
        $this->info('╚════════════════════════════════════════╝');

        $this->line('Records before insert: '.number_format($beforeCount));
        $this->line('Records after insert:  '.number_format($afterCount));
        $this->line('New records added:     '.number_format($afterCount - $beforeCount));
        $this->line('Records updated:       '.number_format($processedCount - ($afterCount - $beforeCount)));
        $this->line('Total processed:       '.number_format($processedCount));
        $this->line("Execution time:        {$executionTime} seconds");

        if (! empty($this->notFoundPrograms)) {
            $this->line('Skipped (no program):  '.count(array_unique($this->notFoundPrograms)));
        }
    }

    private function showWarnings(): void
    {
        if (! empty($this->notFoundPrograms)) {
            $uniquePrograms = array_unique($this->notFoundPrograms);
            $this->warn("\n⚠ Warning: ".count($uniquePrograms).' program IDs not found:');

            foreach (array_slice($uniquePrograms, 0, 5) as $id) {
                $this->warn("  - Program ID: {$id}");
            }

            if (count($uniquePrograms) > 5) {
                $this->warn('  ... and '.(count($uniquePrograms) - 5).' more');
            }
        }

        if (! empty($this->duplicateWarnings)) {
            $this->warn("\n⚠ Data Quality Warnings:");
            foreach ($this->duplicateWarnings as $warning) {
                $this->warn("  - {$warning}");
            }
        }
    }
}
