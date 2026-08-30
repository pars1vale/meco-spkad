<?php

namespace App\Http\Controllers\Rkpd\DokumenAnggaran;

use App\Http\Controllers\Controller;
use App\Services\Rkpd\DokumenAnggaran\RkaPendapatanService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RkaPendapatanController extends Controller
{
    public function __construct(protected RkaPendapatanService $service) {}

    public function index(Request $request)
    {

        $tahunAnggaran = (int) ($request->session()->get('tahun_anggaran') ?? date('Y'));
        $skpdList = $this->service->getAllSkpdForList($tahunAnggaran);

        return view('rkpd.dokumen-anggaran.rka-pendapatan.index', compact('skpdList', 'tahunAnggaran'));
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
        $data['idSkpd'] = $idSkpd;
        $data['tanggalTtdRaw'] = $request->input('tanggal_ttd');
        $data['namaTtdRaw'] = $request->input('nama_ttd');
        $data['nipTtdRaw'] = $request->input('nip_ttd');

        return view('rkpd.dokumen-anggaran.rka-pendapatan.pdf', $data);
    }

    /**
     * Tombol di pdf.blade.php memanggil endpoint ini (GET, query string sama
     * dengan yang dipakai untuk render preview) — menggantikan window.print().
     * Data & session tanggal_ttd/nama_ttd/nip_ttd harus dikirim ulang dari
     * form/link di halaman preview karena ini request baru (bukan reuse state).
     */
    public function unduhPdf(Request $request, int $idSkpd)
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

        $pdf = Pdf::loadView('rkpd.dokumen-anggaran.rka-pendapatan.pdf', $data + ['isDownload' => true])
            ->setPaper('a4', 'portrait');

        $namaFile = 'RKA-Pendapatan-' . $idSkpd . '-' . $tahunAnggaran . '.pdf';

        return $pdf->download($namaFile);
    }
}
