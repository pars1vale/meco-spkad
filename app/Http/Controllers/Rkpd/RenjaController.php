<?php

namespace App\Http\Controllers\Rkpd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rkpd\StorePaketBelanjaRequest;
use App\Http\Requests\Rkpd\StoreRenjaRequest;
use App\Http\Requests\Rkpd\StoreRincianRequest;
use App\Http\Requests\Rkpd\UpdateRenjaRequest;
use App\Services\Rkpd\RenjaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            Log::error('Error loading RENJA index: '.$e->getMessage());

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
            Log::error('Error storing renja: '.$e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage())
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
            Log::error('Error loading edit page: '.$e->getMessage());

            return redirect()->route('rkpd.renja.index')
                ->with('error', 'Gagal memuat halaman edit');
        }
    }

    public function update(UpdateRenjaRequest $request, $id)
    {
        try {
            $result = $this->renjaService->updateRenja($id, $request->validated());

            return redirect()->route('rkpd.renja.index')
                ->with('success', $result['message']);
        } catch (\Exception $e) {
            Log::error('Error updating RENJA: '.$e->getMessage());

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $result = $this->renjaService->deleteRenja($id);

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Error deleting RENJA: '.$e->getMessage());

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
            $tahunAnggaran = $request->input('tahun_anggaran', 2025);

            $result = $this->renjaService->getSubKegiatanBySkpd($idSkpd, $tahunAnggaran);

            return response()->json([
                'success' => true,
                'data' => $result['data'],
                'count' => $result['count'],
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting sub kegiatan: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data',
                'error' => $e->getMessage(),
            ], 500);
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
            Log::error('Error getting data: '.$e->getMessage());

            return response()->json([
                'draw' => intval($request->draw ?? 1),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function getAkunByJenisBelanja(Request $request)
    {
        try {
            $jenisBelanja = $request->input('jenis_bl');
            $tahunAnggaran = $request->input('tahun_anggaran', 2025);

            $result = $this->renjaService->getAkunByJenisBelanja($jenisBelanja, $tahunAnggaran);

            if (! $result['success']) {
                return response()->json($result, 400);
            }

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Error loading akun: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
            ], 500);
        }
    }

    public function getDetailAkun(Request $request)
    {
        try {
            $akunId = $request->akun_id;
            $result = $this->renjaService->getDetailAkun($akunId);

            if (! $result['success']) {
                return response()->json($result, 404);
            }

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Error getting detail akun: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan',
            ], 500);
        }
    }

    public function getPaketBelanjaList(Request $request)
    {
        try {
            $idRinciSubBl = $request->input('id_rinci_sub_bl');
            $tipePaket = $request->input('tipe_paket');

            if (! $idRinciSubBl || ! $tipePaket) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parameter tidak lengkap',
                    'data' => [],
                ], 400);
            }

            $subKegiatan = DB::table('data_sub_keg_bl')
                ->where('id', $idRinciSubBl)
                ->first();

            if (! $subKegiatan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sub kegiatan tidak ditemukan',
                    'data' => [],
                ], 404);
            }

            $paketList = DB::table('data_rka')
                ->select(
                    DB::raw('MIN(id) as id'),
                    'subtitle_teks as uraian_paket',
                    'is_paket',
                    'kode_akun',
                    'nama_akun',
                    'jenis_bl',
                    'idsubtitle'
                )
                ->where('kode_sbl', $subKegiatan->kode_sbl)
                ->where('tahun_anggaran', 2025)
                ->where('active', 1)
                ->where('is_paket', $tipePaket)
                ->whereNotNull('subtitle_teks')
                ->where('subtitle_teks', '!=', '')
                ->where(function ($q) {
                    $q->whereNull('ket_bl_teks')
                        ->orWhere('ket_bl_teks', '!=', '--- PAKET/KELOMPOK ---');
                })
                ->groupBy('subtitle_teks', 'is_paket', 'kode_akun', 'nama_akun', 'jenis_bl', 'idsubtitle')
                ->orderBy('subtitle_teks', 'ASC')
                ->get();

            $formattedData = $paketList->map(function ($item) {
                $displayText = preg_replace('/^\[\#\]\s*/', '', $item->uraian_paket);

                return [
                    'id' => $item->id,
                    'uraian_paket' => $displayText,
                    'uraian_paket_full' => $item->uraian_paket,
                    'is_paket' => $item->is_paket,
                    'kode_akun' => $item->kode_akun,
                    'nama_akun' => $item->nama_akun,
                    'jenis_bl' => $item->jenis_bl,
                    'idsubtitle' => $item->idsubtitle,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedData,
                'message' => 'Data paket berhasil dimuat',
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting paket list: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan',
                'data' => [],
            ], 500);
        }
    }

    public function storePaketBelanja(StorePaketBelanjaRequest $request)
    {
        try {
            $result = $this->renjaService->createPaketBelanja($request->validated());

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Error creating paket: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
            ], 500);
        }
    }

    public function storeRincian(StoreRincianRequest $request)
    {
        try {
            $result = $this->renjaService->createRincianBelanja($request->validated());

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Error creating rincian: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
            ], 500);
        }
    }

    public function getRincian($id)
    {
        try {
            $result = $this->renjaService->getRincianSubKegiatan($id);

            if (! $result['subKegiatan']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sub kegiatan tidak ditemukan',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting rincian: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan',
            ], 500);
        }
    }
}
