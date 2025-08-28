<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class InsertBidangUrusan extends Command
{
    protected $signature = 'insert:bidang-urusan {--truncate : Kosongkan tabel bidang_urusan terlebih dulu}';
    protected $description = 'Insert bidang urusan data from SIPD-RI into the bidang_urusan (SPKAD) table';

    public function handle()
    {
        if ($this->option('truncate')) {
            DB::connection('mysql')->table('bidang_urusan')->truncate();
            $this->warn('Truncated bidang_urusan table.');
        }

        // Ambil data dan kelompokkan berdasarkan kombinasi kode + nama
        // untuk mendeteksi kasus ID sama tapi nama berbeda
        $rawData = DB::connection('data_sources')
            ->table('u405304318_yahukimo2025.data_prog_keg')
            ->select('nama_urusan', 'id_bidang_urusan', 'kode_bidang_urusan', 'nama_bidang_urusan', 'id_urusan')
            ->whereNotNull('id_bidang_urusan')
            ->whereNotNull('kode_bidang_urusan')
            ->whereNotNull('nama_bidang_urusan')
            ->orderBy('nama_urusan')
            ->orderBy('kode_bidang_urusan')
            ->orderBy('nama_bidang_urusan')
            ->get();

        $this->info("Total raw data: " . $rawData->count());

        // Debug: Analisis kode yang memiliki nama berbeda
        $this->analyzeDuplicateCodes($rawData);

        // Kelompokkan berdasarkan id_bidang_urusan saja
        // Karena ternyata data source sudah memberikan ID berbeda untuk nama berbeda
        $processedData = $rawData->unique(function ($item) {
            // Unique berdasarkan id_bidang_urusan saja
            return $item->id_bidang_urusan;
        });

        $this->info("Data setelah unique by ID: " . $processedData->count());

        // Debug: Analisis data setelah unique
        $this->analyzeProcessedData($processedData);

        $data = $processedData->groupBy('nama_urusan');

        $totalData = $data->flatten(1)->count();
        $this->info($totalData . " Data Bidang Urusan ditemukan.");

        // Tampilkan contoh data untuk verifikasi
        $this->info("Contoh data yang ditemukan:");
        $sampel = $data->flatten(1)->take(10);
        foreach ($sampel as $item) {
            $this->line("ID: {$item->id_bidang_urusan}, Kode: {$item->kode_bidang_urusan}, Nama: {$item->nama_bidang_urusan}");
        }

        $insertData = [];
        $notFoundUrusan = [];

        foreach ($data as $namaUrusan => $bidangUrusanList) {
            foreach ($bidangUrusanList as $row) {
                // Cek apakah id_urusan ada di tabel urusan
                $urusanExists = DB::connection('mysql')
                    ->table('urusan')
                    ->where('id', $row->id_urusan)
                    ->exists();

                if (!$urusanExists) {
                    $notFoundUrusan[] = $row->id_urusan;
                    continue;
                }

                $insertData[] = [
                    'id' => $row->id_bidang_urusan,
                    'id_urusan' => $row->id_urusan,
                    'kode_bidang_urusan' => $row->kode_bidang_urusan,
                    'nama_bidang_urusan' => $row->nama_bidang_urusan,
                    'time_stamp' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (!empty($notFoundUrusan)) {
            $this->warn('ID Urusan berikut tidak ditemukan:');
            foreach (array_unique($notFoundUrusan) as $id) {
                $this->warn("- ID: {$id}");
            }
        }

        if (!empty($insertData)) {
            // Debug sebelum upsert
            $this->debugBeforeUpsert($insertData);

            $beforeCount = DB::connection('mysql')->table('bidang_urusan')->count();
            $this->info("Jumlah data sebelum upsert: {$beforeCount}");

            try {
                DB::connection('mysql')->table('bidang_urusan')->upsert(
                    $insertData,
                    ['id'],
                    ['id_urusan', 'kode_bidang_urusan', 'nama_bidang_urusan', 'time_stamp', 'updated_at']
                );

                $afterCount = DB::connection('mysql')->table('bidang_urusan')->count();
                $this->info("Jumlah data setelah upsert: {$afterCount}");
                $this->info("Data baru yang ditambahkan: " . ($afterCount - $beforeCount));

                // Debug data setelah upsert
                $this->debugAfterUpsert($insertData);

                $this->info(count($insertData) . " data berhasil diproses untuk upsert ke tabel bidang_urusan.");
            } catch (\Exception $e) {
                $this->error("Error saat upsert: " . $e->getMessage());
            }
        }

        $totalBidangUrusan = DB::connection('mysql')->table('bidang_urusan')->count();
        $this->info("Total bidang urusan di database: {$totalBidangUrusan}");

        // Tampilkan statistik kode yang sama
        $this->showDuplicateCodesStats();
    }

    /**
     * Analisis kode bidang urusan yang memiliki nama berbeda di raw data
     */
    private function analyzeDuplicateCodes($rawData)
    {
        $this->info("\n=== ANALISIS KODE DUPLIKAT DI RAW DATA ===");

        // Group berdasarkan kode untuk melihat nama yang berbeda
        $groupedByCodes = $rawData->groupBy('kode_bidang_urusan');

        $duplicateCodes = $groupedByCodes->filter(function ($group) {
            // Cari kode yang memiliki lebih dari 1 nama unik
            return $group->pluck('nama_bidang_urusan')->unique()->count() > 1;
        });

        $this->info("Kode dengan nama berbeda: " . $duplicateCodes->count());

        foreach ($duplicateCodes as $kode => $group) {
            $uniqueNames = $group->pluck('nama_bidang_urusan')->unique();
            $uniqueIds = $group->pluck('id_bidang_urusan')->unique();

            $this->warn("Kode {$kode} ({$uniqueNames->count()} nama, {$uniqueIds->count()} ID unik):");
            foreach ($uniqueNames as $nama) {
                $items = $group->where('nama_bidang_urusan', $nama);
                $ids = $items->pluck('id_bidang_urusan')->unique();
                $this->line("  - {$nama} (ID: " . $ids->implode(', ') . ", Records: {$items->count()})");
            }
            $this->line("");
        }

        // Statistik keseluruhan
        $this->info("STATISTIK RAW DATA:");
        $this->line("- Total kode unik: " . $groupedByCodes->count());
        $this->line("- Kode dengan nama ganda: " . $duplicateCodes->count());
        $this->line("- Total kombinasi unik (kode+nama): " . $rawData->unique(function ($item) {
            return $item->kode_bidang_urusan . '|' . $item->nama_bidang_urusan;
        })->count());
    }

    /**
     * Analisis data setelah unique processing
     */
    private function analyzeProcessedData($processedData)
    {
        $this->info("\n=== ANALISIS DATA SETELAH UNIQUE ===");

        // Group berdasarkan kode untuk melihat nama yang berbeda
        $groupedByCodes = $processedData->groupBy('kode_bidang_urusan');

        $duplicateCodes = $groupedByCodes->filter(function ($group) {
            return $group->pluck('nama_bidang_urusan')->unique()->count() > 1;
        });

        $this->info("Kode dengan nama berbeda setelah unique: " . $duplicateCodes->count());

        foreach ($duplicateCodes as $kode => $group) {
            $uniqueNames = $group->pluck('nama_bidang_urusan')->unique();
            $this->line("Kode {$kode} ({$uniqueNames->count()} nama):");
            foreach ($group as $item) {
                $this->line("  - ID: {$item->id_bidang_urusan}, Nama: {$item->nama_bidang_urusan}");
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
                    $this->warn("  - {$item['kode_bidang_urusan']}: {$item['nama_bidang_urusan']}");
                }
            }
        }

        // Debug semua kode yang memiliki nama berbeda dalam insertData
        $groupedByCode = collect($insertData)->groupBy('kode_bidang_urusan');
        $duplicateCodeData = $groupedByCode->filter(function ($group) {
            return collect($group)->pluck('nama_bidang_urusan')->unique()->count() > 1;
        });

        if ($duplicateCodeData->count() > 0) {
            $this->info("\nKODE DENGAN NAMA BERBEDA YANG AKAN DI-INSERT:");
            foreach ($duplicateCodeData as $kode => $group) {
                $this->line("Kode {$kode}:");
                foreach ($group as $item) {
                    $this->line("  - ID: {$item['id']}, Nama: {$item['nama_bidang_urusan']}");
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
        $groupedByCode = collect($insertData)->groupBy('kode_bidang_urusan');
        $duplicateCodeData = $groupedByCode->filter(function ($group) {
            return collect($group)->pluck('nama_bidang_urusan')->unique()->count() > 1;
        });

        if ($duplicateCodeData->count() > 0) {
            foreach ($duplicateCodeData as $kode => $group) {
                $this->line("Mengecek kode {$kode} di database:");

                $dbData = DB::connection('mysql')
                    ->table('bidang_urusan')
                    ->where('kode_bidang_urusan', $kode)
                    ->get();

                $this->line("Jumlah di database: " . $dbData->count());
                foreach ($dbData as $item) {
                    $this->line("  - ID: {$item->id}, Nama: {$item->nama_bidang_urusan}");
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
            ->table('bidang_urusan')
            ->select('kode_bidang_urusan', DB::raw('COUNT(*) as total'), DB::raw('GROUP_CONCAT(nama_bidang_urusan SEPARATOR " | ") as nama_list'))
            ->groupBy('kode_bidang_urusan')
            ->having('total', '>', 1)
            ->orderBy('kode_bidang_urusan')
            ->get();

        if ($groupedByCodes->count() > 0) {
            $this->warn("Kode bidang urusan yang memiliki nama berbeda di database:");
            foreach ($groupedByCodes as $item) {
                $this->warn("Kode {$item->kode_bidang_urusan} ({$item->total} variasi): {$item->nama_list}");
            }
        } else {
            $this->info("Tidak ada kode bidang urusan yang memiliki nama berbeda di database.");
        }

        // 2. Statistik keseluruhan database
        $totalCodes = DB::connection('mysql')
            ->table('bidang_urusan')
            ->distinct('kode_bidang_urusan')
            ->count();

        $totalRecords = DB::connection('mysql')->table('bidang_urusan')->count();

        $this->info("\n=== STATISTIK DATABASE ===");
        $this->line("- Total kode unik di database: {$totalCodes}");
        $this->line("- Total records di database: {$totalRecords}");
        $this->line("- Kode dengan nama ganda: " . $groupedByCodes->count());

        if ($totalRecords > $totalCodes) {
            $this->line("- Ada " . ($totalRecords - $totalCodes) . " kode dengan nama berbeda");
        }
    }
}
