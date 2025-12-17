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
        // UBAH: Tidak perlu lagi query data di sini
        // Hanya get list program untuk dropdown di modal
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

        return view('referensi.kegiatan.index', compact('listProgram'));
    }

    // METHOD BARU untuk Ajax Server-Side
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $query = Kegiatan::with(['program.bidangUrusan.urusan'])
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
                ]);

            // Total records tanpa filter
            $totalRecords = $query->count();

            // Global search
            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('kegiatan.kode_kegiatan', 'like', "%{$search}%")
                        ->orWhere('kegiatan.nama_kegiatan', 'like', "%{$search}%")
                        ->orWhere('program.kode_program', 'like', "%{$search}%")
                        ->orWhere('program.nama_program', 'like', "%{$search}%")
                        ->orWhere('bidang_urusan.kode_bidang_urusan', 'like', "%{$search}%")
                        ->orWhere('bidang_urusan.nama_bidang_urusan', 'like', "%{$search}%")
                        ->orWhere('urusan.kode_urusan', 'like', "%{$search}%")
                        ->orWhere('urusan.nama_urusan', 'like', "%{$search}%");
                });
            }

            // Total records setelah filter
            $totalFiltered = $query->count();

            // Sorting
            if ($request->has('order')) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderDir = $request->order[0]['dir'];

                // Columns: 0=checkbox, 1=kode_kegiatan, 2=nama_kegiatan, 3=urusan_group, 4=bidang_group, 5=program_group, 6=actions
                $columns = [
                    'kegiatan.id',
                    'kegiatan.kode_kegiatan',
                    'kegiatan.nama_kegiatan',
                    'urusan.nama_urusan',
                    'bidang_urusan.nama_bidang_urusan',
                    'program.nama_program',
                    'kegiatan.id'
                ];

                if (isset($columns[$orderColumnIndex])) {
                    $query->orderBy($columns[$orderColumnIndex], $orderDir);
                }
            } else {
                // Default sorting
                $query->orderBy('urusan.kode_urusan', 'asc')
                    ->orderBy('bidang_urusan.kode_bidang_urusan', 'asc')
                    ->orderBy('program.kode_program', 'asc')
                    ->orderBy('kegiatan.kode_kegiatan', 'asc');
            }

            // Pagination
            $start = $request->start ?? 0;
            $length = $request->length ?? 10;

            $data = $query->skip($start)
                ->take($length)
                ->get();

            // Format data untuk DataTables
            $formattedData = [];
            foreach ($data as $item) {
                $formattedData[] = [
                    'id' => $item->id,
                    'kode_kegiatan' => $item->kode_kegiatan,
                    'nama_kegiatan' => $item->nama_kegiatan,
                    'urusan_group' => '[URUSAN] ' . $item->kode_urusan . ' ' . $item->nama_urusan,
                    'bidang_group' => '[BIDANG URUSAN] ' . $item->kode_bidang_urusan . ' ' . $item->nama_bidang_urusan,
                    'program_group' => '[PROGRAM] ' . $item->kode_program . ' ' . $item->nama_program,
                    'kode_urusan' => $item->kode_urusan,
                    'nama_urusan' => $item->nama_urusan,
                    'kode_bidang_urusan' => $item->kode_bidang_urusan,
                    'nama_bidang_urusan' => $item->nama_bidang_urusan,
                    'kode_program' => $item->kode_program,
                    'nama_program' => $item->nama_program,
                    'actions' => $item->id
                ];
            }

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalFiltered,
                'data' => $formattedData
            ]);
        }

        return response()->json(['error' => 'Invalid request'], 400);
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
            Kegiatan::create([
                'kode_kegiatan' => $request->kode_kegiatan,
                'nama_kegiatan' => $request->nama_kegiatan,
                'id_program' => $request->id_program,
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
