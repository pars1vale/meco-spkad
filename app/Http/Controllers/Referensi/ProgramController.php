<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;

use App\Models\Referensi\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramController extends Controller
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
                'kode_program',
                'nama_program'
            )
            ->distinct()
            ->orderBy('kode_urusan')
            ->orderBy('kode_bidang_urusan')
            ->orderBy('kode_program')
            ->get()
            ->groupBy('id_urusan'); // Group berdasarkan urusan

        return view('referensi.program.index', compact('data'));
    }
}
