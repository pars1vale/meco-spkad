<?php

namespace App\Http\Controllers\Rkpd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rkpd\Renja;
use App\Models\Referensi\Urusan;
use App\Models\Referensi\BidangUrusan;
use App\Models\Referensi\Program;
use App\Models\Referensi\Kegiatan;
use App\Models\Referensi\SubKegiatan;
use App\Models\Referensi\SumberDana;
use App\Models\Pengaturan\Profil\PerangkatDaerah\DataUnit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class RenjaController extends Controller
{
    public function index()
    {
        $data = Renja::all();
        $data_unit = DataUnit::where('tahun_anggaran', 2025)
                            //  ->where('active', 1)
                             ->orderBy('nama_skpd')
                             ->get();

        $sumberdana = SumberDana::all();

        
        $daerah = DB::table('data_daerah')->select('*')->get();
        $kec = DB::table('data_kecamatan')->select('*')->get();
        $kel = DB::table('data_kelurahan')->select('*')->get();
        $bln = DB::table('data_bulan')->select('*')->get();

        

        return view('rkpd.renja.index', compact('data', 'data_unit','sumberdana','daerah','kec','kel','bln'));
    }
    public function getSubKegiatanBySkpd(Request $request)
    {
            $id_skpd = $request->input('id_skpd');
            $tahun_anggaran = $request->input('tahun_anggaran', 2025);

            try {
                // Query pertama: Sub kegiatan milik SKPD dengan indikator
                $query1 = DB::table('data_unit as du')
                    ->join('bidang_urusan as bu', function($join) {
                        $join->whereRaw('bu.id IN (du.bidur_1, du.bidur_2, du.bidur_3)');
                    })
                    ->join('program as p', 'p.id_bidang_urusan', '=', 'bu.id')
                    ->join('kegiatan as k', 'k.id_program', '=', 'p.id')
                    ->join('sub_kegiatan as sk', 'sk.id_kegiatan', '=', 'k.id')
                    ->leftJoin('data_master_indikator_subgiat as dmis', function($join) use ($tahun_anggaran) {
                        $join->on('dmis.id_sub_keg', '=', 'sk.id')
                            ->where('dmis.tahun_anggaran', '=', $tahun_anggaran)
                            ->where('dmis.active', '=', 1);
                    })
                    ->select(
                        'du.id_skpd',
                        'du.kode_skpd',
                        'du.nama_skpd',
                        'du.bidur_1',
                        'du.bidur_2',
                        'du.bidur_3',
                        'bu.id as id_bidang_urusan',
                        'bu.kode_bidang_urusan',
                        'bu.nama_bidang_urusan',
                        'p.id as id_program',
                        'p.kode_program',
                        'p.nama_program',
                        'k.id as id_kegiatan',
                        'k.kode_kegiatan',
                        'k.nama_kegiatan',
                        'sk.id as id_sub_kegiatan',
                        'sk.kode_sub_kegiatan',
                        'sk.nama_sub_kegiatan',
                        'dmis.id as id_indikator',
                        'dmis.indikator',
                        'dmis.satuan'
                    )
                    ->where('du.id_skpd', $id_skpd)
                    ->where('du.tahun_anggaran', $tahun_anggaran)
                    ->where('bu.id', '>', 0);

                // Query kedua: Sub kegiatan dari urusan X (id_urusan = 20) dengan indikator
                $query2 = DB::table('data_unit as du')
                    ->join('bidang_urusan as bu', 'bu.id_urusan', '=', DB::raw('20'))
                    ->join('program as p', 'p.id_bidang_urusan', '=', 'bu.id')
                    ->join('kegiatan as k', 'k.id_program', '=', 'p.id')
                    ->join('sub_kegiatan as sk', 'sk.id_kegiatan', '=', 'k.id')
                    ->leftJoin('data_master_indikator_subgiat as dmis', function($join) use ($tahun_anggaran) {
                        $join->on('dmis.id_sub_keg', '=', 'sk.id')
                            ->where('dmis.tahun_anggaran', '=', $tahun_anggaran)
                            ->where('dmis.active', '=', 1);
                    })
                    ->select(
                        'du.id_skpd',
                        'du.kode_skpd',
                        'du.nama_skpd',
                        'du.bidur_1',
                        'du.bidur_2',
                        'du.bidur_3',
                        'bu.id as id_bidang_urusan',
                        'bu.kode_bidang_urusan',
                        'bu.nama_bidang_urusan',
                        'p.id as id_program',
                        'p.kode_program',
                        'p.nama_program',
                        'k.id as id_kegiatan',
                        'k.kode_kegiatan',
                        'k.nama_kegiatan',
                        'sk.id as id_sub_kegiatan',
                        'sk.kode_sub_kegiatan',
                        'sk.nama_sub_kegiatan',
                        'dmis.id as id_indikator',
                        'dmis.indikator',
                        'dmis.satuan'
                    )
                    ->where('du.id_skpd', $id_skpd)
                    ->where('du.tahun_anggaran', $tahun_anggaran);

                // Gabungkan kedua query dengan UNION ALL
                $subKegiatan = $query1
                    ->unionAll($query2)
                    ->orderBy('kode_bidang_urusan')
                    ->orderBy('kode_sub_kegiatan')
                    ->get();

                return response()->json([
                    'success' => true,
                    'data' => $subKegiatan,
                    'count' => $subKegiatan->count()
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
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'id_skpd' => 'required|integer',
                'id_sub_kegiatan' => 'required|integer',
                'sumber_dana' => 'required|array|min:1',
                'sumber_dana.*.id_sumber_dana' => 'required|integer',
                'sumber_dana.*.pagu' => 'required|numeric|min:0',
                'indikator' => 'nullable|array',
                'indikator.*.id_indikator' => 'nullable|integer',
                'indikator.*.indikator_text' => 'nullable|string',
                'indikator.*.satuan' => 'nullable|string',
                'indikator.*.target' => 'required_with:indikator.*.indikator_text|string',
            ], [
                'id_skpd.required' => 'SKPD harus dipilih',
                'id_sub_kegiatan.required' => 'Sub Kegiatan harus dipilih',
                'sumber_dana.required' => 'Minimal 1 sumber dana harus ditambahkan',
                'sumber_dana.*.id_sumber_dana.required' => 'Sumber dana harus dipilih',
                'sumber_dana.*.pagu.required' => 'Pagu harus diisi',
                'sumber_dana.*.pagu.numeric' => 'Pagu harus berupa angka',
                'indikator.*.target.required_with' => 'Target indikator harus diisi',
            ]);

            // Ambil tahun anggaran
            $tahunAnggaran = 2025;

            // Ambil data SKPD/Unit terlebih dahulu
            $dataUnit = DB::table('data_unit')
                ->where('id_skpd', $validated['id_skpd'])
                ->where('tahun_anggaran', $tahunAnggaran)
                ->first();

            if (!$dataUnit) {
                return redirect()->back()
                    ->with('error', 'Data SKPD tidak ditemukan')
                    ->withInput();
            }

            // Ambil data lengkap sub kegiatan dengan UNION
            $query1 = DB::table('data_unit as du')
                ->join('bidang_urusan as bu', function($join) {
                    $join->whereRaw('bu.id IN (du.bidur_1, du.bidur_2, du.bidur_3)');
                })
                ->join('program as p', 'p.id_bidang_urusan', '=', 'bu.id')
                ->join('kegiatan as k', 'k.id_program', '=', 'p.id')
                ->join('sub_kegiatan as sk', 'sk.id_kegiatan', '=', 'k.id')
                ->leftJoin('urusan as u', 'u.id', '=', 'bu.id_urusan')
                ->select(
                    'du.id_skpd',
                    'du.kode_skpd',
                    'du.nama_skpd',
                    'u.id as id_urusan',
                    'u.kode_urusan',
                    'u.nama_urusan',
                    'bu.id as id_bidang_urusan',
                    'bu.kode_bidang_urusan',
                    'bu.nama_bidang_urusan',
                    'p.id as id_program',
                    'p.kode_program',
                    'p.nama_program',
                    'k.id as id_kegiatan',
                    'k.kode_kegiatan',
                    'k.nama_kegiatan',
                    'sk.id as id_sub_kegiatan',
                    'sk.kode_sub_kegiatan',
                    'sk.nama_sub_kegiatan'
                )
                ->where('du.id_skpd', $validated['id_skpd'])
                ->where('sk.id', $validated['id_sub_kegiatan'])
                ->where('du.tahun_anggaran', $tahunAnggaran)
                ->where('bu.id', '>', 0);

            // Query kedua: Sub kegiatan dari urusan X (id_urusan = 20)
            $query2 = DB::table('data_unit as du')
                ->join('bidang_urusan as bu', 'bu.id_urusan', '=', DB::raw('20'))
                ->join('program as p', 'p.id_bidang_urusan', '=', 'bu.id')
                ->join('kegiatan as k', 'k.id_program', '=', 'p.id')
                ->join('sub_kegiatan as sk', 'sk.id_kegiatan', '=', 'k.id')
                ->leftJoin('urusan as u', 'u.id', '=', 'bu.id_urusan')
                ->select(
                    'du.id_skpd',
                    'du.kode_skpd',
                    'du.nama_skpd',
                    'u.id as id_urusan',
                    'u.kode_urusan',
                    'u.nama_urusan',
                    'bu.id as id_bidang_urusan',
                    'bu.kode_bidang_urusan',
                    'bu.nama_bidang_urusan',
                    'p.id as id_program',
                    'p.kode_program',
                    'p.nama_program',
                    'k.id as id_kegiatan',
                    'k.kode_kegiatan',
                    'k.nama_kegiatan',
                    'sk.id as id_sub_kegiatan',
                    'sk.kode_sub_kegiatan',
                    'sk.nama_sub_kegiatan'
                )
                ->where('du.id_skpd', $validated['id_skpd'])
                ->where('sk.id', $validated['id_sub_kegiatan'])
                ->where('du.tahun_anggaran', $tahunAnggaran);

            // Gabungkan dan ambil hasil
            $subKegiatanData = $query1->unionAll($query2)->first();

            if (!$subKegiatanData) {
                return redirect()->back()
                    ->with('error', 'Data sub kegiatan tidak ditemukan')
                    ->withInput();
            }

            // Hitung total pagu dari semua sumber dana
            $totalPagu = 0;
            foreach ($validated['sumber_dana'] as $dana) {
                $totalPagu += $dana['pagu'];
            }

            // Generate kode dari ID
            // Format: id_skpd.id_sub_skpd.id_program.id_kegiatan.id_sub_kegiatan
            $id_sub_skpd = $dataUnit->id_setup_unit ?? 0;
            
            $kode_bl = $subKegiatanData->id_skpd . '.' . 
                    $id_sub_skpd . '.' . 
                    $subKegiatanData->id_program . '.' . $subKegiatanData->id_kegiatan;
            
            $kode_sbl = $subKegiatanData->id_skpd . '.' . 
                        $id_sub_skpd . '.' . 
                        $subKegiatanData->id_program . '.' . 
                        $subKegiatanData->id_kegiatan . '.' . 
                        $subKegiatanData->id_sub_kegiatan;

            // Generate ID unik
            $id_unik_sub_bl = uniqid('subbl_', true);

            // Log untuk debugging
            Log::info('Generated Codes', [
                'kode_bl' => $kode_bl,
                'kode_sbl' => $kode_sbl,
                'id_skpd' => $subKegiatanData->id_skpd,
                'id_sub_skpd' => $id_sub_skpd,
                'id_program' => $subKegiatanData->id_program,
                'id_kegiatan' => $subKegiatanData->id_kegiatan,
                'id_sub_kegiatan' => $subKegiatanData->id_sub_kegiatan,
            ]);

            // Mulai database transaction
            DB::beginTransaction();

            // 1. Insert ke tabel data_sub_keg_bl
            $idSubKegBl = DB::table('data_sub_keg_bl')->insertGetId([
                'id_sub_skpd' => $id_sub_skpd,
                'id_lokasi' => null,
                'id_label_kokab' => null,
                'nama_dana' => null,
                'no_sub_giat' => $subKegiatanData->kode_sub_kegiatan,
                'kode_giat' => $subKegiatanData->kode_kegiatan,
                'id_program' => $subKegiatanData->id_program,
                'nama_lokasi' => '604',
                'waktu_akhir' => $request->waktu_akhir,
                'pagu_n_lalu' => 0,
                'id_urusan' => $subKegiatanData->id_urusan,
                'id_unik_sub_bl' => $id_unik_sub_bl,
                'id_sub_giat' => $subKegiatanData->id_sub_kegiatan,
                'label_prov' => null,
                'kode_program' => $subKegiatanData->kode_program,
                'kode_sub_giat' => $subKegiatanData->kode_sub_kegiatan,
                'no_program' => $subKegiatanData->kode_program,
                'kode_urusan' => $subKegiatanData->kode_urusan,
                'kode_bidang_urusan' => $subKegiatanData->kode_bidang_urusan,
                'nama_program' => $subKegiatanData->nama_program,
                'target_4' => null,
                'target_5' => null,
                'id_bidang_urusan' => $subKegiatanData->id_bidang_urusan,
                'nama_bidang_urusan' => $subKegiatanData->nama_bidang_urusan,
                'target_3' => null,
                'no_giat' => $subKegiatanData->kode_kegiatan,
                'id_label_prov' => 0,
                'waktu_awal' => $request->waktu_awal,
                'pagumurni' => $totalPagu,
                'pagu' => $totalPagu,
                'pagu_simda' => 0,
                'output_sub_giat' => null,
                'sasaran' => null,
                'indikator' => null,
                'id_dana' => null,
                'nama_sub_giat' => $subKegiatanData->nama_sub_kegiatan,
                'pagu_n_depan' => $request->pagu_n_depan,
                'satuan' => null,
                'id_rpjmd' => 0,
                'id_giat' => $subKegiatanData->id_kegiatan,
                'id_label_pusat' => 0,
                'nama_giat' => $subKegiatanData->nama_kegiatan,
                'kode_skpd' => $subKegiatanData->kode_skpd,
                'nama_skpd' => $subKegiatanData->nama_skpd,
                'kode_sub_skpd' => $dataUnit->kode_skpd ?? '',
                'id_skpd' => $subKegiatanData->id_skpd,
                'id_sub_bl' => null,
                'nama_sub_skpd' => $dataUnit->nama_skpd ?? '',
                'target_1' => null,
                'nama_urusan' => $subKegiatanData->nama_urusan,
                'target_2' => null,
                'label_kokab' => null,
                'label_pusat' => null,
                'pagu_keg' => $totalPagu,
                'pagu_fmis' => 0,
                'id_bl' => null,
                'kode_bl' => $kode_bl,
                'kode_sbl' => $kode_sbl,
                'active' => 1,
                'update_at' => now(),
                'tahun_anggaran' => $tahunAnggaran
            ]);

            // 2. Insert multiple sumber dana ke tabel data_dana_sub_keg
            foreach ($validated['sumber_dana'] as $dana) {
                $sumberDanaInfo = SumberDana::find($dana['id_sumber_dana']);

                if ($sumberDanaInfo) {
                    DB::table('data_dana_sub_keg')->insert([
                        'namadana' => $sumberDanaInfo->nama_dana,
                        'kodedana' => $sumberDanaInfo->kode_dana,
                        'iddana' => $dana['id_sumber_dana'],
                        'iddanasubbl' => null,
                        'pagudana' => $dana['pagu'],
                        'kode_sbl' => $kode_sbl,
                        'idsubbl' => $idSubKegBl,
                        'is_locked' => 0,
                        'active' => 1,
                        'update_at' => now(),
                        'tahun_anggaran' => $tahunAnggaran
                    ]);
                }
            }

            // 3. Insert indikator ke tabel data_sub_keg_indikator (DIPINDAHKAN KE LUAR LOOP)
            if (!empty($validated['indikator'])) {
                foreach ($validated['indikator'] as $index => $indikator) {
                    // Hilangkan format dari target (titik pemisah ribuan)
                    $targetValue = str_replace('.', '', $indikator['target']);
                    
                    DB::table('data_sub_keg_indikator')->insert([
                        'outputteks' => $indikator['indikator_text'],
                        'targetoutput' => $targetValue,
                        'satuanoutput' => $indikator['satuan'],
                        'idoutputbl' => $indikator['id_indikator'] ?? 0,
                        'targetoutputteks' => $targetValue,
                        'kode_sbl' => $kode_sbl,
                        'idsubbl' => $idSubKegBl,
                        'bobot_kinerja' => '1',
                        'active' => 1,
                        'update_at' => now(),
                        'tahun_anggaran' => $tahunAnggaran
                    ]);
                }
            }

            // Commit transaction
            DB::commit();

            Log::info('Renja berhasil disimpan', [
                'id_sub_keg_bl' => $idSubKegBl,
                'kode_bl' => $kode_bl,
                'kode_sbl' => $kode_sbl,
                'total_pagu' => $totalPagu,
                'jumlah_sumber_dana' => count($validated['sumber_dana']),
                'jumlah_indikator' => count($validated['indikator'] ?? [])
            ]);

            $message = 'Sub Kegiatan berhasil ditambahkan dengan ' . count($validated['sumber_dana']) . ' sumber dana';
            if (!empty($validated['indikator'])) {
                $message .= ' dan ' . count($validated['indikator']) . ' indikator';
            }

            return redirect()->route('rkpd.renja.index')
                ->with('success', $message);

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error storing renja: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage())
                ->withInput();
        }
    }
    

    public function getData(Request $request)
    {
        $tahunAnggaran = 2025;

        try {
            $query = DB::table('data_sub_keg_bl as dskb')
                ->leftJoin('data_dana_sub_keg as ddsk', 'dskb.id', '=', 'ddsk.idsubbl')
                ->select(
                    'dskb.id',
                    'dskb.kode_sbl',
                    'dskb.kode_skpd',
                    'dskb.nama_skpd',
                    'dskb.kode_urusan',
                    'dskb.nama_urusan',
                    'dskb.kode_bidang_urusan',
                    'dskb.nama_bidang_urusan',
                    'dskb.kode_program',
                    'dskb.nama_program',
                    'dskb.kode_giat',
                    'dskb.nama_giat',
                    'dskb.kode_sub_giat',
                    'dskb.nama_sub_giat',
                    'dskb.pagu',
                    'dskb.pagumurni',
                    'dskb.active',
                    DB::raw('COUNT(DISTINCT ddsk.iddana) as jumlah_sumber_dana'),
                    DB::raw('GROUP_CONCAT(DISTINCT ddsk.namadana SEPARATOR ", ") as sumber_dana_list')
                )
                ->where('dskb.tahun_anggaran', $tahunAnggaran)
                ->where('dskb.active', 1)
                ->groupBy(
                    'dskb.id',
                    'dskb.kode_sbl',
                    'dskb.kode_skpd',
                    'dskb.nama_skpd',
                    'dskb.kode_urusan',
                    'dskb.nama_urusan',
                    'dskb.kode_bidang_urusan',
                    'dskb.nama_bidang_urusan',
                    'dskb.kode_program',
                    'dskb.nama_program',
                    'dskb.kode_giat',
                    'dskb.nama_giat',
                    'dskb.kode_sub_giat',
                    'dskb.nama_sub_giat',
                    'dskb.pagu',
                    'dskb.pagumurni',
                    'dskb.active'
                )
                ->orderBy('dskb.kode_skpd')
                ->orderBy('dskb.kode_urusan')
                ->orderBy('dskb.kode_program')
                ->orderBy('dskb.kode_giat')
                ->orderBy('dskb.kode_sub_giat');

            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function($q) use ($search) {
                    $q->where('dskb.nama_sub_giat', 'like', "%{$search}%")
                        ->orWhere('dskb.kode_sub_giat', 'like', "%{$search}%")
                        ->orWhere('dskb.nama_skpd', 'like', "%{$search}%")
                        ->orWhere('dskb.kode_sbl', 'like', "%{$search}%");
                });
            }

            $totalRecords = DB::table('data_sub_keg_bl')
                ->where('tahun_anggaran', $tahunAnggaran)
                ->where('active', 1)
                ->count();

            $totalFiltered = $query->count(DB::raw('DISTINCT dskb.id'));

            if ($request->has('start') && $request->has('length')) {
                $query->skip($request->start)->take($request->length);
            }

            $data = $query->get();

            $formattedData = [];
            foreach ($data as $row) {
                $jumlahIndikator = DB::table('data_sub_keg_indikator')
                    ->where('kode_sbl', $row->kode_sbl)
                    ->where('active', 1)
                    ->count();

                $jumlahUsulan = $row->jumlah_sumber_dana ?? 0;

                $badgeColors = ['danger', 'primary', 'success', 'warning', 'info'];
                $randomColor = $badgeColors[array_rand($badgeColors)];
                
                $usulanBadge = $jumlahUsulan > 0 
                    ? '<span class="badge badge-' . $randomColor . ' ms-2">' . $jumlahUsulan . ' Usulan Pokir</span>' 
                    : '';

                $checkIcon = $jumlahIndikator > 0 
                    ? '<i class="ki-outline ki-check-circle fs-2 text-success ms-2"></i>' 
                    : '';

                // ========== PERBAIKAN: TAMBAHKAN TOMBOL AKSI ==========
                $aksiButtons = '
                    <div class="btn-group">
                        <button class="btn btn-sm btn-icon btn-light btn-active-light-primary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="ki-outline ki-category fs-3"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li class="px-3 py-2">
                                <div class="text-gray-800 fw-bold fs-6">Pilih Aksi</div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item btn-lihat-sub-kegiatan" href="#" data-id="' . $row->id . '">
                                    <i class="ki-outline ki-file-down fs-5 me-2 text-primary"></i>
                                    Lihat Sub Kegiatan
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item btn-lihat-rincian" href="#" data-id="' . $row->id . '">
                                    <i class="ki-outline ki-document fs-5 me-2 text-info"></i>
                                    Lihat Rincian Belanja
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item btn-rka-paket" href="#" data-id="' . $row->id . '">
                                    <i class="ki-outline ki-package fs-5 me-2 text-success"></i>
                                    RKA Paket / Kelompok
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item btn-rka-rincian" href="#" data-id="' . $row->id . '">
                                    <i class="ki-outline ki-copy fs-5 me-2 text-warning"></i>
                                    RKA Rincian Belanja
                                </a>
                            </li>
                        </ul>
                    </div>';
                // =====================================================

                $formattedData[] = [
                    'DT_RowIndex' => count($formattedData) + 1,
                    'checkbox' => '',
                    'group_skpd' => $row->kode_skpd . ' ' . $row->nama_skpd,
                    'group_urusan' => $row->kode_urusan . ' ' . $row->nama_urusan,
                    'group_program' => $row->kode_program . ' ' . $row->nama_program,
                    'group_kegiatan' => $row->kode_giat . ' ' . $row->nama_giat,
                    'sub_kegiatan' => '
                        <div class="d-flex align-items-center">
                            <button class="btn btn-sm btn-icon btn-light me-3 btn-collapse">
                                <i class="ki-outline ki-minus fs-3"></i>
                            </button>
                            <div>
                                <a href="#" class="text-primary fw-bold">' . $row->kode_sub_giat . ' ' . $row->nama_sub_giat . '</a>
                                ' . $checkIcon . '
                                ' . $usulanBadge . '
                            </div>
                        </div>
                    ',
                    'status_sub_kegiatan' => '<span class="badge badge-light-danger">DIKUNCI</span>',
                    'status_rincian' => '<span class="badge badge-light-danger">DIKUNCI</span>',
                    'sebelum_perubahan' => number_format($row->pagumurni ?? 0, 2, '.', ','),
                    'pagu_validasi' => number_format($row->pagu ?? 0, 2, '.', ','),
                    'total_rincian' => number_format($row->pagu ?? 0, 3, '.', ','),
                    'total_realisasi' => '0.00',
                    'persentase' => '0.00 %',
                    'aksi' => $aksiButtons  // ← UBAH DARI '' MENJADI $aksiButtons
                ];
            }

            return response()->json([
                'draw' => intval($request->draw ?? 1),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalFiltered,
                'data' => $formattedData
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
            
            Log::info('GET AKUN REQUEST', [
                'jenis_bl' => $jenisBelanja,
                'tahun' => $tahunAnggaran
            ]);
            
            // Mapping jenis belanja ke field boolean di tabel akun
            $mappingField = [
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
                'TANAH' => 'is_modal_tanah'
            ];
            
            // Validasi jenis belanja
            if (!isset($mappingField[$jenisBelanja])) {
                Log::error('Jenis belanja tidak valid', ['jenis_bl' => $jenisBelanja]);
                return response()->json([
                    'success' => false,
                    'message' => 'Jenis belanja tidak valid: ' . $jenisBelanja
                ], 400);
            }
            
            $field = $mappingField[$jenisBelanja];
            
            Log::info('Querying akun', [
                'field' => $field,
                'tahun' => $tahunAnggaran
            ]);
            
            // Query akun berdasarkan field boolean
            $akunList = DB::table('akun')
                ->where('tahun_anggaran', $tahunAnggaran)
                ->where('active', 1)
                ->where($field, 1)
                ->where('set_input', 1) // Hanya akun yang bisa diinput
                ->orderBy('kode_akun')
                ->get(['id', 'kode_akun', 'nama_akun', 'level']);
            
            Log::info('Query result', [
                'count' => $akunList->count(),
                'sample' => $akunList->take(2)->toArray()
            ]);
            
            // Format data untuk select2
            $data = $akunList->map(function($akun) {
                return [
                    'id' => $akun->id,
                    'kode_akun' => $akun->kode_akun,
                    'nama_akun' => $akun->nama_akun,
                    'text' => $akun->kode_akun . ' - ' . $akun->nama_akun,
                    'level' => $akun->level
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'count' => $data->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error loading akun', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getDetailAkun(Request $request)
    {
        $akunId = $request->akun_id;
        
        try {
            $akun = DB::table('akun')->where('id', $akunId)->first();
            
            if (!$akun) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun tidak ditemukan'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $akun->id,
                    'kode_akun' => $akun->kode_akun,
                    'nama_akun' => $akun->nama_akun,
                    'level' => $akun->level,
                    'set_input' => $akun->set_input
                ]
            ]);
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

    public function storePaketBelanja(Request $request)
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
            DB::rollBack();
            
            Log::error('ERROR STORE PAKET TO RKA', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function storerincian(Request $request)
    {
        try {
            $request->validate([
                'id_rinci_sub_bl' => 'required',
                'jenis_bl' => 'required',
                'id_akun' => 'required|exists:akun,id',
                'kode_rekening' => 'required',
                'nama_rekening' => 'required',
                'tipe_paket' => 'required',
                'id_paket_belanja' => 'nullable|integer',
                'uraian' => 'required',
                
                // Koefisien array
                'koefisien' => 'nullable|array',
                'koefisien.*' => 'nullable|numeric',
                'satuan_koefisien' => 'nullable|array',
                'satuan_koefisien.*' => 'nullable|string',
                
                // Volume & satuan utama
                'volume' => 'required|numeric',
                'satuan' => 'required',
                'harga_satuan' => 'required|numeric',
                
                // Fields tambahan (OPTIONAL)
                'id_standar_harga' => 'nullable|integer', // ← UBAH JADI NULLABLE
                'jenis_standar_harga' => 'nullable|string',
                'tkdn' => 'nullable|string',
                'spesifikasi_komponen' => 'nullable|string',
                'keterangan' => 'nullable|string'
            ]);

            DB::beginTransaction();

            // Get info sub kegiatan
            $subKegiatan = DB::table('data_sub_keg_bl')
                ->where('id', $request->id_rinci_sub_bl)
                ->first();

            if (!$subKegiatan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sub kegiatan tidak ditemukan'
                ], 404);
            }

            // Get info akun
            $akun = DB::table('akun')
                ->where('id', $request->id_akun)
                ->first();

            if (!$akun) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akun tidak ditemukan'
                ], 404);
            }

            // Get info sumber dana
            $sumberDana = DB::table('data_dana_sub_keg')
                ->where('idsubbl', $request->id_rinci_sub_bl)
                ->where('active', 1)
                ->first();

            // Get nama paket jika ada
            $namaPaket = null;
            $ketBlTeks = null;
            if ($request->id_paket_belanja) {
                $paket = DB::table('data_rka')
                    ->where('id', $request->id_paket_belanja)
                    ->first();
                $namaPaket = $paket->subtitle_teks ?? null;
                $ketBlTeks = $paket->ket_bl_teks ?? null;
            }

            // ================================================
            // HITUNG KOEFISIEN TOTAL & VOLUME DETAIL
            // ================================================
            $koefisienArray = $request->koefisien ?? [];
            $satuanKoefArray = $request->satuan_koefisien ?? [];
            
            // Hitung koefisien total
            $koefisienTotal = 1;
            foreach ($koefisienArray as $koef) {
                if ($koef && is_numeric($koef)) {
                    $koefisienTotal *= floatval($koef);
                }
            }
            
            // Volume detail
            $volum1 = isset($koefisienArray[0]) ? floatval($koefisienArray[0]) : 0;
            $volum2 = isset($koefisienArray[1]) ? floatval($koefisienArray[1]) : 0;
            $volum3 = isset($koefisienArray[2]) ? floatval($koefisienArray[2]) : 0;
            $volum4 = isset($koefisienArray[3]) ? floatval($koefisienArray[3]) : 0;
            
            // Satuan detail
            $sat1 = $satuanKoefArray[0] ?? '';
            $sat2 = $satuanKoefArray[1] ?? '';
            $sat3 = $satuanKoefArray[2] ?? '';
            $sat4 = $satuanKoefArray[3] ?? '';

            // Calculate total
            $volume = floatval($request->volume);
            $hargaSatuan = floatval($request->harga_satuan);
            $totalHarga = $volume * $hargaSatuan;

            // ================================================
            // AMBIL DATA SSH JIKA ADA
            // ================================================
            $sshData = null;
            if ($request->id_standar_harga) {
                $sshData = DB::table('data_ssh')
                    ->where('id_standar_harga', $request->id_standar_harga)
                    ->first();
            }

            // ================================================
            // INSERT RINCIAN DETAIL KE DATA_RKA
            // ================================================
            $insertData = [
                // ===== IDENTITAS =====
                'id_rinci_sub_bl' => $request->id_rinci_sub_bl,
                'kode_sbl' => $subKegiatan->kode_sbl,
                'kode_bl' => $subKegiatan->kode_bl,
                'tahun_anggaran' => $subKegiatan->tahun_anggaran ?? 2025,
                
                // ===== JENIS & AKUN =====
                'jenis_bl' => $request->jenis_bl,
                'kode_akun' => $akun->kode_akun,
                'nama_akun' => $akun->nama_akun,
                
                // ===== LINK KE PAKET =====
                'is_paket' => $request->tipe_paket,
                'idsubtitle' => $request->id_paket_belanja,
                'subtitle_teks' => $namaPaket,
                
                // ===== URAIAN & SPESIFIKASI =====
                'ket_bl_teks' => $ketBlTeks ?? $request->uraian,
                'spek' => $request->uraian,
                'nama_komponen' => $request->uraian,
                'spek_komponen' => $request->spesifikasi_komponen,
                'substeks' => $request->keterangan,
                
                // ===== VOLUME & HARGA UTAMA =====
                'volume' => $volume,
                'volume_murni' => $volume,
                'satuan' => $request->satuan,
                'harga_satuan' => $hargaSatuan,
                'harga_satuan_murni' => $hargaSatuan,
                'total_harga' => $totalHarga,
                'rincian' => $totalHarga,
                'rincian_murni' => $totalHarga,
                
                // ===== KOEFISIEN =====
                'koefisien' => $koefisienTotal,
                'koefisien_murni' => $koefisienTotal,
                
                // ===== VOLUME DETAIL =====
                'volum1' => $volum1,
                'volum2' => $volum2,
                'volum3' => $volum3,
                'volum4' => $volum4,
                'sat1' => $sat1,
                'sat2' => $sat2,
                'sat3' => $sat3,
                'sat4' => $sat4,
                
                // ===== SUMBER DANA =====
                'id_dana' => $sumberDana->iddana ?? null,
                'nama_dana' => $sumberDana->namadana ?? null,
                'kode_dana' => $sumberDana->kodedana ?? null,
                
                // ===== AUDIT =====
                'created_user' => auth()->id() ?? null,
                'createddate' => date('Y-m-d'),
                'createdtime' => date('H:i:s'),
                'updated_user' => auth()->id() ?? null,
                'updateddate' => date('Y-m-d'),
                'updatedtime' => date('H:i:s'),
                
                // ===== STATUS =====
                'active' => 1,
                'is_locked' => 0,
                'akun_locked' => 0,
                'ssh_locked' => 0,
                
                // ===== FIELDS LAIN =====
                'id_daerah' => 604,
                'id_standar_nfs' => 0,
                'idbl' => null,
                'idsubbl' => $request->id_rinci_sub_bl,
                'totalpajak' => 0,
                'pajak' => 0,
                'pajak_murni' => 0,
                'update_at' => now()
            ];

            // ================================================
            // TAMBAHKAN DATA SSH JIKA ADA DAN KOLOM EXISTS
            // ================================================
            if ($sshData) {
                // Cek apakah kolom id_standar_harga exist
                $columns = DB::select("SHOW COLUMNS FROM data_rka LIKE 'id_standar_harga'");
                
                if (count($columns) > 0) {
                    $insertData['id_standar_harga'] = $request->id_standar_harga;
                }
                
                // Simpan info SSH di field lain yang exist
                $insertData['spek_komponen'] = $sshData->spek ?? $request->spesifikasi_komponen;
                
                // Jika ada kolom tkdn
                $tkdnColumns = DB::select("SHOW COLUMNS FROM data_rka LIKE 'tkdn'");
                if (count($tkdnColumns) > 0) {
                    $insertData['tkdn'] = $request->tkdn;
                }
                
                // Jika ada kolom jenis_standar_harga
                $jenisColumns = DB::select("SHOW COLUMNS FROM data_rka LIKE 'jenis_standar_harga'");
                if (count($jenisColumns) > 0) {
                    $insertData['jenis_standar_harga'] = $request->jenis_standar_harga;
                }
            }

            $idRka = DB::table('data_rka')->insertGetId($insertData);

            DB::commit();

            Log::info('RINCIAN CREATED', [
                'id_rka' => $idRka,
                'id_ssh' => $request->id_standar_harga,
                'total' => $totalHarga
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Rincian belanja berhasil ditambahkan',
                'data' => [
                    'id' => $idRka,
                    'total' => $totalHarga,
                    'koefisien' => $koefisienTotal
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('ERROR STORE RINCIAN: ' . $e->getMessage());
            
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


}