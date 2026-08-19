<?php

namespace App\Http\Controllers\Rkpd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rkpd\StoreRenjaRequest;
use App\Http\Requests\Rkpd\UpdateRenjaRequest;
use App\Services\Rkpd\RenjaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RenjaController extends Controller
{
    protected $renjaService;

    public function __construct(RenjaService $renjaService)
    {
        $this->renjaService = $renjaService;
    }

    public function index()
    {
        try {
            $viewData = $this->renjaService->getIndexData();

            return view('rkpd.renja.index', $viewData);
        } catch (\Exception $e) {
            Log::error('Error loading RENJA index: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal memuat halaman RENJA');
        }
    }

    public function store(StoreRenjaRequest $request)
    {
        try {
            $result = $this->renjaService->createRenja($request->validated());

            return redirect()->route('rkpd.renja.index')
                ->with('success', $result['message']);
        } catch (\Exception $e) {
            Log::error('Error storing renja: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $editData = $this->renjaService->getEditData($id);

            if (! $editData['subKegiatan']) {
                return redirect()->route('rkpd.renja.index')
                    ->with('error', 'Sub kegiatan tidak ditemukan');
            }

            return view('rkpd.renja.edit', $editData);
        } catch (\Exception $e) {
            Log::error('Error loading edit page: ' . $e->getMessage());

            return redirect()->route('rkpd.renja.index')
                ->with('error', 'Gagal memuat halaman edit');
        }
    }

    public function cetakRincian(Request $request, int $id)
    {
        $tanggalTtd = $request->query('tanggal');
        $nipTtd = $request->query('nip_ttd');

        if ($tanggalTtd && ! preg_match('/^\d{2}-\d{2}-\d{4}$/', $tanggalTtd)) {
            abort(422, 'Format tanggal tidak valid, gunakan dd-mm-yyyy');
        }

        if ($nipTtd && ! preg_match('/^\d{1,18}$/', $nipTtd)) {
            abort(422, 'NIP tidak valid, hanya boleh angka maksimal 18 digit');
        }

        $data = $this->renjaService->getCetakRincianData(
            $id,
            $tanggalTtd,
            $request->query('nama_ttd'),
            $nipTtd
        );

        if (! $data['subKegiatan']) {
            abort(404, 'Sub kegiatan tidak ditemukan');
        }

        return view('rkpd.renja.cetak-rincian', $data);
    }

    public function update(UpdateRenjaRequest $request, $id)
    {
        try {
            $result = $this->renjaService->updateRenja($id, $request->validated());

            return redirect()->route('rkpd.renja.index')
                ->with('success', $result['message']);
        } catch (\Exception $e) {
            Log::error('Error updating RENJA: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $result = $this->renjaService->deleteRenja($id);

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Error deleting RENJA: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function getSubKegiatanBySkpd(Request $request)
    {
        try {
            $idSkpd = $request->input('id_skpd');
            $tahunAnggaran = session('tahun_anggaran');
            if (! $tahunAnggaran) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tahun anggaran belum dipilih.',
                ], 422);
            }

            $result = $this->renjaService->getSubKegiatanBySkpd($idSkpd, $tahunAnggaran);

            return response()->json([
                'success' => true,
                'data' => $result['data'],
                'count' => $result['count'],
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting sub kegiatan: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function exportPdf(Request $request, $idSkpd)
    {
        try {
            $tahunAnggaran = (int) session('tahun_anggaran');
            $pdfData = $this->renjaService->getExportPdfData((int) $idSkpd, $tahunAnggaran);

            if (! $pdfData['skpd']) {
                return redirect()->back()->with('error', 'Data SKPD tidak ditemukan untuk tahun anggaran tersebut');
            }

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('rkpd.renja.pdf', $pdfData)
                ->setPaper('f4', 'landscape');

            $fileName = 'Renja_' . str_replace(' ', '_', $pdfData['skpd']->nama_skpd) . '_' . $tahunAnggaran . '.pdf';

            return $pdf->stream($fileName);
        } catch (\Exception $e) {
            Log::error('Error exporting RENJA PDF: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Gagal membuat PDF: ' . $e->getMessage());
        }
    }

    public function getData(Request $request)
    {
        try {
            $result = $this->renjaService->getDataTableData($request->all());

            return response()->json([
                'draw' => intval($request->draw ?? 1),
                'recordsTotal' => $result['recordsTotal'],
                'recordsFiltered' => $result['recordsFiltered'],
                'data' => $result['data'],
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting data: ' . $e->getMessage());

            return response()->json([
                'draw' => intval($request->draw ?? 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage(),
            ]);
        }
    }
}
