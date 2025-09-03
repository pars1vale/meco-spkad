<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\Referensi\BidangUrusan;
use App\Models\Referensi\Urusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BidangUrusanController extends Controller
{
    // public function index()
    // {

    //     $data = DB::table('data_prog_keg')
    //         ->select('nama_urusan', 'kode_bidang_urusan', 'nama_bidang_urusan')
    //         ->distinct()
    //         ->orderBy('nama_urusan')
    //         ->get()
    //         ->groupBy('nama_urusan');

    //     // Pastikan $data adalah Collection kosong jika tidak ada hasil
    //     if ($data->isEmpty()) {
    //         $data = collect();
    //     }

    //     return view('referensi.bidang-urusan.index', compact('data'));
    // }

    public function index()
    {
        $data = collect();
        try {
            $bidangUrusans = BidangUrusan::with('urusan')
                ->join('urusan', 'bidang_urusan.id_urusan', '=', 'urusan.id')
                ->select([
                    'bidang_urusan.*',
                    'urusan.nama_urusan',
                    'urusan.kode_urusan'
                ])
                ->orderBy('urusan.nama_urusan', 'asc')
                ->orderBy('bidang_urusan.kode_bidang_urusan', 'asc')
                ->orderBy('bidang_urusan.nama_bidang_urusan', 'asc')
                ->get();

            // Group by nama urusan
            $data = $bidangUrusans->groupBy('nama_urusan')->map(function ($group) {
                return $group->sortBy([
                    ['kode_bidang_urusan', 'asc'],
                    ['nama_bidang_urusan', 'asc']
                ]);
            });
        } catch (\Exception $e) {
            \Log::error('Error fetching bidang urusan data: ' . $e->getMessage());
            $data = collect();
        }

        // Get list urusan for dropdown
        $listUrusan = Urusan::orderBy('nama_urusan')->get();

        return view('referensi.bidang-urusan.index', compact('data', 'listUrusan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_bidang_urusan' => 'required|max:20|unique:bidang_urusan,kode_bidang_urusan',
            'nama_bidang_urusan' => 'required|max:255',
            'id_urusan' => 'required|exists:urusan,id'
        ], [
            'kode_bidang_urusan.required' => 'Kode bidang urusan wajib diisi.',
            'kode_bidang_urusan.unique' => 'Kode bidang urusan sudah digunakan.',
            'nama_bidang_urusan.required' => 'Nama bidang urusan wajib diisi.',
            'id_urusan.required' => 'Urusan wajib dipilih.',
            'id_urusan.exists' => 'Urusan tidak valid.'
        ]);

        try {
            // ID akan otomatis di-generate oleh model
            BidangUrusan::create([
                'kode_bidang_urusan' => $request->kode_bidang_urusan,
                'nama_bidang_urusan' => $request->nama_bidang_urusan,
                'id_urusan' => $request->id_urusan,
                'time_stamp' => now()
            ]);

            return redirect()->route('referensi.bidang-urusan.index')
                ->with('success', 'Bidang urusan berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Log::error('Error creating bidang urusan: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menambahkan bidang urusan.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $bidangUrusan = BidangUrusan::with('urusan')->findOrFail($id);
            $listUrusan = Urusan::orderBy('nama_urusan')->get();

            return view('referensi.bidang-urusan.edit', compact('bidangUrusan', 'listUrusan'));
        } catch (\Exception $e) {
            return redirect()->route('referensi.bidang-urusan.index')
                ->with('error', 'Bidang urusan tidak ditemukan.');
        }
    }

    public function update(Request $request, $id)
    {
        $bidangUrusan = BidangUrusan::findOrFail($id);

        $request->validate([
            'kode_bidang_urusan' => 'required|max:20|unique:bidang_urusan,kode_bidang_urusan,' . $id,
            'nama_bidang_urusan' => 'required|max:255',
            'id_urusan' => 'required|exists:urusan,id'
        ], [
            'kode_bidang_urusan.required' => 'Kode bidang urusan wajib diisi.',
            'kode_bidang_urusan.unique' => 'Kode bidang urusan sudah digunakan.',
            'nama_bidang_urusan.required' => 'Nama bidang urusan wajib diisi.',
            'id_urusan.required' => 'Urusan wajib dipilih.',
            'id_urusan.exists' => 'Urusan tidak valid.'
        ]);

        try {
            $bidangUrusan->update([
                'kode_bidang_urusan' => $request->kode_bidang_urusan,
                'nama_bidang_urusan' => $request->nama_bidang_urusan,
                'id_urusan' => $request->id_urusan,
                'time_stamp' => now()
            ]);

            return redirect()->route('referensi.bidang-urusan.index')
                ->with('success', 'Bidang urusan berhasil diupdate.');
        } catch (\Exception $e) {
            \Log::error('Error updating bidang urusan: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengupdate bidang urusan.')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $bidangUrusan = BidangUrusan::findOrFail($id);
            $bidangUrusan->delete();

            return redirect()->route('referensi.bidang-urusan.index')
                ->with('success', 'Bidang urusan berhasil dihapus.');
        } catch (\Exception $e) {
            \Log::error('Error deleting bidang urusan: ' . $e->getMessage());
            return redirect()->route('referensi.bidang-urusan.index')
                ->with('error', 'Gagal menghapus bidang urusan.');
        }
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:bidang_urusan,id'
        ]);

        try {
            $deletedCount = BidangUrusan::whereIn('id', $request->ids)->delete();

            return redirect()->route('referensi.bidang-urusan.index')
                ->with('success', "Berhasil menghapus {$deletedCount} bidang urusan.");
        } catch (\Exception $e) {
            \Log::error('Error bulk deleting bidang urusan: ' . $e->getMessage());
            return redirect()->route('referensi.bidang-urusan.index')
                ->with('error', 'Gagal menghapus bidang urusan terpilih.');
        }
    }
}
