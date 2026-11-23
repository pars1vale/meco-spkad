<?php

namespace App\Http\Controllers\Rkpd\DokumenAnggaran;

use App\Http\Controllers\Controller;
use App\Services\Rkpd\DokumenAnggaran\RkaRekapBelanjaService;
use App\Support\SkpdResolver;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class RkaRekapBelanjaController extends Controller
{
    public function __construct(protected RkaRekapBelanjaService $service) {}

    public function index(Request $request)
    {
        $tahunAnggaran = (int) ($request->session()->get('tahun_anggaran') ?? date('Y'));
        $skpdList = $this->service->getAllSkpdForList($tahunAnggaran);

        return view('rkpd.dokumen-anggaran.rka-rekapbelanja.index', compact('skpdList', 'tahunAnggaran'));
    }

    public function ttdDefault(Request $request, int $idSkpd)
    {
        $tahunAnggaran = (int) ($request->session()->get('tahun_anggaran') ?? date('Y'));
        $idResolved = SkpdResolver::resolveIndukId($idSkpd, $tahunAnggaran);

        return response()->json($this->service->getTtdDefault($idResolved, $tahunAnggaran));
    }

    public function cetak(Request $request, int $idSkpd)
    {
        $request->validate([
            'tanggal_ttd' => ['required', 'regex:/^\d{2}-\d{2}-\d{4}$/'],
            'nama_ttd' => ['nullable', 'string', 'max:150'],
            'nip_ttd' => ['nullable', 'regex:/^\d{1,18}$/'],
        ]);

        $data = $this->buildCetakData($request, $idSkpd);

        return view('rkpd.dokumen-anggaran.rka-rekapbelanja.pdf', $data);
    }

    public function unduhPdf(Request $request, int $idSkpd)
    {
        $request->validate([
            'tanggal_ttd' => ['required', 'regex:/^\d{2}-\d{2}-\d{4}$/'],
            'nama_ttd' => ['nullable', 'string', 'max:150'],
            'nip_ttd' => ['nullable', 'regex:/^\d{1,18}$/'],
        ]);

        $data = $this->buildCetakData($request, $idSkpd) + ['isDownload' => true];

        $pdf = Pdf::loadView('rkpd.dokumen-anggaran.rka-rekapbelanja.pdf', $data);
        // ->setPaper('a4', 'landscape');

        $kodeSkpd = $data['kodeSkpd'] ?? $idSkpd;
        $namaFile = 'RKA-Rekap-Belanja-' . $kodeSkpd . '-' . $data['tahunAnggaran'] . '.pdf';

        return $pdf->download($namaFile);
    }

    private function buildCetakData(Request $request, int $idSkpd): array
    {
        $tahunAnggaran = (int) ($request->session()->get('tahun_anggaran') ?? date('Y'));
        $idResolved = SkpdResolver::resolveIndukId($idSkpd, $tahunAnggaran);

        $identity = $this->service->getSkpdIdentity($idResolved, $tahunAnggaran);
        if (!$identity) {
            abort(404, 'SKPD tidak ditemukan untuk tahun anggaran ini.');
        }

        $rekap = $this->service->getRekapBelanja($idResolved, $tahunAnggaran);

        return [
            'rows' => $rekap['rows'],
            'grandTotal' => $rekap['grand_total'],
            'organisasi' => trim(($identity->kode_skpd ?? '') . ' ' . ($identity->nama_skpd ?? '')),
            'kodeSkpd' => $identity->kode_skpd,
            'kabupaten' => 'Yahukimo',
            'tahunAnggaran' => $tahunAnggaran,
            'namaUnit' => $identity->nama_skpd,
            'idSkpd' => $idSkpd,
            'tanggalTtdRaw' => $request->input('tanggal_ttd'),
            'namaTtdRaw' => $request->input('nama_ttd'),
            'nipTtdRaw' => $request->input('nip_ttd'),
            'tanggalTtd' => $request->input('tanggal_ttd')
                ? \Carbon\Carbon::createFromFormat('d-m-Y', $request->input('tanggal_ttd'))->translatedFormat('d F Y')
                : now()->translatedFormat('d F Y'),
            'namaTtd' => $request->input('nama_ttd'),
            'nipTtd' => $request->input('nip_ttd'),
        ];
    }
}
