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

        // Ambil data dan kelompokkan berdasarkan kombinasi kode + nama
        // untuk mendeteksi kasus ID sama tapi nama berbeda
        $rawData = DB::connection('data_sources')
            ->table('u405304318_yahukimo2025.data_prog_keg')
            ->select('nama_urusan', 'nama_bidang_urusan', 'id_program', 'nama_program', 'id_giat', 'kode_giat', 'nama_giat')
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

        $this->info("Total raw data: " . $rawData->count());

        // Debug: Analisis kode yang memiliki nama berbeda
        $this->analyzeDuplicateCodes($rawData);

        // Kelompokkan berdasarkan id_giat saja
        // Karena ternyata data source sudah memberikan ID berbeda untuk nama berbeda
        $processedData = $rawData->unique(function ($item) {
            // Unique berdasarkan id_giat saja
            return $item->id_giat;
        });

        $this->info("Data setelah unique by ID: " . $processedData->count());

        // Debug: Analisis data setelah unique
        $this->analyzeProcessedData($processedData);

        $data = $processedData->groupBy('nama_program');

        $totalData = $data->flatten(1)->count();
        $this->info($totalData . " Data Kegiatan ditemukan.");

        // Tampilkan contoh data untuk verifikasi
        $this->info("Contoh data yang ditemukan:");
        $sampel = $data->flatten(1)->take(10);
        foreach ($sampel as $item) {
            $this->line("ID: {$item->id_giat}, Kode: {$item->kode_giat}, Nama: {$item->nama_giat}");
        }

        $insertData = [];
        $notFoundProgram = [];

        foreach ($data as $namaProgram => $kegiatanList) {
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
                    'time_stamp' => now(),
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
            // Debug sebelum upsert
            $this->debugBeforeUpsert($insertData);

            $beforeCount = DB::connection('mysql')->table('kegiatan')->count();
            $this->info("Jumlah data sebelum upsert: {$beforeCount}");

            try {
                DB::connection('mysql')->table('kegiatan')->upsert(
                    $insertData,
                    ['id'],
                    ['id_program', 'kode_kegiatan', 'nama_kegiatan', 'time_stamp', 'updated_at']
                );

                $afterCount = DB::connection('mysql')->table('kegiatan')->count();
                $this->info("Jumlah data setelah upsert: {$afterCount}");
                $this->info("Data baru yang ditambahkan: " . ($afterCount - $beforeCount));

                // Debug data setelah upsert
                $this->debugAfterUpsert($insertData);

                $this->info(count($insertData) . " data berhasil diproses untuk upsert ke tabel kegiatan.");
            } catch (\Exception $e) {
                $this->error("Error saat upsert: " . $e->getMessage());
            }
        }

        $totalKegiatan = DB::connection('mysql')->table('kegiatan')->count();
        $this->info("Total kegiatan di database: {$totalKegiatan}");

        // Tampilkan statistik kode yang sama
        $this->showDuplicateCodesStats();
    }

    /**
     * Analisis kode kegiatan yang memiliki nama berbeda di raw data
     */
    private function analyzeDuplicateCodes($rawData)
    {
        $this->info("\n=== ANALISIS KODE DUPLIKAT DI RAW DATA ===");

        // Group berdasarkan kode untuk melihat nama yang berbeda
        $groupedByCodes = $rawData->groupBy('kode_giat');

        $duplicateCodes = $groupedByCodes->filter(function ($group) {
            // Cari kode yang memiliki lebih dari 1 nama unik
            return $group->pluck('nama_giat')->unique()->count() > 1;
        });

        $this->info("Kode dengan nama berbeda: " . $duplicateCodes->count());

        foreach ($duplicateCodes as $kode => $group) {
            $uniqueNames = $group->pluck('nama_giat')->unique();
            $uniqueIds = $group->pluck('id_giat')->unique();

            $this->warn("Kode {$kode} ({$uniqueNames->count()} nama, {$uniqueIds->count()} ID unik):");
            foreach ($uniqueNames as $nama) {
                $items = $group->where('nama_giat', $nama);
                $ids = $items->pluck('id_giat')->unique();
                $this->line("  - {$nama} (ID: " . $ids->implode(', ') . ", Records: {$items->count()})");
            }
            $this->line("");
        }

        // Statistik keseluruhan
        $this->info("STATISTIK RAW DATA:");
        $this->line("- Total kode unik: " . $groupedByCodes->count());
        $this->line("- Kode dengan nama ganda: " . $duplicateCodes->count());
        $this->line("- Total kombinasi unik (kode+nama): " . $rawData->unique(function ($item) {
            return $item->kode_giat . '|' . $item->nama_giat;
        })->count());
    }

    /**
     * Analisis data setelah unique processing
     */
    private function analyzeProcessedData($processedData)
    {
        $this->info("\n=== ANALISIS DATA SETELAH UNIQUE ===");

        // Group berdasarkan kode untuk melihat nama yang berbeda
        $groupedByCodes = $processedData->groupBy('kode_giat');

        $duplicateCodes = $groupedByCodes->filter(function ($group) {
            return $group->pluck('nama_giat')->unique()->count() > 1;
        });

        $this->info("Kode dengan nama berbeda setelah unique: " . $duplicateCodes->count());

        foreach ($duplicateCodes as $kode => $group) {
            $uniqueNames = $group->pluck('nama_giat')->unique();
            $this->line("Kode {$kode} ({$uniqueNames->count()} nama):");
            foreach ($group as $item) {
                $this->line("  - ID: {$item->id_giat}, Nama: {$item->nama_giat}");
            }
            $this->line("");
        }
    }

    /**
     * Debug data sebelum upsert
     */
    private function debugBeforeUpsert($insertData)
    {
        $this->info("\n=== DEBUG SEBELUM UPSERT ===");
        $this->info("Jumlah data yang akan di-insert: " . count($insertData));

        // Cek apakah ada duplikat ID dalam insertData
        $ids = collect($insertData)->pluck('id');
        $duplicateIds = $ids->duplicates();

        if ($duplicateIds->count() > 0) {
            $this->warn("DUPLIKAT ID DITEMUKAN DALAM INSERT DATA:");
            foreach ($duplicateIds as $duplicateId) {
                $duplicateItems = collect($insertData)->where('id', $duplicateId);
                $this->warn("ID {$duplicateId}:");
                foreach ($duplicateItems as $item) {
                    $this->warn("  - {$item['kode_kegiatan']}: {$item['nama_kegiatan']}");
                }
            }
        }

        // Debug semua kode yang memiliki nama berbeda dalam insertData
        $groupedByCode = collect($insertData)->groupBy('kode_kegiatan');
        $duplicateCodeData = $groupedByCode->filter(function ($group) {
            return collect($group)->pluck('nama_kegiatan')->unique()->count() > 1;
        });

        if ($duplicateCodeData->count() > 0) {
            $this->info("\nKODE DENGAN NAMA BERBEDA YANG AKAN DI-INSERT:");
            foreach ($duplicateCodeData as $kode => $group) {
                $this->line("Kode {$kode}:");
                foreach ($group as $item) {
                    $this->line("  - ID: {$item['id']}, Nama: {$item['nama_kegiatan']}");
                }
                $this->line("");
            }
        } else {
            $this->info("Tidak ada kode dengan nama berbeda dalam data yang akan di-insert.");
        }
    }

    /**
     * Debug data setelah upsert
     */
    private function debugAfterUpsert($insertData)
    {
        $this->info("\n=== DEBUG SETELAH UPSERT ===");

        // Ambil semua kode yang memiliki nama berbeda dari insertData
        $groupedByCode = collect($insertData)->groupBy('kode_kegiatan');
        $duplicateCodeData = $groupedByCode->filter(function ($group) {
            return collect($group)->pluck('nama_kegiatan')->unique()->count() > 1;
        });

        if ($duplicateCodeData->count() > 0) {
            foreach ($duplicateCodeData as $kode => $group) {
                $this->line("Mengecek kode {$kode} di database:");

                $dbData = DB::connection('mysql')
                    ->table('kegiatan')
                    ->where('kode_kegiatan', $kode)
                    ->get();

                $this->line("Jumlah di database: " . $dbData->count());
                foreach ($dbData as $item) {
                    $this->line("  - ID: {$item->id}, Nama: {$item->nama_kegiatan}");
                }

                $expectedCount = collect($group)->count();
                $actualCount = $dbData->count();

                if ($expectedCount !== $actualCount) {
                    $this->warn("MISMATCH! Expected: {$expectedCount}, Actual: {$actualCount}");
                }

                $this->line("");
            }
        }
    }

    /**
     * Tampilkan statistik akhir
     */
    private function showDuplicateCodesStats()
    {
        $this->info("\n=== ANALISIS DATA FINAL DI DATABASE ===");

        // 1. Cek kode yang memiliki nama berbeda di database
        $groupedByCodes = DB::connection('mysql')
            ->table('kegiatan')
            ->select('kode_kegiatan', DB::raw('COUNT(*) as total'), DB::raw('GROUP_CONCAT(nama_kegiatan SEPARATOR " | ") as nama_list'))
            ->groupBy('kode_kegiatan')
            ->having('total', '>', 1)
            ->orderBy('kode_kegiatan')
            ->get();

        if ($groupedByCodes->count() > 0) {
            $this->warn("Kode kegiatan yang memiliki nama berbeda di database:");
            foreach ($groupedByCodes as $item) {
                $this->warn("Kode {$item->kode_kegiatan} ({$item->total} variasi): {$item->nama_list}");
            }
        } else {
            $this->info("Tidak ada kode kegiatan yang memiliki nama berbeda di database.");
        }

        // 2. Statistik keseluruhan database
        $totalCodes = DB::connection('mysql')
            ->table('kegiatan')
            ->distinct('kode_kegiatan')
            ->count();

        $totalRecords = DB::connection('mysql')->table('kegiatan')->count();

        $this->info("\n=== STATISTIK DATABASE ===");
        $this->line("- Total kode unik di database: {$totalCodes}");
        $this->line("- Total records di database: {$totalRecords}");
        $this->line("- Kode dengan nama ganda: " . $groupedByCodes->count());

        if ($totalRecords > $totalCodes) {
            $this->line("- Ada " . ($totalRecords - $totalCodes) . " kode dengan nama berbeda");
        }
    }
}
