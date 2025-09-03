<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\Referensi\Program;
use App\Models\Referensi\BidangUrusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProgramController extends Controller
{
    public function index()
    {
        $data = collect();
        try {
            $programs = Program::with(['bidangUrusan.urusan'])
                ->join('bidang_urusan', 'program.id_bidang_urusan', '=', 'bidang_urusan.id')
                ->join('urusan', 'bidang_urusan.id_urusan', '=', 'urusan.id')
                ->select([
                    'program.*',
                    'bidang_urusan.nama_bidang_urusan',
                    'bidang_urusan.kode_bidang_urusan',
                    'urusan.nama_urusan',
                    'urusan.kode_urusan',
                    'urusan.id as id_urusan',
                    'bidang_urusan.id as id_bidang_urusan'
                ])
                ->orderBy('urusan.kode_urusan', 'asc')
                ->orderBy('bidang_urusan.kode_bidang_urusan', 'asc')
                ->orderBy('program.kode_program', 'asc')
                ->get();

            // Group by id_urusan untuk struktur yang sesuai dengan view
            $data = $programs->groupBy('id_urusan')->map(function ($group) {
                // Sort setiap group berdasarkan kode_bidang_urusan dan kode_program
                return $group->sortBy([
                    ['kode_bidang_urusan', 'asc'],
                    ['kode_program', 'asc']
                ]);
            });
        } catch (\Exception $e) {
            \Log::error('Error fetching program data: ' . $e->getMessage());
            $data = collect();
        }

        // Get list bidang urusan for dropdown (dengan data urusan)
        $listBidangUrusan = BidangUrusan::with('urusan')
            ->join('urusan', 'bidang_urusan.id_urusan', '=', 'urusan.id')
            ->select([
                'bidang_urusan.*',
                'urusan.nama_urusan',
                'urusan.kode_urusan'
            ])
            ->orderBy('urusan.nama_urusan')
            ->orderBy('bidang_urusan.nama_bidang_urusan')
            ->get();

        return view('referensi.program.index', compact('data', 'listBidangUrusan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_program' => 'required|string|max:20|unique:program,kode_program',
            'nama_program' => 'required|string|max:255',
            'id_bidang_urusan' => 'required|exists:bidang_urusan,id'
        ], [
            'kode_program.required' => 'Kode program wajib diisi.',
            'kode_program.unique' => 'Kode program sudah digunakan.',
            'nama_program.required' => 'Nama program wajib diisi.',
            'id_bidang_urusan.required' => 'Bidang urusan wajib dipilih.',
            'id_bidang_urusan.exists' => 'Bidang urusan tidak valid.'
        ]);

        try {
            // ID akan otomatis di-generate oleh model
            Program::create([
                'kode_program' => $request->kode_program,
                'nama_program' => $request->nama_program,
                'id_bidang_urusan' => $request->id_bidang_urusan,
                'time_stamp' => now()
            ]);

            return redirect()->route('referensi.program.index')
                ->with('success', 'Program berhasil ditambahkan.');
        } catch (\Exception $e) {
            \Log::error('Error creating program: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menambahkan program.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $program = Program::with(['bidangUrusan.urusan'])->findOrFail($id);

            $listBidangUrusan = BidangUrusan::with('urusan')
                ->join('urusan', 'bidang_urusan.id_urusan', '=', 'urusan.id')
                ->select([
                    'bidang_urusan.*',
                    'urusan.nama_urusan',
                    'urusan.kode_urusan'
                ])
                ->orderBy('urusan.nama_urusan')
                ->orderBy('bidang_urusan.nama_bidang_urusan')
                ->get();

            return view('referensi.program.edit', compact('program', 'listBidangUrusan'));
        } catch (\Exception $e) {
            return redirect()->route('referensi.program.index')
                ->with('error', 'Program tidak ditemukan.');
        }
    }

    public function update(Request $request, $id)
    {
        $program = Program::findOrFail($id);

        $request->validate([
            'kode_program' => 'required|string|max:20|unique:program,kode_program,' . $id,
            'nama_program' => 'required|string|max:255',
            'id_bidang_urusan' => 'required|exists:bidang_urusan,id'
        ], [
            'kode_program.required' => 'Kode program wajib diisi.',
            'kode_program.unique' => 'Kode program sudah digunakan.',
            'nama_program.required' => 'Nama program wajib diisi.',
            'id_bidang_urusan.required' => 'Bidang urusan wajib dipilih.',
            'id_bidang_urusan.exists' => 'Bidang urusan tidak valid.'
        ]);

        try {
            $program->update([
                'kode_program' => $request->kode_program,
                'nama_program' => $request->nama_program,
                'id_bidang_urusan' => $request->id_bidang_urusan,
                'time_stamp' => now()
            ]);

            return redirect()->route('referensi.program.index')
                ->with('success', 'Program berhasil diupdate.');
        } catch (\Exception $e) {
            \Log::error('Error updating program: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal mengupdate program.')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $program = Program::findOrFail($id);
            $program->delete();

            return redirect()->route('referensi.program.index')
                ->with('success', 'Program berhasil dihapus.');
        } catch (\Exception $e) {
            \Log::error('Error deleting program: ' . $e->getMessage());
            return redirect()->route('referensi.program.index')
                ->with('error', 'Gagal menghapus program.');
        }
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:program,id'
        ]);

        try {
            $deletedCount = Program::whereIn('id', $request->ids)->delete();

            return redirect()->route('referensi.program.index')
                ->with('success', "Berhasil menghapus {$deletedCount} program.");
        } catch (\Exception $e) {
            \Log::error('Error bulk deleting program: ' . $e->getMessage());
            return redirect()->route('referensi.program.index')
                ->with('error', 'Gagal menghapus program terpilih.');
        }
    }
}
