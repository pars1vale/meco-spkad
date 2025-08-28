<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SubKegiatanController extends Controller
{
    public function index()
    {
        $data = DB::table('data_prog_keg') // sesuaikan dengan nama tabel sebenarnya
            ->select(
                'id_urusan',
                'kode_urusan',
                'nama_urusan',
                'id_bidang_urusan',
                'kode_bidang_urusan',
                'nama_bidang_urusan',
                'kode_program',
                'nama_program',
                'kode_giat',
                'nama_giat',
                'kode_sub_giat',
                'nama_sub_giat'
            )
            ->distinct()
            ->orderBy('kode_urusan')
            ->orderBy('kode_bidang_urusan')
            ->orderBy('kode_program')
            ->orderBy('kode_giat')
            ->orderBy('kode_sub_giat')
            ->get()
            ->groupBy('id_urusan'); // Group berdasarkan urusan utama

        return view('referensi.sub-kegiatan.index', compact('data'));
    }
}
