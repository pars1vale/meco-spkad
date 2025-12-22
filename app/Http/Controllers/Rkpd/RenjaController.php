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


    public function showRincian($id)
    {
        try {
            Log::info('=== SHOW RINCIAN START ===', ['id' => $id]);
            
            // ===============================
            // 1. AMBIL DATA SUB KEGIATAN
            // ===============================
            $subKegiatan = DB::table('data_sub_keg_bl as dskb')
                ->select('dskb.*', 'du.nama_skpd as nama_unit')
                ->leftJoin('data_unit as du', function ($join) {
                    $join->on('dskb.id_skpd', '=', 'du.id_skpd')
                        ->where('du.tahun_anggaran', 2025);
                })
                ->where('dskb.id', $id)
                ->where('dskb.tahun_anggaran', 2025)
                ->where('dskb.active', 1)
                ->first();

            if (!$subKegiatan) {
                Log::error('Sub kegiatan tidak ditemukan', ['id' => $id]);
                return redirect()->route('rkpd.renja.index')
                    ->with('error', 'Data sub kegiatan tidak ditemukan');
            }

            Log::info('Sub kegiatan found', [
                'id' => $subKegiatan->id,
                'kode_sbl' => $subKegiatan->kode_sbl
            ]);

            // ===============================
            // 2. AMBIL SUMBER DANA
            // ===============================
            $sumberDana = DB::table('data_dana_sub_keg')
                ->where('idsubbl', $id)
                ->where('tahun_anggaran', 2025)
                ->where('active', 1)
                ->get();

            // ===============================
            // 3. AMBIL INDIKATOR
            // ===============================
            $indikator = DB::table('data_sub_keg_indikator')
                ->where('idsubbl', $id)
                ->where('tahun_anggaran', 2025)
                ->where('active', 1)
                ->get();

            // ===============================
            // 4. AMBIL SEMUA DATA RKA
            // ===============================
            $allRka = DB::table('data_rka')
                ->where('kode_sbl', $subKegiatan->kode_sbl)
                ->where('tahun_anggaran', 2025)
                ->where('active', 1)
                ->orderBy('id')
                ->orderBy('subtitle_teks')
                ->orderBy('ket_bl_teks')
                ->orderBy('kode_akun')
                
                ->get();

            Log::info('Total RKA records:', [
                'kode_sbl' => $subKegiatan->kode_sbl,
                'count' => $allRka->count()
            ]);

            // ===============================
            // 5. KELOMPOKKAN DATA HIERARKIS
            // ===============================
            $dataTerkelompok = [];
            $totalKeseluruhan = 0;

            foreach ($allRka as $item) {
                // LEVEL 1: HASHTAG [#] - subtitle_teks (Paket/Kelompok)
                $hashtag = $item->subtitle_teks ?: 'Tanpa Paket';
                
                // LEVEL 2: MINTAG [-] - ket_bl_teks (Kategori Belanja)
                $mintag = $item->ket_bl_teks ?: 'Tanpa Kategori';
                
                // LEVEL 3: KODE REKENING - kode_akun + nama_akun
                $kodeRekening = $item->kode_akun . ' ' . $item->nama_akun;

                // Inisialisasi struktur jika belum ada
                if (!isset($dataTerkelompok[$hashtag])) {
                    $dataTerkelompok[$hashtag] = [
                        'title' => $hashtag,
                        'is_paket' => $item->is_paket,
                        'jenis_bl' => $item->jenis_bl,
                        'total' => 0,
                        'mintag' => []
                    ];
                }

                if (!isset($dataTerkelompok[$hashtag]['mintag'][$mintag])) {
                    $dataTerkelompok[$hashtag]['mintag'][$mintag] = [
                        'title' => $mintag,
                        'total' => 0,
                        'rekening' => []
                    ];
                }

                if (!isset($dataTerkelompok[$hashtag]['mintag'][$mintag]['rekening'][$kodeRekening])) {
                    $dataTerkelompok[$hashtag]['mintag'][$mintag]['rekening'][$kodeRekening] = [
                        'title' => $kodeRekening,
                        'kode_akun' => $item->kode_akun,
                        'nama_akun' => $item->nama_akun,
                        'total' => 0,
                        'items' => []
                    ];
                }

                // LEVEL 4: RINCIAN DETAIL
                // Dari data Anda, SEMUA record adalah rincian detail (tidak ada header dummy)
                // Jadi kita langsung masukkan semua item
                
                // Hitung total (sudah ada di field total_harga atau hitung dari volume * harga_satuan)
                $itemTotal = $item->total_harga ?? (($item->volume ?? 0) * ($item->harga_satuan ?? 0));
                
                // Tambahkan item detail
                $dataTerkelompok[$hashtag]['mintag'][$mintag]['rekening'][$kodeRekening]['items'][] = [
                    'id' => $item->id,
                    'nama_komponen' => $item->nama_komponen ?: $item->spek,
                    'spek' => $item->spek,
                    'spek_komponen' => $item->spek_komponen,
                    'ket_bl_teks' => $item->ket_bl_teks,
                    'substeks' => $item->substeks,
                    'volume' => $item->volume,
                    'satuan' => $item->satuan,
                    'koefisien' => $item->koefisien,
                    'harga_satuan' => $item->harga_satuan,
                    'total_harga' => $itemTotal,
                    'jenis_bl' => $item->jenis_bl
                ];

                // Update totals
                $dataTerkelompok[$hashtag]['mintag'][$mintag]['rekening'][$kodeRekening]['total'] += $itemTotal;
                $dataTerkelompok[$hashtag]['mintag'][$mintag]['total'] += $itemTotal;
                $dataTerkelompok[$hashtag]['total'] += $itemTotal;
                $totalKeseluruhan += $itemTotal;
            }

            Log::info('Data Terkelompok Summary:', [
                'kode_sbl' => $subKegiatan->kode_sbl,
                'total_rka_records' => $allRka->count(),
                'total_hashtag' => count($dataTerkelompok),
                'total_keseluruhan' => $totalKeseluruhan,
                'struktur' => array_map(function($hashtag) {
                    return [
                        'title' => substr($hashtag['title'], 0, 50) . '...',
                        'total' => $hashtag['total'],
                        'mintag_count' => count($hashtag['mintag']),
                        'mintag' => array_map(function($mintag) {
                            return [
                                'title' => substr($mintag['title'], 0, 30) . '...',
                                'total' => $mintag['total'],
                                'rekening_count' => count($mintag['rekening'])
                            ];
                        }, $hashtag['mintag'])
                    ];
                }, $dataTerkelompok)
            ]);

            // ===============================
            // 6. RETURN VIEW
            // ===============================
            return view('rkpd.renja.rincian', compact(
                'subKegiatan',
                'sumberDana',
                'indikator',
                'dataTerkelompok',
                'totalKeseluruhan'
            ));

        } catch (\Exception $e) {
            Log::error('Error showRincian: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return redirect()->route('rkpd.renja.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


}
