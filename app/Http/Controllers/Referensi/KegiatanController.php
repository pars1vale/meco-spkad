<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{

    public function index()
    {
        $data = DB::table('data_prog_keg')
            ->select(
                'id_urusan',
                'kode_urusan',
                'nama_urusan',
                'id_bidang_urusan',
                'kode_bidang_urusan',
                'nama_bidang_urusan',
                'id_program',
                'kode_program',
                'nama_program',
                'id_giat',
                'kode_giat',
                'nama_giat'
            )
            // ->distinct()
            ->orderBy('kode_urusan')
            ->orderBy('kode_bidang_urusan')
            ->orderBy('kode_program')
            ->orderBy('kode_giat')
            ->get()
            ->groupBy('id_urusan'); // dikelompokkan per urusan

        return view('referensi.kegiatan.index', compact('data'));
    }
}
