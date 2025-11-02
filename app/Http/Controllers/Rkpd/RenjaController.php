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
        
        return view('rkpd.renja.index', compact('data', 'data_unit'));
    }

    /**
     * Get Sub Kegiatan berdasarkan SKPD yang dipilih
     */
    public function getSubKegiatanBySkpd(Request $request)
    {
        $id_skpd = $request->input('id_skpd');
        $tahun_anggaran = $request->input('tahun_anggaran', 2025);

        try {
            $subKegiatan = DB::table('data_unit as du')
                ->join('bidang_urusan as bu', function($join) {
                    $join->whereRaw('bu.id IN (du.bidur_1, du.bidur_2, du.bidur_3)');
                })
                ->join('program as p', 'p.id_bidang_urusan', '=', 'bu.id')
                ->join('kegiatan as k', 'k.id_program', '=', 'p.id')
                ->join('sub_kegiatan as sk', 'sk.id_kegiatan', '=', 'k.id')
                ->select(
                    'du.id_skpd',
                    'du.kode_skpd',
                    'du.nama_skpd',
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
                ->where('du.id_skpd', $id_skpd)
                ->where('du.tahun_anggaran', $tahun_anggaran)
                ->where('bu.id', '>', 0)
                ->orderBy('bu.kode_bidang_urusan')
                ->orderBy('sk.kode_sub_kegiatan')
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
        // Implementasi store renja
        try {
            $validated = $request->validate([
                'id_skpd' => 'required|exists:data_unit,id_skpd',
                'id_sub_kegiatan' => 'required|exists:sub_kegiatan,id',
                // tambahkan field lain sesuai kebutuhan
            ]);

            // Logic simpan renja
            $renja = Renja::create($validated);

            return redirect()->route('renja.index')
                           ->with('success', 'Sub Kegiatan berhasil ditambahkan');

        } catch (\Exception $e) {
            Log::error('Error storing renja: ' . $e->getMessage());
            return redirect()->back()
                           ->with('error', 'Terjadi kesalahan saat menyimpan data')
                           ->withInput();
        }
    }
}