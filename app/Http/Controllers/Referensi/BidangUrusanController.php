<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\Referensi\BidangUrusan;
use Illuminate\Http\Request;

class BidangUrusanController extends Controller
{
    public function index()
    {
        // $data = BidangUrusan::select('nama_urusan', 'kode_bidang_urusan', 'nama_bidang_urusan')
        //     ->distinct()
        //     ->orderBy('nama_urusan')
        //     ->get();

        // // return response()->json($data);
        // return view('referensi.bidang-urusan.index', compact('data'));

        $data = BidangUrusan::select('nama_urusan', 'kode_bidang_urusan', 'nama_bidang_urusan')
            ->distinct()
            ->orderBy('nama_urusan')
            ->orderBy('kode_bidang_urusan')
            ->get()
            ->groupBy('nama_urusan');

        // Pastikan $data adalah Collection kosong jika tidak ada hasil
        if ($data->isEmpty()) {
            $data = collect();
        }

        return view('referensi.bidang-urusan.index', compact('data'));
    }
}
