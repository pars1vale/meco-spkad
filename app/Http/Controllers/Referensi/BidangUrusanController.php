<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\Referensi\BidangUrusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BidangUrusanController extends Controller
{
    // public function index()
    // {

    //     $data = DB::table('data_prog_keg')
    //         ->select('nama_urusan', 'kode_bidang_urusan', 'nama_bidang_urusan')
    //         ->distinct()
    //         ->orderBy('nama_urusan')
    //         ->get()
    //         ->groupBy('nama_urusan');

    //     // Pastikan $data adalah Collection kosong jika tidak ada hasil
    //     if ($data->isEmpty()) {
    //         $data = collect();
    //     }

    //     return view('referensi.bidang-urusan.index', compact('data'));
    // }

    public function index()
    {
        $data = collect();

        try {
            // Menggunakan eager loading untuk menghindari N+1 problem
            $bidangUrusans = BidangUrusan::with('urusan')
                ->join('urusan', 'bidang_urusan.id_urusan', '=', 'urusan.id')
                ->select([
                    'bidang_urusan.*',
                    'urusan.nama_urusan',
                    'urusan.kode_urusan'
                ])
                ->orderBy('urusan.nama_urusan', 'asc')
                ->orderBy('bidang_urusan.kode_bidang_urusan', 'asc') // Order by kode dulu
                ->orderBy('bidang_urusan.nama_bidang_urusan', 'asc') // Kemudian by nama
                ->get();

            // Group by nama urusan
            $data = $bidangUrusans->groupBy('nama_urusan')->map(function ($group) {
                // Sort setiap group berdasarkan kode_bidang_urusan dan nama_bidang_urusan
                return $group->sortBy([
                    ['kode_bidang_urusan', 'asc'],
                    ['nama_bidang_urusan', 'asc']
                ]);
            });

            // Tambahkan statistik untuk debugging/monitoring
            // $stats = [
            //     'total_bidang_urusan' => $bidangUrusans->count(),
            //     'total_urusan' => $data->count(),
            //     'duplicate_codes' => $this->getDuplicateCodesStats($bidangUrusans)
            // ];

            // // Log statistik untuk monitoring
            // if ($stats['duplicate_codes']->count() > 0) {
            //     \Log::info('Bidang Urusan dengan kode duplikat ditemukan:', [
            //         'duplicate_count' => $stats['duplicate_codes']->count(),
            //         'duplicates' => $stats['duplicate_codes']->toArray()
            //     ]);
            // }
        } catch (\Exception $e) {
            \Log::error('Error fetching bidang urusan data: ' . $e->getMessage());

            // Return empty data dengan error message untuk ditampilkan di view
            $data = collect();
            $stats = [
                'total_bidang_urusan' => 0,
                'total_urusan' => 0,
                'duplicate_codes' => collect(),
                'error' => 'Gagal memuat data bidang urusan: ' . $e->getMessage()
            ];
        }

        return view('referensi.bidang-urusan.index', compact('data'));
    }
}
