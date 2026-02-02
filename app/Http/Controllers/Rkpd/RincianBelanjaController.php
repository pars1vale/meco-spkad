<?php

namespace App\Http\Controllers\Rkpd;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rkpd\StorePaketBelanjaRequest;
use App\Http\Requests\Rkpd\StoreRincianRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RincianBelanjaController extends Controller
{
    /**
     * Menampilkan halaman rincian belanja sub kegiatan
     */
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
                ->where('dskb.id_sub_bl', $id)
                ->where('dskb.tahun_anggaran', 2025)
                ->where('dskb.active', 1)
                ->first();

            if (! $subKegiatan) {
                Log::error('Sub kegiatan tidak ditemukan', ['id_sub_bl' => $id]);

                return redirect()->route('rkpd.renja.index')
                    ->with('error', 'Data sub kegiatan tidak ditemukan');
            }

            Log::info('Sub kegiatan found', [
                'id' => $subKegiatan->id,
                'id_sub_bl' => $subKegiatan->id_sub_bl,
                'kode_sbl' => $subKegiatan->kode_sbl,
            ]);

            // ===============================
            // 2. AMBIL SUMBER DANA
            // ===============================
            $sumberDana = DB::table('data_dana_sub_keg')
                ->where('idsubbl', $subKegiatan->id_sub_bl)
                ->where('tahun_anggaran', 2025)
                ->where('active', 1)
                ->get();

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
                ->where('idsubbl', $subKegiatan->id_sub_bl)
                ->where('tahun_anggaran', 2025)
                ->where('active', 1)
                ->get();

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
                ->where('idsubbl', $subKegiatan->id_sub_bl)
                ->where('tahun_anggaran', 2025)
                ->where('active', 1)
                ->where(function ($q) {
                    $q->whereNull('ket_bl_teks')
                        ->orWhere('ket_bl_teks', '!=', '--- PAKET/KELOMPOK ---');
                })
                ->orderBy('idsubtitle')
                ->orderBy('ket_bl_teks')
                ->orderBy('kode_akun')
                ->orderBy('id')
                ->get();

            if ($allRka->isEmpty()) {
                $allRka = DB::table('data_rka')
                    ->where('kode_sbl', $subKegiatan->kode_sbl)
                    ->where('tahun_anggaran', 2025)
                    ->where('active', 1)
                    ->where(function ($q) {
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
                'count' => $allRka->count(),
            ]);

            // ===============================
            // 5. KELOMPOKKAN DATA HIERARKIS
            // ===============================
            $dataTerkelompok = [];
            $totalKeseluruhan = 0;

            $hashtagMap = [];
            foreach ($allRka as $item) {
                if ($item->idsubtitle && ! isset($hashtagMap[$item->idsubtitle])) {
                    $hashtagMap[$item->idsubtitle] = [
                        'subtitle_teks' => $item->subtitle_teks,
                        'is_paket' => $item->is_paket,
                        'jenis_bl' => $item->jenis_bl,
                    ];
                }
            }

            foreach ($allRka as $item) {
                $hashtagKey = $item->idsubtitle ?: 'no_paket_'.$item->id;
                $hashtagInfo = $hashtagMap[$hashtagKey] ?? [
                    'subtitle_teks' => $item->subtitle_teks ?? 'Tanpa Paket',
                    'is_paket' => $item->is_paket,
                    'jenis_bl' => $item->jenis_bl,
                ];

                $mintag = $item->ket_bl_teks ?: 'Tanpa Kategori';
                $kodeRekening = $item->kode_akun.' '.$item->nama_akun;

                if (! isset($dataTerkelompok[$hashtagKey])) {
                    $dataTerkelompok[$hashtagKey] = [
                        'title' => $hashtagInfo['subtitle_teks'],
                        'idsubtitle' => $item->idsubtitle,
                        'is_paket' => $hashtagInfo['is_paket'],
                        'jenis_bl' => $hashtagInfo['jenis_bl'],
                        'total' => 0,
                        'mintag' => [],
                    ];
                }

                if (! isset($dataTerkelompok[$hashtagKey]['mintag'][$mintag])) {
                    $dataTerkelompok[$hashtagKey]['mintag'][$mintag] = [
                        'title' => $mintag,
                        'total' => 0,
                        'rekening' => [],
                    ];
                }

                if (! isset($dataTerkelompok[$hashtagKey]['mintag'][$mintag]['rekening'][$kodeRekening])) {
                    $dataTerkelompok[$hashtagKey]['mintag'][$mintag]['rekening'][$kodeRekening] = [
                        'title' => $kodeRekening,
                        'kode_akun' => $item->kode_akun,
                        'nama_akun' => $item->nama_akun,
                        'total' => 0,
                        'items' => [],
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
                    'jenis_bl' => $item->jenis_bl,
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
                'total_keseluruhan' => $totalKeseluruhan,
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
            Log::error('Error showRincian: '.$e->getMessage());
            Log::error($e->getTraceAsString());

            return redirect()->route('rkpd.renja.index')
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    /**
     * Get akun berdasarkan jenis belanja
     */
    public function getAkunByJenisBelanja(Request $request)
    {
        try {
            $jenisBelanja = $request->input('jenis_bl');
            $tahunAnggaran = $request->input('tahun_anggaran', 2025);

            // Mapping jenis belanja ke field akun
            $jenisBelanjaMapping = [
                'BTL-GAJI' => 'is_gaji_asn',
                'BARJAS-MODAL' => 'is_barjas',
                'BUNGA' => 'is_bunga',
                'SUBSIDI' => 'is_subsidi',
                'HIBAH-BRG' => 'is_hibah_brg',
                'HIBAH' => 'is_hibah_uang',
                'BANSOS-BRG' => 'is_sosial_brg',
                'BANSOS' => 'is_sosial_uang',
                'BAGI-HASIL' => 'is_bagi_hasil',
                'BANKEU' => 'is_bankeu_umum',
                'BANKEU-KHUSUS' => 'is_bankeu_khusus',
                'BTT' => 'is_btt',
                'BOS' => 'is_bos',
                'BLUD' => 'is_bl',
                'TANAH' => 'is_modal_tanah',
            ];

            if (! isset($jenisBelanjaMapping[$jenisBelanja])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jenis belanja tidak valid: '.$jenisBelanja,
                ], 400);
            }

            $field = $jenisBelanjaMapping[$jenisBelanja];

            $akunList = DB::table('akun')
                ->where('tahun_anggaran', $tahunAnggaran)
                ->where($field, 1)
                ->orderBy('kode_akun')
                ->get();

            $data = $akunList->map(function ($akun) {
                return [
                    'id' => $akun->id,
                    'kode_akun' => $akun->kode_akun,
                    'nama_akun' => $akun->nama_akun,
                    'text' => $akun->kode_akun.' - '.$akun->nama_akun,
                    'level' => $akun->level,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data,
                'count' => $data->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading akun: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get detail akun
     */
    public function getDetailAkun(Request $request)
    {
        try {
            $akunId = $request->akun_id;

            $akun = DB::table('akun')
                ->where('id', $akunId)
                ->first();

            if (! $akun) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun tidak ditemukan',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $akun->id,
                    'kode_akun' => $akun->kode_akun,
                    'nama_akun' => $akun->nama_akun,
                    'level' => $akun->level,
                    'set_input' => $akun->set_input ?? null,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting detail akun: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan',
            ], 500);
        }
    }

    /**
     * Get list paket belanja
     */
    public function getPaketBelanjaList(Request $request)
    {
        try {
            $idRinciSubBl = $request->input('id_rinci_sub_bl');
            $tipePaket = $request->input('tipe_paket');

            Log::info('GET PAKET REQUEST', [
                'id_rinci_sub_bl' => $idRinciSubBl,
                'tipe_paket' => $tipePaket,
            ]);

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

            Log::info('PAKET FOUND', [
                'kode_sbl' => $subKegiatan->kode_sbl,
                'tipe_paket' => $tipePaket,
                'count' => $formattedData->count(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data paket berhasil dimuat',
                'data' => $formattedData,
                'count' => $formattedData->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('ERROR GET PAKET', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
                'data' => [],
            ], 500);
        }
    }

    /**
     * Store paket belanja
     */
    public function storePaketBelanja(StorePaketBelanjaRequest $request)
    {
        try {
            $subKegiatan = DB::table('data_sub_keg_bl')
                ->where('id', $request->id_rinci_sub_bl)
                ->first();

            if (! $subKegiatan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sub kegiatan tidak ditemukan',
                ], 404);
            }

            $akun = DB::table('akun')
                ->where('id', $request->id_akun)
                ->first();

            if (! $akun) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun tidak ditemukan',
                ], 404);
            }

            $tempPaketId = 'new_'.uniqid();

            Log::info('PAKET METADATA CREATED', [
                'temp_id' => $tempPaketId,
                'uraian' => $request->uraian_paket,
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
                        'kode_sbl' => $subKegiatan->kode_sbl,
                    ],
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error storing paket: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store rincian belanja - Exact copy dari RenjaController::storerincian()
     */
    public function storerincian(StoreRincianRequest $request)
    {
        try {
            // Validasi input sudah dilakukan oleh StoreRincianRequest

            DB::beginTransaction();

            // ================================================
            // 1. GET DATA SUB KEGIATAN
            // ================================================
            $subKegiatan = DB::table('data_sub_keg_bl')
                ->where('id', $request->id_rinci_sub_bl)
                ->first();

            if (! $subKegiatan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sub kegiatan tidak ditemukan',
                ], 404);
            }

            // ================================================
            // 2. GET DATA AKUN
            // ================================================
            $akun = DB::table('akun')
                ->where('id', $request->id_akun)
                ->first();

            if (! $akun) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun tidak ditemukan',
                ], 404);
            }

            $namaAkunLengkap = $akun->kode_akun.' '.$akun->nama_akun;

            // ================================================
            // 3. GET SUMBER DANA
            // ================================================
            $sumberDana = DB::table('data_dana_sub_keg')
                ->where('kode_sbl', $subKegiatan->kode_sbl)
                ->where('active', 1)
                ->first();

            if (! $sumberDana) {
                $sumberDana = DB::table('data_dana_sub_keg')
                    ->where('idsubbl', $subKegiatan->id_sub_bl)
                    ->where('active', 1)
                    ->first();
            }

            if (! $sumberDana) {
                Log::warning('SUMBER DANA NOT FOUND', [
                    'id_sub_kegiatan' => $subKegiatan->id,
                    'kode_sbl' => $subKegiatan->kode_sbl,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Sumber dana tidak ditemukan untuk sub kegiatan ini',
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
                'is_temp_id' => str_starts_with($idPaketBelanja, 'new_'),
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
                    ->where(function ($q) {
                        $q->whereNull('ket_bl_teks')
                            ->orWhere('ket_bl_teks', '!=', '--- PAKET/KELOMPOK ---');
                    })
                    ->first();

                if ($existingPaket) {
                    // Sudah ada rincian dengan paket ini
                    $idSubtitleToUse = $existingPaket->idsubtitle;
                    $isNewPaket = false;

                    Log::info('📦 EXISTING PAKET FOUND', [
                        'idsubtitle' => $idSubtitleToUse,
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
                        'idsubtitle' => $idSubtitleToUse,
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Paket belanja tidak ditemukan',
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
                    if ($koefisienStr) {
                        $koefisienStr .= ' / ';
                    }
                    $koefisienStr .= $koef.($satuan ? ' '.$satuan : '');
                }
            }
            if (empty($koefisienStr)) {
                $koefisienStr = $volume.' '.$request->satuan;
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
                'spek' => null,

                // VOLUME & HARGA
                'volume' => $volume,
                'satuan' => $request->satuan,
                'harga_satuan' => $hargaSatuan,
                'total_harga' => $totalHarga,
                'rincian' => $totalHarga,
                'volume_murni' => null,
                'harga_satuan_murni' => null,
                'rincian_murni' => null,

                // KOEFISIEN
                'koefisien' => $koefisienStr,
                'koefisien_murni' => null,
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
                'pajak_murni' => null,
                'totalpajak' => null,

                // AUDIT TRAIL
                'created_user' => auth()->id() ?? null,
                'createddate' => null,
                'createdtime' => null,
                'updated_user' => auth()->id() ?? null,
                'updateddate' => null,
                'updatedtime' => null,
                'update_at' => now(),

                // STATUS & FLAGS
                'active' => 1,
                'is_locked' => null,
                'akun_locked' => null,
                'ssh_locked' => 0,

                // FIELDS LAIN
                'id_standar_nfs' => 0,
                'idbl' => 0,
                'lokus_akun_teks' => '',
                'user1' => '',
                'user2' => '',
                'id_prop_penerima' => null,
                'id_camat_penerima' => null,
                'id_kokab_penerima' => null,
                'id_lurah_penerima' => null,
                'id_penerima' => null,
                'idkomponen' => 0.00,
                'idketerangan' => null,
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

            if ($sshData && ! empty($sshData->spek)) {
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
                    'subtitle_teks' => $subtitleTeks,
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
                'jenis_standar_harga' => $request->jenis_standar_harga,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Rincian belanja berhasil ditambahkan',
                'data' => [
                    'id' => $idRka,
                    'idsubtitle' => $idSubtitleToUse,
                    'total' => $totalHarga,
                    'koefisien' => $koefisienStr,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('❌ ERROR STORE RINCIAN', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get SSH data
     */
    public function getSshData(Request $request)
    {
        try {
            $idStandarHarga = $request->input('id_standar_harga');

            if (! $idStandarHarga) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID standar harga tidak valid',
                ], 400);
            }

            $sshData = DB::table('data_ssh')
                ->where('id_standar_harga', $idStandarHarga)
                ->where('tahun', 2025)
                ->where('id_daerah', 604)
                ->first();

            if (! $sshData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data SSH tidak ditemukan',
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
                    'tipe' => $sshData->tipe_standar_harga,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting SSH data: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Search komponen
     */
    public function searchKomponen(Request $request)
    {
        try {
            $jenisStandarHarga = $request->input('jenis_standar_harga');
            $search = $request->input('q', '');

            Log::info('SEARCH KOMPONEN', [
                'jenis' => $jenisStandarHarga,
                'search' => $search,
            ]);

            $tipeMapping = [
                '1' => 'SSH',
                '2' => 'SBU',
                '3' => 'HSPK',
                '4' => 'ASB',
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

            if (! empty($search)) {
                $query->where(function ($q) use ($search) {
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
                'count' => $results->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Error searching komponen: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get mintag list
     */
    public function getMintagList(Request $request)
    {
        try {
            $idPaketBelanja = $request->input('id_paket_belanja');
            $subtitleTeks = $request->input('subtitle_teks');

            if (! $idPaketBelanja) {
                return response()->json([
                    'success' => false,
                    'message' => 'ID Paket tidak valid',
                    'data' => [],
                ], 400);
            }

            if (str_starts_with($idPaketBelanja, 'new_')) {
                if (! $subtitleTeks) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Paket baru belum memiliki kategori',
                        'data' => [],
                        'count' => 0,
                    ]);
                }

                $paketSubtitle = $subtitleTeks;
            } else {
                $paket = DB::table('data_rka')
                    ->where('id', $idPaketBelanja)
                    ->first();

                if (! $paket) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Paket tidak ditemukan',
                        'data' => [],
                    ], 404);
                }

                $paketSubtitle = $paket->subtitle_teks;
            }

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

            $formattedData = $mintagList->map(function ($item) {
                $displayText = preg_replace('/^\[\-\]\s*/', '', $item->ket_bl_teks);

                return [
                    'value' => $item->ket_bl_teks,
                    'text' => $displayText,
                ];
            });

            Log::info('MINTAG LIST', [
                'paket_id' => $idPaketBelanja,
                'subtitle' => $paketSubtitle,
                'count' => $formattedData->count(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data mintag berhasil dimuat',
                'data' => $formattedData,
                'count' => $formattedData->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('ERROR GET MINTAG', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
                'data' => [],
            ], 500);
        }
    }

    /**
     * Store mintag
     */
    public function storeMintag(Request $request)
    {
        try {
            $request->validate([
                'id_paket_belanja' => 'required',
                'nama_mintag' => 'required|string|max:500',
            ]);

            $mintag = $request->nama_mintag;
            if (! preg_match('/^\[\-\]/', $mintag)) {
                $mintag = '[-] '.$mintag;
            }

            $displayText = preg_replace('/^\[\-\]\s*/', '', $mintag);

            return response()->json([
                'success' => true,
                'message' => 'Kategori belanja berhasil ditambahkan',
                'data' => [
                    'value' => $mintag,
                    'text' => $displayText,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('ERROR STORE MINTAG: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update rincian - placeholder untuk future implementation
     */
    public function updateRincian(Request $request, $id)
    {
        try {
            // TODO: Implement update logic
            return response()->json([
                'success' => false,
                'message' => 'Method belum diimplementasikan',
            ], 501);
        } catch (\Exception $e) {
            Log::error('Error updating rincian: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete rincian - placeholder untuk future implementation
     */
    public function destroyRincian($id)
    {
        try {
            // TODO: Implement delete logic
            return response()->json([
                'success' => false,
                'message' => 'Method belum diimplementasikan',
            ], 501);
        } catch (\Exception $e) {
            Log::error('Error deleting rincian: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
            ], 500);
        }
    }
}
