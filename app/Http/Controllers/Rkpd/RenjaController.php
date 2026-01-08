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
            
            // ✅ AMBIL PAKET UNIK BERDASARKAN subtitle_teks DAN idsubtitle
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
                // ✅ SKIP DUMMY RECORDS
                ->where(function($q) {
                    $q->whereNull('ket_bl_teks')
                      ->orWhere('ket_bl_teks', '!=', '--- PAKET/KELOMPOK ---');
                })
                ->groupBy('subtitle_teks', 'is_paket', 'kode_akun', 'nama_akun', 'jenis_bl', 'idsubtitle')
                ->orderBy('subtitle_teks', 'ASC')
                ->get();
            
            // Format data: Hapus [#] untuk tampilan
            $formattedData = $paketList->map(function($item) {
                $displayText = preg_replace('/^\[\#\]\s*/', '', $item->uraian_paket);
                
                return [
                    'id' => $item->id,
                    'uraian_paket' => $displayText,
                    'uraian_paket_full' => $item->uraian_paket,
                    'is_paket' => $item->is_paket,
                    'kode_akun' => $item->kode_akun,
                    'nama_akun' => $item->nama_akun,
                    'jenis_bl' => $item->jenis_bl,
                    'idsubtitle' => $item->idsubtitle
                ];
            });
            
            Log::info('PAKET FOUND', [
                'kode_sbl' => $subKegiatan->kode_sbl,
                'tipe_paket' => $tipePaket,
                'count' => $formattedData->count()
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
                'jenis_bl' => 'required|string',
                'id_akun' => 'required|integer|exists:akun,id'
            ]);
            
            // ✅ TIDAK PERLU INSERT RECORD DUMMY LAGI
            // Paket akan ter-create otomatis saat rincian pertama disimpan
            
            // Ambil info untuk response
            $subKegiatan = DB::table('data_sub_keg_bl')
                ->where('id', $request->id_rinci_sub_bl)
                ->first();
            
            if (!$subKegiatan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sub kegiatan tidak ditemukan'
                ], 404);
            }
            
            $akun = DB::table('akun')
                ->where('id', $request->id_akun)
                ->first();
            
            if (!$akun) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun tidak ditemukan'
                ], 404);
            }
            
            // Generate temporary ID untuk paket baru
            $tempPaketId = 'new_' . uniqid();
            
            Log::info('PAKET METADATA CREATED', [
                'temp_id' => $tempPaketId,
                'uraian' => $request->uraian_paket
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Paket belanja berhasil ditambahkan',
                'data' => [
                    'id' => $tempPaketId,
                    'uraian_paket' => preg_replace('/^\[\#\]\s*/', '', $request->uraian_paket),
                    'uraian_paket_full' => $request->uraian_paket,
                    'is_paket' => $request->tipe_paket,
                    'metadata' => [
                        'id_rinci_sub_bl' => $request->id_rinci_sub_bl,
                        'jenis_bl' => $request->jenis_bl,
                        'id_akun' => $request->id_akun,
                        'kode_akun' => $akun->kode_akun,
                        'nama_akun' => $akun->nama_akun,
                        'kode_sbl' => $subKegiatan->kode_sbl
                    ]
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
                // 'id_paket_belanja' => 'required|string',
                'subtitle_teks_paket' => 'required|string|max:2000',
                'uraian' => 'required',
                'kategori_belanja' => 'required|string',
                
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
                ->where('id_sub_bl', $request->id_rinci_sub_bl)
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

            $namaAkunLengkap = $akun->kode_akun . ' ' . $akun->nama_akun;

            // ================================================
            // 3. GET SUMBER DANA
            // ================================================
            $sumberDana = DB::table('data_dana_sub_keg')
                ->where('kode_sbl', $subKegiatan->kode_sbl)
                ->where('active', 1)
                ->first();

            if (!$sumberDana) {
                $sumberDana = DB::table('data_dana_sub_keg')
                    ->where('idsubbl', $subKegiatan->id_sub_bl)
                    ->where('active', 1)
                    ->first();
            }

            if (!$sumberDana) {
                Log::warning('SUMBER DANA NOT FOUND', [
                    'id_sub_kegiatan' => $subKegiatan->id,
                    'kode_sbl' => $subKegiatan->kode_sbl
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Sumber dana tidak ditemukan untuk sub kegiatan ini'
                ], 404);
            }

            // ================================================
            // 4. HANDLE PAKET CREATION & GET idsubtitle
            // ================================================
            $idPaketBelanja = $request->id_paket_belanja;
            $subtitleTeks = $request->subtitle_teks_paket;
            $idSubtitleToUse = null;
            $isNewPaket = false;

            Log::info('🎯 PROCESSING PAKET', [
                'id_paket_belanja' => $idPaketBelanja,
                'subtitle_teks' => $subtitleTeks,
                'is_temp_id' => str_starts_with($idPaketBelanja, 'new_')
            ]);

            // Cek apakah ini temporary ID dari paket baru
            if (str_starts_with($idPaketBelanja, 'new_')) {
                $isNewPaket = true;
                
                // Cek apakah sudah ada rincian untuk paket ini
                $existingPaket = DB::table('data_rka')
                    ->where('subtitle_teks', $subtitleTeks)
                    ->where('kode_sbl', $subKegiatan->kode_sbl)
                    ->where('tahun_anggaran', 2025)
                    ->where('active', 1)
                    ->whereNotNull('idsubtitle')
                    ->where(function($q) {
                        $q->whereNull('ket_bl_teks')
                          ->orWhere('ket_bl_teks', '!=', '--- PAKET/KELOMPOK ---');
                    })
                    ->first();
                
                if ($existingPaket) {
                    // Sudah ada rincian dengan paket ini
                    $idSubtitleToUse = $existingPaket->idsubtitle;
                    $isNewPaket = false;
                    
                    Log::info('📦 EXISTING PAKET FOUND', [
                        'idsubtitle' => $idSubtitleToUse
                    ]);
                } else {
                    // Belum ada rincian, ini akan jadi rincian pertama
                    $idSubtitleToUse = null;
                    
                    Log::info('🆕 NEW PAKET - Will create on insert');
                }
            } else {
                // Ini paket yang sudah ada
                $paketHashtag = DB::table('data_rka')
                    ->where('id', $idPaketBelanja)
                    ->first();
                
                if ($paketHashtag) {
                    $idSubtitleToUse = $paketHashtag->idsubtitle ?? $paketHashtag->id;
                    $subtitleTeks = $paketHashtag->subtitle_teks;
                    
                    Log::info('📦 USING EXISTING PAKET', [
                        'paket_id' => $paketHashtag->id,
                        'idsubtitle' => $idSubtitleToUse
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Paket belanja tidak ditemukan'
                    ], 404);
                }
            }

            // ================================================
            // 5. EXTRACT MINTAG (KATEGORI BELANJA)
            // ================================================
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
                // IDENTITAS SUB KEGIATAN
                'id_rinci_sub_bl' => $request->id_rinci_sub_bl,
                'kode_sbl' => $subKegiatan->kode_sbl,
                'kode_bl' => $subKegiatan->kode_bl,
                'tahun_anggaran' => $subKegiatan->tahun_anggaran ?? 2025,
                'id_daerah' => 604,
                'idsubbl' => $subKegiatan->id_sub_bl,
                
                // JENIS BELANJA & AKUN
                'jenis_bl' => $request->jenis_bl,
                'kode_akun' => $akun->kode_akun,
                'nama_akun' => $namaAkunLengkap,
                
                // LINK KE PAKET (HASHTAG)
                'is_paket' => $request->tipe_paket,
                'idsubtitle' => $idSubtitleToUse,
                'subtitle_teks' => $subtitleTeks,
                'substeks' => $subtitleTeks,
                'subs_bl_teks' => $subtitleTeks,
                
                // KATEGORI BELANJA (MINTAG)
                'ket_bl_teks' => $mintagTeks,
                
                // KOMPONEN & SPESIFIKASI
                'nama_komponen' => $request->uraian,
                'spek_komponen' => $request->spesifikasi_komponen,
                'spek' => NULL,
                
                // VOLUME & HARGA
                'volume' => $volume,
                'satuan' => $request->satuan,
                'harga_satuan' => $hargaSatuan,
                'total_harga' => $totalHarga,
                'rincian' => $totalHarga,
                'volume_murni' => NULL,
                'harga_satuan_murni' => NULL,
                'rincian_murni' => NULL,
                
                // KOEFISIEN
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
                
                // SUMBER DANA
                'id_dana' => $sumberDana->iddana,
                'nama_dana' => $sumberDana->namadana,
                'kode_dana' => $sumberDana->kodedana,
                
                // PAJAK
                'pajak' => 0.00,
                'pajak_murni' => NULL,
                'totalpajak' => NULL,
                
                // AUDIT TRAIL
                'created_user' => auth()->id() ?? null,
                'createddate' => NULL,
                'createdtime' => NULL,
                'updated_user' => auth()->id() ?? null,
                'updateddate' => NULL,
                'updatedtime' => NULL,
                'update_at' => now(),
                
                // STATUS & FLAGS
                'active' => 1,
                'is_locked' => NULL,
                'akun_locked' => NULL,
                'ssh_locked' => 0,
                
                // FIELDS LAIN
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
            // 10. TAMBAHKAN DATA SSH & JENIS STANDAR HARGA
            // ================================================
            if ($request->id_standar_harga) {
                $columns = DB::select("SHOW COLUMNS FROM data_rka LIKE 'id_standar_harga'");
                if (count($columns) > 0) {
                    $insertData['id_standar_harga'] = $request->id_standar_harga;
                }
            }

            if ($request->jenis_standar_harga) {
                $jenisColumns = DB::select("SHOW COLUMNS FROM data_rka LIKE 'jenis_standar_harga'");
                if (count($jenisColumns) > 0) {
                    $insertData['jenis_standar_harga'] = $request->jenis_standar_harga;
                }
            }

            if ($sshData && !empty($sshData->spek)) {
                $insertData['spek_komponen'] = $sshData->spek;
            }

            if ($request->tkdn) {
                $tkdnColumns = DB::select("SHOW COLUMNS FROM data_rka LIKE 'tkdn'");
                if (count($tkdnColumns) > 0) {
                    $insertData['tkdn'] = $request->tkdn;
                }
            }

            // ================================================
            // 11. SIMPAN KE DATABASE
            // ================================================
            $idRka = DB::table('data_rka')->insertGetId($insertData);

            // ================================================
            // 12. JIKA INI RINCIAN PERTAMA DARI PAKET BARU
            // UPDATE idsubtitle-nya KE ID RECORD INI
            // ================================================
            if ($isNewPaket && $idSubtitleToUse === null) {
                DB::table('data_rka')
                    ->where('id', $idRka)
                    ->update(['idsubtitle' => $idRka]);
                
                $idSubtitleToUse = $idRka;
                
                Log::info('🆕 PAKET CREATED', [
                    'id_rka' => $idRka,
                    'idsubtitle' => $idSubtitleToUse,
                    'subtitle_teks' => $subtitleTeks
                ]);
            }

            DB::commit();

            // ================================================
            // 13. LOGGING
            // ================================================
            Log::info('✅ RINCIAN CREATED', [
                'id_rka' => $idRka,
                'id_sub_kegiatan' => $request->id_rinci_sub_bl,
                'idsubtitle' => $idSubtitleToUse,
                'paket' => $subtitleTeks,
                'mintag' => $mintagTeks,
                'komponen' => $request->uraian,
                'total_harga' => $totalHarga,
                'jenis_standar_harga' => $request->jenis_standar_harga
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Rincian belanja berhasil ditambahkan',
                'data' => [
                    'id' => $idRka,
                    'idsubtitle' => $idSubtitleToUse,
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
            Log::info('=== SHOW RINCIAN START ===', ['id_sub_bl' => $id]);
            
            // ===============================
            // 1. AMBIL DATA SUB KEGIATAN
            // ===============================
            $subKegiatan = DB::table('data_sub_keg_bl as dskb')
                ->select('dskb.*', 'du.nama_skpd as nama_unit')
                ->leftJoin('data_unit as du', function ($join) {
                    $join->on('dskb.id_skpd', '=', 'du.id_skpd')
                        ->where('du.tahun_anggaran', 2025);
                })
                ->where('dskb.id_sub_bl', $id)  // ✅ Cari berdasarkan id_sub_bl
                ->where('dskb.tahun_anggaran', 2025)
                ->where('dskb.active', 1)
                ->first();

            if (!$subKegiatan) {
                Log::error('Sub kegiatan tidak ditemukan', ['id_sub_bl' => $id]);
                return redirect()->route('rkpd.renja.index')
                    ->with('error', 'Data sub kegiatan tidak ditemukan');
            }

            Log::info('Sub kegiatan found', [
                'id' => $subKegiatan->id,              // 24 (PK internal)
                'id_sub_bl' => $subKegiatan->id_sub_bl, // 16651
                'kode_sbl' => $subKegiatan->kode_sbl
            ]);

            // ===============================
            // 2. AMBIL SUMBER DANA
            // ===============================
            $sumberDana = DB::table('data_dana_sub_keg')
                ->where('idsubbl', $subKegiatan->id_sub_bl)  // ✅ GUNAKAN id_sub_bl
                ->where('tahun_anggaran', 2025)
                ->where('active', 1)
                ->get();

            // Fallback: cari berdasarkan kode_sbl jika tidak ketemu
            if ($sumberDana->isEmpty()) {
                $sumberDana = DB::table('data_dana_sub_keg')
                    ->where('kode_sbl', $subKegiatan->kode_sbl)
                    ->where('tahun_anggaran', 2025)
                    ->where('active', 1)
                    ->get();
            }

            // ===============================
            // 3. AMBIL INDIKATOR
            // ===============================
            $indikator = DB::table('data_sub_keg_indikator')
                ->where('idsubbl', $subKegiatan->id_sub_bl)  // ✅ GUNAKAN id_sub_bl
                ->where('tahun_anggaran', 2025)
                ->where('active', 1)
                ->get();

            // Fallback: cari berdasarkan kode_sbl jika tidak ketemu
            if ($indikator->isEmpty()) {
                $indikator = DB::table('data_sub_keg_indikator')
                    ->where('kode_sbl', $subKegiatan->kode_sbl)
                    ->where('tahun_anggaran', 2025)
                    ->where('active', 1)
                    ->get();
            }

            // ===============================
            // 4. AMBIL SEMUA DATA RKA
            // ===============================
            $allRka = DB::table('data_rka')
                ->where('idsubbl', $subKegiatan->id_sub_bl)  // ✅ GUNAKAN id_sub_bl
                ->where('tahun_anggaran', 2025)
                ->where('active', 1)
                ->where(function($q) {
                    $q->whereNull('ket_bl_teks')
                    ->orWhere('ket_bl_teks', '!=', '--- PAKET/KELOMPOK ---');
                })
                ->orderBy('idsubtitle')
                ->orderBy('ket_bl_teks')
                ->orderBy('kode_akun')
                ->orderBy('id')
                ->get();

            // Fallback: cari berdasarkan kode_sbl jika tidak ketemu
            if ($allRka->isEmpty()) {
                $allRka = DB::table('data_rka')
                    ->where('kode_sbl', $subKegiatan->kode_sbl)
                    ->where('tahun_anggaran', 2025)
                    ->where('active', 1)
                    ->where(function($q) {
                        $q->whereNull('ket_bl_teks')
                        ->orWhere('ket_bl_teks', '!=', '--- PAKET/KELOMPOK ---');
                    })
                    ->orderBy('idsubtitle')
                    ->orderBy('ket_bl_teks')
                    ->orderBy('kode_akun')
                    ->orderBy('id')
                    ->get();
            }

            Log::info('Total RKA records:', [
                'kode_sbl' => $subKegiatan->kode_sbl,
                'count' => $allRka->count()
            ]);

            // ===============================
            // 5. KELOMPOKKAN DATA HIERARKIS
            // ===============================
            $dataTerkelompok = [];
            $totalKeseluruhan = 0;

            $hashtagMap = [];
            foreach ($allRka as $item) {
                if ($item->idsubtitle && !isset($hashtagMap[$item->idsubtitle])) {
                    $hashtagMap[$item->idsubtitle] = [
                        'subtitle_teks' => $item->subtitle_teks,
                        'is_paket' => $item->is_paket,
                        'jenis_bl' => $item->jenis_bl
                    ];
                }
            }

            foreach ($allRka as $item) {
                $hashtagKey = $item->idsubtitle ?: 'no_paket_' . $item->id;
                $hashtagInfo = $hashtagMap[$hashtagKey] ?? [
                    'subtitle_teks' => $item->subtitle_teks ?? 'Tanpa Paket',
                    'is_paket' => $item->is_paket,
                    'jenis_bl' => $item->jenis_bl
                ];
                
                $mintag = $item->ket_bl_teks ?: 'Tanpa Kategori';
                $kodeRekening = $item->kode_akun . ' ' . $item->nama_akun;

                if (!isset($dataTerkelompok[$hashtagKey])) {
                    $dataTerkelompok[$hashtagKey] = [
                        'title' => $hashtagInfo['subtitle_teks'],
                        'idsubtitle' => $item->idsubtitle,
                        'is_paket' => $hashtagInfo['is_paket'],
                        'jenis_bl' => $hashtagInfo['jenis_bl'],
                        'total' => 0,
                        'mintag' => []
                    ];
                }

                if (!isset($dataTerkelompok[$hashtagKey]['mintag'][$mintag])) {
                    $dataTerkelompok[$hashtagKey]['mintag'][$mintag] = [
                        'title' => $mintag,
                        'total' => 0,
                        'rekening' => []
                    ];
                }

                if (!isset($dataTerkelompok[$hashtagKey]['mintag'][$mintag]['rekening'][$kodeRekening])) {
                    $dataTerkelompok[$hashtagKey]['mintag'][$mintag]['rekening'][$kodeRekening] = [
                        'title' => $kodeRekening,
                        'kode_akun' => $item->kode_akun,
                        'nama_akun' => $item->nama_akun,
                        'total' => 0,
                        'items' => []
                    ];
                }

                $itemTotal = $item->total_harga ?? (($item->volume ?? 0) * ($item->harga_satuan ?? 0));
                
                $dataTerkelompok[$hashtagKey]['mintag'][$mintag]['rekening'][$kodeRekening]['items'][] = [
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

                $dataTerkelompok[$hashtagKey]['mintag'][$mintag]['rekening'][$kodeRekening]['total'] += $itemTotal;
                $dataTerkelompok[$hashtagKey]['mintag'][$mintag]['total'] += $itemTotal;
                $dataTerkelompok[$hashtagKey]['total'] += $itemTotal;
                $totalKeseluruhan += $itemTotal;
            }

            Log::info('Data Terkelompok Summary:', [
                'kode_sbl' => $subKegiatan->kode_sbl,
                'total_rka_records' => $allRka->count(),
                'total_hashtag' => count($dataTerkelompok),
                'total_keseluruhan' => $totalKeseluruhan
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

            $tipeMapping = [
                '1' => 'SSH',
                '2' => 'SBU',
                '3' => 'HSPK',
                '4' => 'ASB'
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

            if ($jenisStandarHarga && isset($tipeMapping[$jenisStandarHarga])) {
                $query->where('tipe_standar_harga', $tipeMapping[$jenisStandarHarga]);
            }

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
            $subtitleTeks = $request->input('subtitle_teks');
            
            if (!$idPaketBelanja) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID Paket tidak valid',
                    'data' => []
                ], 400);
            }
            
            // Jika temporary ID, gunakan subtitle_teks
            if (str_starts_with($idPaketBelanja, 'new_')) {
                if (!$subtitleTeks) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Paket baru belum memiliki kategori',
                        'data' => [],
                        'count' => 0
                    ]);
                }
                
                $paketSubtitle = $subtitleTeks;
            } else {
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
                
                $paketSubtitle = $paket->subtitle_teks;
            }
            
            // Ambil semua mintag UNIK yang sudah ada untuk subtitle_teks ini
            $mintagList = DB::table('data_rka')
                ->select('ket_bl_teks')
                ->where('subtitle_teks', $paketSubtitle)
                ->where('tahun_anggaran', 2025)
                ->where('active', 1)
                ->whereNotNull('ket_bl_teks')
                ->where('ket_bl_teks', '!=', '')
                ->where('ket_bl_teks', '!=', '--- PAKET/KELOMPOK ---')
                ->groupBy('ket_bl_teks')
                ->orderBy('ket_bl_teks')
                ->get();
            
            // Format data
            $formattedData = $mintagList->map(function($item) {
                $displayText = preg_replace('/^\[\-\]\s*/', '', $item->ket_bl_teks);
                
                return [
                    'value' => $item->ket_bl_teks,
                    'text' => $displayText
                ];
            });
            
            Log::info('MINTAG LIST', [
                'paket_id' => $idPaketBelanja,
                'subtitle' => $paketSubtitle,
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
                'id_paket_belanja' => 'required',
                'nama_mintag' => 'required|string|max:500'
            ]);
            
            // Tambahkan prefix [-] jika belum ada
            $mintag = $request->nama_mintag;
            if (!preg_match('/^\[\-\]/', $mintag)) {
                $mintag = '[-] ' . $mintag;
            }
            
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