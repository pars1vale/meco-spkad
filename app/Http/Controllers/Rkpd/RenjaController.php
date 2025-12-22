<?php

namespace App\Http\Controllers\Rkpd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Rkpd\RenjaService;
use App\Http\Requests\Rkpd\StoreRenjaRequest;
use App\Http\Requests\Rkpd\StoreRincianRequest;
use App\Http\Requests\Rkpd\StorePaketBelanjaRequest;
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

    /**
     * Get sub kegiatan berdasarkan SKPD untuk dropdown
     */
    public function getSubKegiatanBySkpd(Request $request)
    {
        try {
            $idSkpd = $request->input('id_skpd');
            $tahunAnggaran = $request->input('tahun_anggaran', 2025);

            $result = $this->renjaService->getSubKegiatanBySkpd($idSkpd, $tahunAnggaran);

            return response()->json([
                'success' => true,
                'data' => $result['data'],
                'count' => $result['count']
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting sub kegiatan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store RENJA baru dengan sumber dana dan indikator
     */
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

    /**
     * Get data untuk DataTables dengan grouping
     */
    public function getData(Request $request)
    {
        try {
            $result = $this->renjaService->getDataTableData($request->all());

            return response()->json([
                'draw' => intval($request->draw ?? 1),
                'recordsTotal' => $result['recordsTotal'],
                'recordsFiltered' => $result['recordsFiltered'],
                'data' => $result['data']
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting data: ' . $e->getMessage());
            return response()->json([
                'draw' => intval($request->draw ?? 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Tampilkan halaman rincian sub kegiatan
     */
    public function showRincian($id)
    {
        try {
            $data = $this->renjaService->getRincianSubKegiatan($id);

            if (!$data['subKegiatan']) {
                return redirect()->route('rkpd.renja.index')
                    ->with('error', 'Data sub kegiatan tidak ditemukan');
            }

            return view('rkpd.renja.rincian', $data);
        } catch (\Exception $e) {
            Log::error('Error showing rincian: ' . $e->getMessage());
            return redirect()->route('rkpd.renja.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Get akun rekening berdasarkan jenis belanja
     */
    public function getAkunByJenisBelanja(Request $request)
    {
        try {
            $jenisBelanja = $request->input('jenis_bl');
            $tahunAnggaran = $request->input('tahun_anggaran', 2025);

            $result = $this->renjaService->getAkunByJenisBelanja($jenisBelanja, $tahunAnggaran);

            if (!$result['success']) {
                return response()->json($result, 400);
            }

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Error loading akun: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get detail akun berdasarkan ID
     */
    public function getDetailAkun(Request $request)
    {
        try {
            $akunId = $request->akun_id;
            $result = $this->renjaService->getDetailAkun($akunId);

            if (!$result['success']) {
                return response()->json($result, 404);
            }

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Error getting detail akun: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan'
            ], 500);
        }
    }

    /**
     * Get list paket belanja yang sudah ada
     */
    public function getPaketBelanjaList(Request $request)
    {
        try {
            $params = [
                'id_rinci_sub_bl' => $request->input('id_rinci_sub_bl'),
                'tipe_paket' => $request->input('tipe_paket'),
                'jenis_bl' => $request->input('jenis_bl')
            ];

            if (!$params['id_rinci_sub_bl'] || !$params['tipe_paket']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parameter tidak lengkap',
                    'data' => []
                ], 400);
            }

            $result = $this->renjaService->getPaketBelanjaList($params);

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Error getting paket list: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Store paket/kelompok belanja baru
     */
    public function storePaketBelanja(StorePaketBelanjaRequest $request)
    {
        try {
            $result = $this->renjaService->createPaketBelanja($request->validated());

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Error storing paket: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store rincian belanja detail
     */
    public function storerincian(StoreRincianRequest $request)
    {
        try {
            $result = $this->renjaService->createRincianBelanja($request->validated());

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Error storing rincian: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
