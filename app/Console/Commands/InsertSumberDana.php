<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InsertSumberDana extends Command
{
    protected $signature = 'insert:sumber-dana
        {--truncate : Kosongkan tabel sumber_dana terlebih dulu}
        {--tahun=2025 : Tahun anggaran snapshot}';

    protected $description = 'Mirror data sumber dana dari SIPD-RI ke tabel sumber_dana (SPKAD) - FULL CLONE';

    public function handle()
    {
        try {
            // 1. TRUNCATE (OPSIONAL)
            if ($this->option('truncate')) {
                DB::connection('mysql')
                    ->table('sumber_dana')
                    ->truncate();

                $this->warn('✓ Tabel sumber_dana dikosongkan.');
            }

            // 2. CEK KONEKSI SOURCE
            if (! $this->checkSourceConnection()) {
                $this->error('✗ Tidak dapat terhubung ke database data_sources.');

                return Command::FAILURE;
            }

            $this->info('✓ Koneksi ke SIPD-RI berhasil.');

            // 3. AMBIL DATA SOURCE (SEMUA KOLOM)
            $sourceData = $this->fetchSourceData();

            if ($sourceData->isEmpty()) {
                $this->info('⚠ Tidak ada data di SIPD source.');

                return Command::SUCCESS;
            }

            $this->info("✓ {$sourceData->count()} records ditemukan di SIPD-RI.");

            // 4. PREPARE DATA MIRROR (FULL CLONE)
            $insertData = $this->prepareInsertData($sourceData);

            // 5. UPSERT BATCH
            $this->insertDataInBatches($insertData);

            $this->newLine();
            $this->info('✓ Mirror SIPD-RI → SPKAD selesai dengan sempurna.');

            return Command::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('✗ ERROR: '.$e->getMessage());
            $this->error('Line: '.$e->getLine());

            return Command::FAILURE;
        }
    }

    // KONEKSI SOURCE
    private function checkSourceConnection(): bool
    {
        try {
            DB::connection('data_sources')->getPdo();

            return true;
        } catch (\Throwable) {
            return false;
        }
    }

    private function fetchSourceData()
    {
        return DB::connection('data_sources')
            // ->table('u405304318_yahukimo2025.data_sumber_dana')
            ->table('yahukimo2026_20260107.data_sumber_dana')
            ->select(
                'id',
                'created_at',
                'created_user',
                'id_daerah',
                'id_dana',
                'id_unik',
                'is_locked',
                'kode_dana',
                'nama_dana',
                'sumber_dana',
                'set_input',
                'status',
                'tahun',
                'updated_at',
                'active',
                'updated_user',
                'tahun_anggaran'
            )
            ->orderBy('id')
            ->get();
    }

    // NORMALIZE VALUE (HANDLE ARRAY/OBJECT/NULL)
    private function normalizeValue($value)
    {
        // Jika null, return null
        if (is_null($value)) {
            return null;
        }

        // Jika array atau object, convert ke JSON
        if (is_array($value) || is_object($value)) {
            return json_encode($value);
        }

        return $value;
    }

    // SIAPKAN DATA UNTUK MIRROR (CLONE EXACT)
    private function prepareInsertData($data): array
    {
        $tahunOption = (int) $this->option('tahun');
        $result = [];

        foreach ($data as $row) {
            $result[] = [
                'id' => $row->id,
                'created_at' => $this->normalizeValue($row->created_at),
                'created_user' => $this->normalizeValue($row->created_user),
                'id_daerah' => $this->normalizeValue($row->id_daerah),
                'id_dana' => $this->normalizeValue($row->id_dana),
                'id_unik' => $this->normalizeValue($row->id_unik),
                'is_locked' => $this->normalizeValue($row->is_locked),
                'kode_dana' => $this->normalizeValue($row->kode_dana),
                'nama_dana' => $this->normalizeValue($row->nama_dana),
                'sumber_dana' => $this->normalizeValue($row->sumber_dana),
                'set_input' => $this->normalizeValue($row->set_input),
                'status' => $this->normalizeValue($row->status),
                'tahun' => $this->normalizeValue($row->tahun) ?? $tahunOption,
                'tahun_anggaran' => $this->normalizeValue($row->tahun_anggaran) ?? $tahunOption,
                'updated_at' => $this->normalizeValue($row->updated_at),
                'active' => $this->normalizeValue($row->active) ?? 1,
                'updated_user' => $this->normalizeValue($row->updated_user) ?? 0,
            ];
        }

        return $result;
    }

    // INSERT / UPDATE BATCH
    private function insertDataInBatches(array $insertData): void
    {
        if (empty($insertData)) {
            return;
        }

        $columnCount = count($insertData[0]);
        $maxPlaceholders = 65535;
        $batchSize = (int) floor(($maxPlaceholders / $columnCount) * 0.9);

        $chunks = array_chunk($insertData, $batchSize);

        $this->newLine();
        $this->info('Memproses '.count($insertData).' records dalam '.count($chunks).' batch...');
        $this->output->progressStart(count($chunks));

        foreach ($chunks as $chunk) {
            DB::connection('mysql')
                ->table('sumber_dana')
                ->upsert(
                    $chunk,
                    ['id_dana', 'tahun_anggaran'],
                    [
                        'created_at',
                        'created_user',
                        'id_daerah',
                        'id_unik',
                        'is_locked',
                        'kode_dana',
                        'nama_dana',
                        'sumber_dana',
                        'set_input',
                        'status',
                        'tahun',
                        'updated_at',
                        'active',
                        'updated_user',
                    ]
                );

            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
        $this->info('✓ '.count($insertData).' records berhasil disinkronkan.');
    }
}
