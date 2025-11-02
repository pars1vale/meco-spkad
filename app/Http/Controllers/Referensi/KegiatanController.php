<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\Referensi\Kegiatan;
use App\Models\Referensi\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KegiatanController extends Controller
{
    public function index()
    {
        $data = collect();
        try {
            $kegiatan = Kegiatan::with(['program.bidangUrusan.urusan'])
                ->join('program', 'kegiatan.id_program', '=', 'program.id')
                ->join('bidang_urusan', 'program.id_bidang_urusan', '=', 'bidang_urusan.id')
                ->join('urusan', 'bidang_urusan.id_urusan', '=', 'urusan.id')
                ->select([
                    'kegiatan.*',
                    'program.nama_program',
                    'program.kode_program',
                    'bidang_urusan.nama_bidang_urusan',
                    'bidang_urusan.kode_bidang_urusan',
                    'urusan.nama_urusan',
                    'urusan.kode_urusan',
                    'urusan.id as id_urusan',
                    'bidang_urusan.id as id_bidang_urusan',
                    'program.id as id_program'
                ])
                ->orderBy('urusan.kode_urusan', 'asc')
                ->orderBy('bidang_urusan.kode_bidang_urusan', 'asc')
                ->orderBy('program.kode_program', 'asc')
                ->orderBy('kegiatan.kode_kegiatan', 'asc')
                ->get();

            // Group by id_urusan untuk struktur yang sesuai dengan view
            $data = $kegiatan->groupBy('id_urusan')->map(function ($group) {
                return $group->sortBy([
                    ['kode_bidang_urusan', 'asc'],
                    ['kode_program', 'asc'],
                    ['kode_kegiatan', 'asc']
                ]);
            });
        } catch (\Exception $e) {
            \Log::error('Error fetching kegiatan data: ' . $e->getMessage());
            $data = collect();
        }

        // Get list program for dropdown (dengan data urusan dan bidang urusan)
        $listProgram = Program::with(['bidangUrusan.urusan'])
            ->join('bidang_urusan', 'program.id_bidang_urusan', '=', 'bidang_urusan.id')
            ->join('urusan', 'bidang_urusan.id_urusan', '=', 'urusan.id')
            ->select([
                'program.*',
                'bidang_urusan.nama_bidang_urusan',
                'bidang_urusan.kode_bidang_urusan',
                'urusan.nama_urusan',
                'urusan.kode_urusan'
            ])
            ->orderBy('urusan.nama_urusan')
            ->orderBy('bidang_urusan.nama_bidang_urusan')
            ->orderBy('program.nama_program')
            ->get();

        return view('referensi.kegiatan.index', compact('data', 'listProgram'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_kegiatan' => 'required|string|max:20|unique:kegiatan,kode_kegiatan',
            'nama_kegiatan' => 'required|string|max:500',
            'id_program' => 'required|exists:program,id'
        ], [
            'kode_kegiatan.required' => 'Kode kegiatan wajib diisi.',
            'kode_kegiatan.unique' => 'Kode kegiatan sudah digunakan.',
            'nama_kegiatan.required' => 'Nama kegiatan wajib diisi.',
            'id_program.required' => 'Program wajib dipilih.',
            'id_program.exists' => 'Program tidak valid.'
        ]);

        try {
            // ID akan otomatis di-generate oleh model
            Kegiatan::create([
                'kode_kegiatan' => $request->kode_kegiatan,
                'nama_kegiatan' => $request->nama_kegiatan,
                'id_program' => $request->id_program,
                // ambil id user dari session
                'id_user' => session('id_user'),
                'time_stamp' => now()
            ]);

            return redirect()->route('referensi.kegiatan.index')
                ->with('success', 'Kegiatan berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Log::error('Error creating kegiatan: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menambahkan kegiatan.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $kegiatan = Kegiatan::with(['program.bidangUrusan.urusan'])->findOrFail($id);

            $listProgram = Program::with(['bidangUrusan.urusan'])
                ->join('bidang_urusan', 'program.id_bidang_urusan', '=', 'bidang_urusan.id')
                ->join('urusan', 'bidang_urusan.id_urusan', '=', 'urusan.id')
                ->select([
                    'program.*',
                    'bidang_urusan.nama_bidang_urusan',
                    'bidang_urusan.kode_bidang_urusan',
                    'urusan.nama_urusan',
                    'urusan.kode_urusan'
                ])
                ->orderBy('urusan.nama_urusan')
                ->orderBy('bidang_urusan.nama_bidang_urusan')
                ->orderBy('program.nama_program')
                ->get();

            return view('referensi.kegiatan.edit', compact('kegiatan', 'listProgram'));
        } catch (\Exception $e) {
            return redirect()->route('referensi.kegiatan.index')
                ->with('error', 'Kegiatan tidak ditemukan.');
        }
    }

    public function update(Request $request, $id)
    {
        $kegiatan = Kegiatan::findOrFail($id);

        $request->validate([
            'kode_kegiatan' => 'required|string|max:20|unique:kegiatan,kode_kegiatan,' . $id,
            'nama_kegiatan' => 'required|string|max:500',
            'id_program' => 'required|exists:program,id'
        ], [
            'kode_kegiatan.required' => 'Kode kegiatan wajib diisi.',
            'kode_kegiatan.unique' => 'Kode kegiatan sudah digunakan.',
            'nama_kegiatan.required' => 'Nama kegiatan wajib diisi.',
            'id_program.required' => 'Program wajib dipilih.',
            'id_program.exists' => 'Program tidak valid.'
        ]);

        try {
            $kegiatan->update([
                'kode_kegiatan' => $request->kode_kegiatan,
                'nama_kegiatan' => $request->nama_kegiatan,
                'id_program' => $request->id_program,
                // ambil id user dari session
                'id_user' => session('id_user'),
                'time_stamp' => now()
            ]);

            return redirect()->route('referensi.kegiatan.index')
                ->with('success', 'Kegiatan berhasil diupdate.');
        } catch (\Exception $e) {
            \Log::error('Error updating kegiatan: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengupdate kegiatan.')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $kegiatan = Kegiatan::findOrFail($id);
            $kegiatan->delete();

            return redirect()->route('referensi.kegiatan.index')
                ->with('success', 'Kegiatan berhasil dihapus.');
        } catch (\Exception $e) {
            \Log::error('Error deleting kegiatan: ' . $e->getMessage());
            return redirect()->route('referensi.kegiatan.index')
                ->with('error', 'Gagal menghapus kegiatan.');
        }
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:kegiatan,id'
        ]);

        try {
            $deletedCount = Kegiatan::whereIn('id', $request->ids)->delete();

            return redirect()->route('referensi.kegiatan.index')
                ->with('success', "Berhasil menghapus {$deletedCount} kegiatan.");
        } catch (\Exception $e) {
            \Log::error('Error bulk deleting kegiatan: ' . $e->getMessage());
            return redirect()->route('referensi.kegiatan.index')
                ->with('error', 'Gagal menghapus kegiatan terpilih.');
        }
    }
}
