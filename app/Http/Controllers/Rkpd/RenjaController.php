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
    // public function getData(Request $request)
    // {
    // $tahunAnggaran = 2025;

    // try {
    //     $query = DB::table('data_sub_keg_bl as dskb')
    //         ->leftJoin('data_dana_sub_keg as ddsk', 'dskb.id', '=', 'ddsk.idsubbl')
    //         ->select(
    //             'dskb.id',
    //             'dskb.kode_sbl',
    //             'dskb.kode_skpd',
    //             'dskb.nama_skpd',
    //             'dskb.kode_urusan',
    //             'dskb.nama_urusan',
    //             'dskb.kode_bidang_urusan',
    //             'dskb.nama_bidang_urusan',
    //             'dskb.kode_program',
    //             'dskb.nama_program',
    //             'dskb.kode_giat',
    //             'dskb.nama_giat',
    //             'dskb.kode_sub_giat',
    //             'dskb.nama_sub_giat',
    //             'dskb.pagu',
    //             'dskb.pagumurni',
    //             'dskb.active',
    //             DB::raw('COUNT(DISTINCT ddsk.iddana) as jumlah_sumber_dana'),
    //             DB::raw('GROUP_CONCAT(DISTINCT ddsk.namadana SEPARATOR ", ") as sumber_dana_list')
    //         )
    //         ->where('dskb.tahun_anggaran', $tahunAnggaran)
    //         ->where('dskb.active', 1)
    //         ->groupBy(
    //             'dskb.id',
    //             'dskb.kode_sbl',
    //             'dskb.kode_skpd',
    //             'dskb.nama_skpd',
    //             'dskb.kode_urusan',
    //             'dskb.nama_urusan',
    //             'dskb.kode_bidang_urusan',
    //             'dskb.nama_bidang_urusan',
    //             'dskb.kode_program',
    //             'dskb.nama_program',
    //             'dskb.kode_giat',
    //             'dskb.nama_giat',
    //             'dskb.kode_sub_giat',
    //             'dskb.nama_sub_giat',
    //             'dskb.pagu',
    //             'dskb.pagumurni',
    //             'dskb.active'
    //         )
    //         ->orderBy('dskb.kode_skpd')
    //         ->orderBy('dskb.kode_urusan')
    //         ->orderBy('dskb.kode_program')
    //         ->orderBy('dskb.kode_giat')
    //         ->orderBy('dskb.kode_sub_giat');

    //     // Jika ada filter pencarian
    //     if ($request->has('search') && !empty($request->search['value'])) {
    //         $search = $request->search['value'];
    //         $query->where(function($q) use ($search) {
    //             $q->where('dskb.nama_sub_giat', 'like', "%{$search}%")
    //                 ->orWhere('dskb.kode_sub_giat', 'like', "%{$search}%")
    //                 ->orWhere('dskb.nama_skpd', 'like', "%{$search}%")
    //                 ->orWhere('dskb.kode_sbl', 'like', "%{$search}%");
    //         });
    //     }

    //     $totalRecords = DB::table('data_sub_keg_bl')
    //         ->where('tahun_anggaran', $tahunAnggaran)
    //         ->where('active', 1)
    //         ->count();

    //     $totalFiltered = $query->count(DB::raw('DISTINCT dskb.id'));

    //     // Pagination
    //     if ($request->has('start') && $request->has('length')) {
    //         $query->skip($request->start)->take($request->length);
    //     }

    //     $data = $query->get();

    //     // Format data untuk DataTable dengan grouping
    //     $formattedData = [];
    //     foreach ($data as $row) {
    //         // Hitung jumlah indikator
    //         $jumlahIndikator = DB::table('data_sub_keg_indikator')
    //             ->where('kode_sbl', $row->kode_sbl)
    //             ->where('active', 1)
    //             ->count();

    //         // Hitung jumlah usulan (contoh, sesuaikan dengan tabel Anda)
    //         $jumlahUsulan = $row->jumlah_sumber_dana ?? 0;

    //         // Badge untuk usulan (warna random seperti screenshot)
    //         $badgeColors = ['danger', 'primary', 'success', 'warning', 'info'];
    //         $randomColor = $badgeColors[array_rand($badgeColors)];
            
    //         $usulanBadge = $jumlahUsulan > 0 
    //             ? '<span class="badge badge-' . $randomColor . ' ms-2">' . $jumlahUsulan . ' Usulan Pokir</span>' 
    //             : '';

    //         // Icon checklist hijau jika ada indikator
    //         $checkIcon = $jumlahIndikator > 0 
    //             ? '<i class="ki-outline ki-check-circle fs-2 text-success ms-2"></i>' 
    //             : '';

    //         $formattedData[] = [
    //             'DT_RowIndex' => count($formattedData) + 1,
    //             'checkbox' => '',
    //             'group_skpd' => $row->kode_skpd . ' ' . $row->nama_skpd,
    //             'group_urusan' => $row->kode_urusan . ' ' . $row->nama_urusan,
    //             'group_program' => $row->kode_program . ' ' . $row->nama_program,
    //             'group_kegiatan' => $row->kode_giat . ' ' . $row->nama_giat,
    //             'sub_kegiatan' => '
    //                 <div class="d-flex align-items-center">
    //                     <button class="btn btn-sm btn-icon btn-light me-3 btn-collapse">
    //                         <i class="ki-outline ki-minus fs-3"></i>
    //                     </button>
    //                     <div>
    //                         <a href="#" class="text-primary fw-bold">' . $row->kode_sub_giat . ' ' . $row->nama_sub_giat . '</a>
    //                         ' . $checkIcon . '
    //                         ' . $usulanBadge . '
    //                     </div>
    //                 </div>
    //             ',
    //             'status_sub_kegiatan' => '<span class="badge badge-light-danger">DIKUNCI</span>',
    //             'status_rincian' => '<span class="badge badge-light-danger">DIKUNCI</span>',
    //             'sebelum_perubahan' => number_format($row->pagumurni ?? 0, 2, '.', ','),
    //             'pagu_validasi' => number_format($row->pagu ?? 0, 2, '.', ','),
    //             'total_rincian' => number_format($row->pagu ?? 0, 3, '.', ','),
    //             'total_realisasi' => '0.00',
    //             'persentase' => '0.00 %',
    //             'aksi' => ''
    //         ];
    //     }

    //     return response()->json([
    //         'draw' => intval($request->draw ?? 1),
    //         'recordsTotal' => $totalRecords,
    //         'recordsFiltered' => $totalFiltered,
    //         'data' => $formattedData
    //     ]);

    // } catch (\Exception $e) {
    //     Log::error('Error getting data: ' . $e->getMessage());
    //     return response()->json([
    //         'draw' => intval($request->draw ?? 1),
    //         'recordsTotal' => 0,
    //         'recordsFiltered' => 0,
    //         'data' => [],
    //         'error' => $e->getMessage()
    //     ]);
    // }
    // }

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

