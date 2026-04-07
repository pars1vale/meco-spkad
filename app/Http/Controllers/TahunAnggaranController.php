<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TahunAnggaranController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Tampilkan halaman dengan modal pilih tahun anggaran
    public function pilih()
    {
        $tahunList = $this->getTahunList();

        return view('auth.pilih-tahun', compact('tahunList'));
    }

    // Simpan tahun anggaran ke session
    public function simpan(Request $request)
    {
        $request->validate([
            'tahun_anggaran' => ['required', 'integer', 'min:2020', 'max:2099'],
        ], [
            'tahun_anggaran.required' => 'Tahun anggaran wajib dipilih.',
        ]);

        session(['tahun_anggaran' => $request->tahun_anggaran]);

        return redirect()->intended('/home');
    }

    // Ganti tahun anggaran (bisa dipanggil dari navbar)
    public function ganti(Request $request)
    {
        $request->validate([
            'tahun_anggaran' => ['required', 'integer', 'min:2020', 'max:2099'],
        ]);

        session(['tahun_anggaran' => $request->tahun_anggaran]);

        return back()->with('success', 'Tahun anggaran berhasil diganti.');
    }

    // Generate list tahun (5 tahun ke belakang s/d tahun depan)
    private function getTahunList()
    {
        $tahunSekarang = (int) date('Y');
        $tahunList = [];

        for ($i = $tahunSekarang - 4; $i <= $tahunSekarang + 1; $i++) {
            $tahunList[] = $i;
        }

        return array_reverse($tahunList);
    }
}
