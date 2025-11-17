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

    /**
     * Get Sub Kegiatan berdasarkan SKPD yang dipilih
     */
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
            ], [
                'id_skpd.required' => 'SKPD harus dipilih',
                'id_sub_kegiatan.required' => 'Sub Kegiatan harus dipilih',
                'sumber_dana.required' => 'Minimal 1 sumber dana harus ditambahkan',
                'sumber_dana.*.id_sumber_dana.required' => 'Sumber dana harus dipilih',
                'sumber_dana.*.pagu.required' => 'Pagu harus diisi',
                'sumber_dana.*.pagu.numeric' => 'Pagu harus berupa angka',
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
                'kode_sub_skpd' => $dataUnit->kode_skpd ?? '', // Ambil dari data_unit
                'id_skpd' => $subKegiatanData->id_skpd,
                'id_sub_bl' => null,
                'nama_sub_skpd' => $dataUnit->nama_skpd ?? '', // Ambil dari data_unit
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
                // Ambil detail sumber dana
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

            // Commit transaction
            DB::commit();

            Log::info('Renja berhasil disimpan', [
                'id_sub_keg_bl' => $idSubKegBl,
                'kode_bl' => $kode_bl,
                'kode_sbl' => $kode_sbl,
                'total_pagu' => $totalPagu,
                'jumlah_sumber_dana' => count($validated['sumber_dana'])
            ]);

            return redirect()->route('rkpd.renja.index')
                ->with('success', 'Sub Kegiatan berhasil ditambahkan dengan ' . count($validated['sumber_dana']) . ' sumber dana');

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
}