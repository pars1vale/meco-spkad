<?php

namespace App\Http\Controllers\Rkpd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Rkpd\RenjaService;
use App\Http\Requests\Rkpd\StoreRenjaRequest;
use App\Http\Requests\Rkpd\StoreRincianRequest;
use App\Http\Requests\Rkpd\StorePaketBelanjaRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


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

    public function getPaketBelanjaList(Request $request)
    {
        try {
            $idRinciSubBl = $request->input('id_rinci_sub_bl');
            $tipePaket = $request->input('tipe_paket');
            
            Log::info('GET PAKET REQUEST', [
                'id_rinci_sub_bl' => $idRinciSubBl,
                'tipe_paket' => $tipePaket
            ]);
            
            if (!$idRinciSubBl || !$tipePaket) {
                return response()->json([
                    'success' => false,
                    'message' => 'Parameter tidak lengkap',
                    'data' => []
                ], 400);
            }
            
            $subKegiatan = DB::table('data_sub_keg_bl')
                ->where('id', $idRinciSubBl)
                ->first();
            
            if (!$subKegiatan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sub kegiatan tidak ditemukan',
                    'data' => []
                ], 404);
            }
            
            $paketList = DB::table('data_rka')
                ->select(
                    'id',
                    'subtitle_teks as uraian_paket',
                    'is_paket',
                    'kode_akun',
                    'nama_akun',
                    'jenis_bl'
                )
                ->whereIn('id', function($query) use ($subKegiatan, $tipePaket) {
                    $query->select(DB::raw('MIN(id)'))
                        ->from('data_rka')
                        ->where('kode_sbl', $subKegiatan->kode_sbl)
                        ->where('tahun_anggaran', 2025)
                        ->where('active', 1)
                        ->where('is_paket', $tipePaket)
                        ->whereNotNull('subtitle_teks')
                        ->where('subtitle_teks', '!=', '')
                        ->groupBy('subtitle_teks');
                })
                ->orderBy('subtitle_teks', 'ASC')
                ->get();
            
            // ==========================================
            // FORMAT DATA: HAPUS [#] UNTUK TAMPILAN
            // ==========================================
            $formattedData = $paketList->map(function($item) {
                // Hapus [#] dan whitespace di awal
                $displayText = preg_replace('/^\[\#\]\s*/', '', $item->uraian_paket);
                
                return [
                    'id' => $item->id,
                    'uraian_paket' => $displayText, // ← Tanpa [#]
                    'uraian_paket_full' => $item->uraian_paket, // ← Dengan [#] (opsional, untuk reference)
                    'is_paket' => $item->is_paket,
                    'kode_akun' => $item->kode_akun,
                    'nama_akun' => $item->nama_akun,
                    'jenis_bl' => $item->jenis_bl
                ];
            });
            
            Log::info('PAKET FOUND', [
                'kode_sbl' => $subKegiatan->kode_sbl,
                'tipe_paket' => $tipePaket,
                'count' => $formattedData->count(),
                'data_sample' => $formattedData->take(2)->toArray()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Data paket berhasil dimuat',
                'data' => $formattedData,
                'count' => $formattedData->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('ERROR GET PAKET', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function storePaketBelanja(StorePaketBelanjaRequest $request)
    {
        try {
            $request->validate([
                'id_rinci_sub_bl' => 'required|integer',
                'tipe_paket' => 'required|in:1,2',
                'uraian_paket' => 'required|string|max:1000',
                'jenis_bl' => 'required|string', // Perlu jenis belanja untuk kategorisasi
                'id_akun' => 'required|integer|exists:akun,id'
            ]);
            
            DB::beginTransaction();
            
            // Ambil info sub kegiatan
            $subKegiatan = DB::table('data_sub_keg_bl')
                ->where('id', $request->id_rinci_sub_bl)
                ->first();
            
            if (!$subKegiatan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sub kegiatan tidak ditemukan'
                ], 404);
            }
            
            // Ambil info akun
            $akun = DB::table('akun')
                ->where('id', $request->id_akun)
                ->first();
            
            if (!$akun) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun tidak ditemukan'
                ], 404);
            }
            
            // Ambil sumber dana (pertama)
            $sumberDana = DB::table('data_dana_sub_keg')
                ->where('idsubbl', $request->id_rinci_sub_bl)
                ->where('active', 1)
                ->first();
            
            // Insert sebagai RECORD PAKET di data_rka
            // Record ini hanya sebagai "header/kelompok"
            $idPaket = DB::table('data_rka')->insertGetId([
                // Identitas
                'id_rinci_sub_bl' => $request->id_rinci_sub_bl,
                'kode_sbl' => $subKegiatan->kode_sbl,
                'kode_bl' => $subKegiatan->kode_bl,
                'tahun_anggaran' => $subKegiatan->tahun_anggaran ?? 2025,
                
                // Jenis & Akun
                'jenis_bl' => $request->jenis_bl,
                'kode_akun' => $akun->kode_akun,
                'nama_akun' => $akun->nama_akun,
                
                // PENANDA PAKET/KELOMPOK
                'is_paket' => $request->tipe_paket, // 1 atau 2
                'subtitle_teks' => $request->uraian_paket, // Nama paket/kelompok
                'idsubtitle' => null, // Paket tidak punya parent
                
                // Uraian
                'ket_bl_teks' => '--- PAKET/KELOMPOK ---',
                'spek' => $request->uraian_paket,
                
                // Data dummy untuk paket (bukan rincian real)
                'volume' => 0,
                'satuan' => 'Paket',
                'harga_satuan' => 0,
                'total_harga' => 0,
                'rincian' => 0,
                'rincian_murni' => 0,
                
                // Sumber dana
                'id_dana' => $sumberDana->iddana ?? null,
                'nama_dana' => $sumberDana->namadana ?? null,
                'kode_dana' => $sumberDana->kodedana ?? null,
                
                // Audit
                'created_user' => auth()->id() ?? null,
                'createddate' => date('Y-m-d'),
                'createdtime' => date('H:i:s'),
                'active' => 1,
                'is_locked' => 0,
                'update_at' => now(),
                
                // Fields lain default
                'id_daerah' => 604,
                'id_standar_nfs' => 0,
                'idbl' => null,
                'idsubbl' => $request->id_rinci_sub_bl,
                'harga_satuan_murni' => 0,
                'volume_murni' => 0,
                'totalpajak' => 0,
                'pajak' => 0,
                'pajak_murni' => 0
            ]);
            
            // Ambil record yang baru dibuat
            $paketBaru = DB::table('data_rka')
                ->where('id', $idPaket)
                ->first();
            
            DB::commit();
            
            Log::info('PAKET CREATED IN RKA', [
                'id' => $idPaket,
                'subtitle_teks' => $request->uraian_paket,
                'is_paket' => $request->tipe_paket
            ]);

            $uraianPaketDisplay = preg_replace('/^\[\#\]\s*/', '', $paketBaru->subtitle_teks);
            
            return response()->json([
                'success' => true,
                'message' => 'Paket belanja berhasil ditambahkan',
                'data' => [
                    'id' => $paketBaru->id,
                    'uraian_paket' => $uraianPaketDisplay,  // ← TANPA [#]
                    'is_paket' => $paketBaru->is_paket
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error storing paket: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

   public function storerincian(StoreRincianRequest $request)
{
    try {
        // ================================================
        // VALIDASI INPUT
        // ================================================
        $request->validate([
            'id_rinci_sub_bl' => 'required',
            'jenis_bl' => 'required',
            'id_akun' => 'required|exists:akun,id',
            'kode_rekening' => 'required',
            'nama_rekening' => 'required',
            'tipe_paket' => 'required',
            'id_paket_belanja' => 'required|integer', // ← ID HASHTAG
            'uraian' => 'required',
            'kategori_belanja' => 'required|string', // ← MINTAG
            
            'koefisien' => 'nullable|array',
            'koefisien.*' => 'nullable|numeric',
            'satuan_koefisien' => 'nullable|array',
            'satuan_koefisien.*' => 'nullable|string',
            
            'volume' => 'required|numeric',
            'satuan' => 'required',
            'harga_satuan' => 'required|numeric',
            
            'id_standar_harga' => 'nullable|integer',
            'jenis_standar_harga' => 'nullable|string',
            'tkdn' => 'nullable|string',
            'spesifikasi_komponen' => 'nullable|string',
            'keterangan' => 'nullable|string'
        ]);

        DB::beginTransaction();

        // ================================================
        // 1. GET DATA SUB KEGIATAN
        // ================================================
        $subKegiatan = DB::table('data_sub_keg_bl')
            ->where('id', $request->id_rinci_sub_bl)
            ->first();

        if (!$subKegiatan) {
            return response()->json([
                'success' => false,
                'message' => 'Sub kegiatan tidak ditemukan'
            ], 404);
        }

        // ================================================
        // 2. GET DATA AKUN
        // ================================================
        $akun = DB::table('akun')
            ->where('id', $request->id_akun)
            ->first();

        if (!$akun) {
            return response()->json([
                'success' => false,
                'message' => 'Akun tidak ditemukan'
            ], 404);
        }

        // ================================================
        // 3. GET SUMBER DANA (DARI data_dana_sub_keg)
        // ================================================
        // Query berdasarkan kode_sbl dari sub kegiatan
        $sumberDana = DB::table('data_dana_sub_keg')
            ->where('kode_sbl', $subKegiatan->kode_sbl)
            ->where('active', 1)
            ->first();

        // FALLBACK: Jika tidak ketemu, coba dengan idsubbl
        if (!$sumberDana) {
            $sumberDana = DB::table('data_dana_sub_keg')
                ->where('idsubbl', $subKegiatan->id)
                ->where('active', 1)
                ->first();
        }

        if (!$sumberDana) {
            Log::warning('SUMBER DANA NOT FOUND', [
                'id_sub_kegiatan' => $subKegiatan->id,
                'kode_sbl' => $subKegiatan->kode_sbl,
                'query_attempted' => 'kode_sbl AND idsubbl'
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Sumber dana tidak ditemukan untuk sub kegiatan ini. Kode SBL: ' . $subKegiatan->kode_sbl
            ], 404);
        }

        // Data yang akan digunakan:
        // - $sumberDana->iddana → id_dana
        // - $sumberDana->namadana → nama_dana  
        // - $sumberDana->kodedana → kode_dana

        // ================================================
        // 4. GET INFO PAKET (HASHTAG) DARI ID
        // ================================================
        // id_paket_belanja ini adalah ID record di data_rka yang berisi [#]
        $paketHashtag = DB::table('data_rka')
            ->where('id', $request->id_paket_belanja)
            ->first();

        if (!$paketHashtag) {
            return response()->json([
                'success' => false,
                'message' => 'Paket belanja tidak ditemukan'
            ], 404);
        }

        // Ambil data:
        // - $paketHashtag->id → idsubtitle (ID paket)
        // - $paketHashtag->subtitle_teks → "[#] Nama Paket"
        $idSubtitle = $paketHashtag->id;
        $namaPaket = $paketHashtag->subtitle_teks;

        // ================================================
        // 5. EXTRACT MINTAG (KATEGORI BELANJA)
        // ================================================
        // Input format: "[-] Belanja Meubeler"
        // Simpan dengan [-] untuk grouping
        $mintagTeks = $request->kategori_belanja;

        // ================================================
        // 6. HITUNG KOEFISIEN & VOLUME
        // ================================================
        $koefisienArray = $request->koefisien ?? [];
        $satuanKoefArray = $request->satuan_koefisien ?? [];
        
        $koefisienTotal = 1;
        foreach ($koefisienArray as $koef) {
            if ($koef && is_numeric($koef)) {
                $koefisienTotal *= floatval($koef);
            }
        }
        
        $volum1 = isset($koefisienArray[0]) ? floatval($koefisienArray[0]) : 0;
        $volum2 = isset($koefisienArray[1]) ? floatval($koefisienArray[1]) : 0;
        $volum3 = isset($koefisienArray[2]) ? floatval($koefisienArray[2]) : 0;
        $volum4 = isset($koefisienArray[3]) ? floatval($koefisienArray[3]) : 0;
        
        $sat1 = $satuanKoefArray[0] ?? '';
        $sat2 = $satuanKoefArray[1] ?? '';
        $sat3 = $satuanKoefArray[2] ?? '';
        $sat4 = $satuanKoefArray[3] ?? '';

        $volume = floatval($request->volume);
        $hargaSatuan = floatval($request->harga_satuan);
        $totalHarga = $volume * $hargaSatuan;

        // ================================================
        // 7. BUILD KOEFISIEN STRING
        // ================================================
        $koefisienStr = '';
        foreach ($koefisienArray as $index => $koef) {
            if ($koef && is_numeric($koef)) {
                $satuan = $satuanKoefArray[$index] ?? '';
                if ($koefisienStr) $koefisienStr .= ' / ';
                $koefisienStr .= $koef . ($satuan ? ' ' . $satuan : '');
            }
        }
        if (empty($koefisienStr)) {
            $koefisienStr = $volume . ' ' . $request->satuan;
        }

        // ================================================
        // 8. AMBIL DATA SSH JIKA ADA
        // ================================================
        $sshData = null;
        if ($request->id_standar_harga) {
            $sshData = DB::table('data_ssh')
                ->where('id_standar_harga', $request->id_standar_harga)
                ->first();
        }

        // ================================================
        // 9. INSERT RINCIAN KE DATA_RKA
        // ================================================
        $insertData = [
            // ===== IDENTITAS SUB KEGIATAN =====
            'id_rinci_sub_bl' => $request->id_rinci_sub_bl,
            'kode_sbl' => $subKegiatan->kode_sbl,
            'kode_bl' => $subKegiatan->kode_bl,
            'tahun_anggaran' => $subKegiatan->tahun_anggaran ?? 2025,
            'id_daerah' => 604,
            'idsubbl' => $request->id_rinci_sub_bl,
            
            // ===== JENIS BELANJA & AKUN =====
            'jenis_bl' => $request->jenis_bl,
            'kode_akun' => $akun->kode_akun,
            'nama_akun' => $akun->nama_akun,
            
            // ===== LINK KE PAKET (HASHTAG) =====
            'is_paket' => $request->tipe_paket,
            'idsubtitle' => $idSubtitle,        // ← ID PAKET (HASHTAG)
            'subtitle_teks' => $namaPaket,      // "[#] Nama Paket"
            'substeks' => $namaPaket,
            'subs_bl_teks' => $namaPaket,
            
            // ===== KATEGORI BELANJA (MINTAG) =====
            'ket_bl_teks' => $mintagTeks,       // "[-] Kategori Belanja"
            
            // ===== KOMPONEN & SPESIFIKASI =====
            'nama_komponen' => $request->uraian,
            'spek_komponen' => $request->spesifikasi_komponen,
            'spek' => NULL,
            
            // ===== VOLUME & HARGA =====
            'volume' => $volume,
            'satuan' => $request->satuan,
            'harga_satuan' => $hargaSatuan,
            'total_harga' => $totalHarga,
            'rincian' => $totalHarga,
            'volume_murni' => NULL,
            'harga_satuan_murni' => NULL,
            'rincian_murni' => NULL,
            
            // ===== KOEFISIEN =====
            'koefisien' => $koefisienStr,
            'koefisien_murni' => NULL,
            'volum1' => $volum1,
            'volum2' => $volum2,
            'volum3' => $volum3,
            'volum4' => $volum4,
            'sat1' => $sat1,
            'sat2' => $sat2,
            'sat3' => $sat3,
            'sat4' => $sat4,
            
            // ===== SUMBER DANA (DARI data_dana_sub_keg) =====
            'id_dana' => $sumberDana->iddana,      // ← ID Dana
            'nama_dana' => $sumberDana->namadana,  // ← Nama Dana
            'kode_dana' => $sumberDana->kodedana,  // ← Kode Dana
            
            // ===== PAJAK =====
            'pajak' => 0.00,
            'pajak_murni' => NULL,
            'totalpajak' => NULL,
            
            // ===== AUDIT TRAIL =====
            'created_user' => auth()->id() ?? null,
            'createddate' => NULL,
            'createdtime' => NULL,
            'updated_user' => auth()->id() ?? null,
            'updateddate' => NULL,
            'updatedtime' => NULL,
            'update_at' => now(),
            
            // ===== STATUS & FLAGS =====
            'active' => 1,
            'is_locked' => NULL,
            'akun_locked' => NULL,
            'ssh_locked' => 0,
            
            // ===== FIELDS LAIN =====
            'id_standar_nfs' => 0,
            'idbl' => 0,
            'lokus_akun_teks' => '',
            'user1' => '',
            'user2' => '',
            'id_prop_penerima' => NULL,
            'id_camat_penerima' => NULL,
            'id_kokab_penerima' => NULL,
            'id_lurah_penerima' => NULL,
            'id_penerima' => NULL,
            'idkomponen' => 0.00,
            'idketerangan' => NULL
        ];

        // ================================================
        // 10. TAMBAHKAN DATA SSH JIKA ADA
        // ================================================
        if ($sshData) {
            $columns = DB::select("SHOW COLUMNS FROM data_rka LIKE 'id_standar_harga'");
            if (count($columns) > 0) {
                $insertData['id_standar_harga'] = $request->id_standar_harga;
            }
            
            if (!empty($sshData->spek)) {
                $insertData['spek_komponen'] = $sshData->spek;
            }
            
            $tkdnColumns = DB::select("SHOW COLUMNS FROM data_rka LIKE 'tkdn'");
            if (count($tkdnColumns) > 0) {
                $insertData['tkdn'] = $request->tkdn;
            }
            
            $jenisColumns = DB::select("SHOW COLUMNS FROM data_rka LIKE 'jenis_standar_harga'");
            if (count($jenisColumns) > 0) {
                $insertData['jenis_standar_harga'] = $request->jenis_standar_harga;
            }
        }

        // ================================================
        // 11. SIMPAN KE DATABASE
        // ================================================
        $idRka = DB::table('data_rka')->insertGetId($insertData);

        DB::commit();

        // ================================================
        // 12. LOGGING
        // ================================================
        Log::info('✅ RINCIAN CREATED', [
            'id_rka' => $idRka,
            'id_sub_kegiatan' => $request->id_rinci_sub_bl,
            'idsubtitle' => $idSubtitle,
            'paket' => $namaPaket,
            'mintag' => $mintagTeks,
            'komponen' => $request->uraian,
            'total_harga' => $totalHarga,
            'sumber_dana' => [
                'id_dana' => $sumberDana->iddana,
                'nama_dana' => $sumberDana->namadana,
                'kode_dana' => $sumberDana->kodedana
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Rincian belanja berhasil ditambahkan',
            'data' => [
                'id' => $idRka,
                'total' => $totalHarga,
                'koefisien' => $koefisienStr
            ]
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        
        Log::error('❌ ERROR STORE RINCIAN', [
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
            'trace' => $e->getTraceAsString(),
            'request_data' => $request->all()
        ]);
        
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
                ->orderBy('subtitle_teks')
                ->orderBy('ket_bl_teks')
                ->orderBy('kode_akun')
                ->orderBy('id')
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

    public function getSshData(Request $request)
    {
        try {
            $idStandarHarga = $request->input('id_standar_harga');
            
            if (!$idStandarHarga) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID standar harga tidak valid'
                ], 400);
            }

            $sshData = DB::table('data_ssh')
                ->where('id_standar_harga', $idStandarHarga)
                ->where('tahun', 2025)
                ->where('id_daerah', 604)
                ->first();

            if (!$sshData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data SSH tidak ditemukan'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $sshData->id_standar_harga,
                    'kode' => $sshData->kode_standar_harga,
                    'nama' => $sshData->nama_standar_harga,
                    'satuan' => $sshData->satuan,
                    'spek' => $sshData->spek,
                    'harga' => $sshData->harga,
                    'tkdn' => $sshData->nilai_tkdn,
                    'tipe' => $sshData->tipe_standar_harga
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error getting SSH data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function searchKomponen(Request $request)
    {
        try {
            $jenisStandarHarga = $request->input('jenis_standar_harga');
            $search = $request->input('q', '');
            
            Log::info('SEARCH KOMPONEN', [
                'jenis' => $jenisStandarHarga,
                'search' => $search
            ]);

            // Mapping jenis standar harga ke tipe_standar_harga
            $tipeMapping = [
                '1' => 'SSH',  // Standar Satuan Harga
                '2' => 'SBU',  // Standar Biaya Umum
                '3' => 'HSPK', // Harga Satuan Pokok Kegiatan
                '4' => 'ASB'   // Analisa Standar Belanja
            ];

            $query = DB::table('data_ssh')
                ->select(
                    'id_standar_harga as id',
                    'kode_standar_harga',
                    'nama_standar_harga',
                    'satuan',
                    'spek',
                    'harga',
                    'nilai_tkdn',
                    'tipe_standar_harga',
                    DB::raw("CONCAT(kode_standar_harga, ' - ', nama_standar_harga) as text")
                )
                ->where('tahun', 2025)
                ->where('id_daerah', 604);

            // Filter berdasarkan tipe jika dipilih
            if ($jenisStandarHarga && isset($tipeMapping[$jenisStandarHarga])) {
                $query->where('tipe_standar_harga', $tipeMapping[$jenisStandarHarga]);
            }

            // Search
            if (!empty($search)) {
                $query->where(function($q) use ($search) {
                    $q->where('nama_standar_harga', 'LIKE', "%{$search}%")
                    ->orWhere('kode_standar_harga', 'LIKE', "%{$search}%")
                    ->orWhere('spek', 'LIKE', "%{$search}%");
                });
            }

            $results = $query
                ->orderBy('nama_standar_harga')
                ->limit(50)
                ->get();

            Log::info('SEARCH RESULT', [
                'count' => $results->count(),
                'tipe_filter' => $tipeMapping[$jenisStandarHarga] ?? 'ALL'
            ]);

            return response()->json([
                'success' => true,
                'results' => $results,
                'count' => $results->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error searching komponen: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getMintagList(Request $request)
    {
        try {
            $idPaketBelanja = $request->input('id_paket_belanja');
            
            if (!$idPaketBelanja) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID Paket tidak valid',
                    'data' => []
                ], 400);
            }
            
            // Ambil paket info
            $paket = DB::table('data_rka')
                ->where('id', $idPaketBelanja)
                ->first();
            
            if (!$paket) {
                return response()->json([
                    'success' => false,
                    'message' => 'Paket tidak ditemukan',
                    'data' => []
                ], 404);
            }
            
            // Ambil semua mintag UNIK yang sudah ada untuk subtitle_teks ini
            $mintagList = DB::table('data_rka')
                ->select('ket_bl_teks')
                ->where('subtitle_teks', $paket->subtitle_teks)
                ->where('tahun_anggaran', 2025)
                ->where('active', 1)
                ->whereNotNull('ket_bl_teks')
                ->where('ket_bl_teks', '!=', '')
                ->groupBy('ket_bl_teks')
                ->orderBy('ket_bl_teks')
                ->get();
            
            // Format data
            $formattedData = $mintagList->map(function($item) {
                // Hapus [-] untuk tampilan
                $displayText = preg_replace('/^\[\-\]\s*/', '', $item->ket_bl_teks);
                
                return [
                    'value' => $item->ket_bl_teks, // Dengan [-]
                    'text' => $displayText // Tanpa [-]
                ];
            });
            
            Log::info('MINTAG LIST', [
                'paket_id' => $idPaketBelanja,
                'subtitle' => $paket->subtitle_teks,
                'count' => $formattedData->count()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Data mintag berhasil dimuat',
                'data' => $formattedData,
                'count' => $formattedData->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('ERROR GET MINTAG', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function storeMintag(Request $request)
    {
        try {
            $request->validate([
                'id_paket_belanja' => 'required|integer',
                'nama_mintag' => 'required|string|max:500'
            ]);
            
            // Ambil info paket
            $paket = DB::table('data_rka')
                ->where('id', $request->id_paket_belanja)
                ->first();
            
            if (!$paket) {
                return response()->json([
                    'success' => false,
                    'message' => 'Paket tidak ditemukan'
                ], 404);
            }
            
            // Tambahkan prefix [-] jika belum ada
            $mintag = $request->nama_mintag;
            if (!preg_match('/^\[\-\]/', $mintag)) {
                $mintag = '[-] ' . $mintag;
            }
            
            // Cek apakah mintag sudah ada
            $existing = DB::table('data_rka')
                ->where('subtitle_teks', $paket->subtitle_teks)
                ->where('ket_bl_teks', $mintag)
                ->where('tahun_anggaran', 2025)
                ->where('active', 1)
                ->exists();
            
            if ($existing) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori belanja ini sudah ada untuk paket tersebut'
                ], 400);
            }
            
            // Return data mintag baru
            $displayText = preg_replace('/^\[\-\]\s*/', '', $mintag);
            
            return response()->json([
                'success' => true,
                'message' => 'Kategori belanja berhasil ditambahkan',
                'data' => [
                    'value' => $mintag,
                    'text' => $displayText
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('ERROR STORE MINTAG: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }


}