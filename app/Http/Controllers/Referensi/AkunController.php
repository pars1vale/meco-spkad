<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AkunController extends Controller
{
    public function index()
    {
        $data = DB::table('data_akun')
            ->select(
                'id_akun',
                'kode_akun',
                'nama_akun',
                'pendapatan',
                'belanja',
                'pembiayaan'
            )
            ->orderBy('kode_akun')
            ->get();
        return view('referensi.akun.index', compact('data'));
    }
}
