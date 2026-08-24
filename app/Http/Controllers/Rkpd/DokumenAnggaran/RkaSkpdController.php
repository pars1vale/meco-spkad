<?php

namespace App\Http\Controllers\Rkpd\DokumenAnggaran;

use App\Http\Controllers\Controller;
use App\Services\Rkpd\DokumenAnggaran\RkaSkpdService;
use Illuminate\Http\Request;

class RkaSkpdController extends Controller
{
    public function __construct(protected RkaSkpdService $service) {}

    public function index(Request $request)
    {
        $tahunAnggaran = (int) ($request->session()->get('tahun_anggaran') ?? date('Y'));
        $skpdList = $this->service->getAllSkpdForList($tahunAnggaran);

        return view('rkpd.dokumen-anggaran.rka-skpd.index', compact('skpdList', 'tahunAnggaran'));
    }

    public function ttdDefault(Request $request, int $idSkpd)
    {
        $tahunAnggaran = (int) ($request->session()->get('tahun_anggaran') ?? date('Y'));
        $ttdDefault = $this->service->getTtdDefault($idSkpd, $tahunAnggaran);

        return response()->json($ttdDefault);
    }

    public function cetak(Request $request, int $idSkpd)
    {
        $request->validate([
            'tanggal_ttd' => ['required', 'regex:/^\d{2}-\d{2}-\d{4}$/'],
            'nama_ttd' => ['nullable', 'string', 'max:150'],
            'nip_ttd' => ['nullable', 'regex:/^\d{1,18}$/'],
        ]);

        $tahunAnggaran = (int) ($request->session()->get('tahun_anggaran') ?? date('Y'));
        $data = $this->service->buildCetakData(
            $idSkpd,
            $tahunAnggaran,
            $request->input('tanggal_ttd'),
            $request->input('nama_ttd'),
            $request->input('nip_ttd')
        );

        $data['tahunAnggaran'] = $tahunAnggaran;
        $data['kabupaten'] = 'Yahukimo';
        $data['service'] = $this->service;

        return view('rkpd.dokumen-anggaran.rka-skpd.pdf', $data);
    }
}