public function showRincian($id)
{
    try {
        // Ambil data sub kegiatan
        $subKegiatan = DB::table('data_sub_keg_bl as dskb')
            ->select(
                'dskb.*',
                'du.nama_skpd as nama_unit'
            )
            ->leftJoin('data_unit as du', function($join) {
                $join->on('dskb.id_skpd', '=', 'du.id_skpd')
                     ->where('du.tahun_anggaran', '=', 2025);
            })
            ->where('dskb.id', $id)
            ->where('dskb.tahun_anggaran', 2025)
            ->where('dskb.active', 1)
            ->first();

        if (!$subKegiatan) {
            return redirect()->route('rkpd.renja.index')
                ->with('error', 'Data sub kegiatan tidak ditemukan');
        }

        // Ambil data sumber dana
        $sumberDana = DB::table('data_dana_sub_keg')
            ->where('idsubbl', $id)
            ->where('tahun_anggaran', 2025)
            ->where('active', 1)
            ->get();

        // Ambil data indikator
        $indikator = DB::table('data_sub_keg_indikator')
            ->where('idsubbl', $id)
            ->where('tahun_anggaran', 2025)
            ->where('active', 1)
            ->get();

        // Sementara kosongkan rincian belanja jika tabel belum ada
        $rincianBelanja = collect([]);
        $totalPerObjek = collect([]);

        return view('rkpd.renja.rincian', compact(
            'subKegiatan',
            'sumberDana',
            'indikator',
            'rincianBelanja',
            'totalPerObjek'
        ));

    } catch (\Exception $e) {
        Log::error('Error showing rincian: ' . $e->getMessage());
        return redirect()->route('rkpd.renja.index')
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}
}