<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SumberDanaController extends Controller
{
    public function index()
    {
        $data = DB::table('data_sumber_dana')
            ->select(
                'id_dana',
                'kode_dana',
                'nama_dana',
                'sumber_dana',
                'set_input'
            )
            ->orderBy('kode_dana')
            ->get();
        return view('referensi.sumber-dana.index', compact('data'));
    }
}
