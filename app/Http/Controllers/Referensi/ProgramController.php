<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\Referensi\Program;
use App\Models\Referensi\BidangUrusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProgramController extends Controller
{
    public function index()
    {
        // UBAH: Tidak perlu lagi query data di sini
        // Hanya get list bidang urusan untuk dropdown di modal
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

        return view('referensi.program.index', compact('listBidangUrusan'));
    }

    // METHOD BARU untuk Ajax Server-Side
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $query = Program::with(['bidangUrusan.urusan'])
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
                ]);

            // Total records tanpa filter
            $totalRecords = $query->count();

            // Global search
            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('program.kode_program', 'like', "%{$search}%")
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

                // Columns: 0=checkbox, 1=kode_program, 2=nama_program, 3=urusan_group(hidden), 4=bidang_group(hidden), 5=actions
                $columns = [
                    'program.id',
                    'program.kode_program',
                    'program.nama_program',
                    'urusan.nama_urusan',
                    'bidang_urusan.nama_bidang_urusan',
                    'program.id'
                ];

                if (isset($columns[$orderColumnIndex])) {
                    $query->orderBy($columns[$orderColumnIndex], $orderDir);
                }
            } else {
                // Default sorting
                $query->orderBy('urusan.kode_urusan', 'asc')
                    ->orderBy('bidang_urusan.kode_bidang_urusan', 'asc')
                    ->orderBy('program.kode_program', 'asc');
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
                    'kode_program' => $item->kode_program,
                    'nama_program' => $item->nama_program,
                    'urusan_group' => '[URUSAN] ' . $item->nama_urusan,
                    'bidang_group' => '[BIDANG URUSAN] ' . $item->nama_bidang_urusan,
                    'kode_urusan' => $item->kode_urusan,
                    'nama_urusan' => $item->nama_urusan,
                    'kode_bidang_urusan' => $item->kode_bidang_urusan,
                    'nama_bidang_urusan' => $item->nama_bidang_urusan,
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
            'kode_program' => 'required|string|max:20',
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
            Program::create([
                'kode_program' => $request->kode_program,
                'nama_program' => $request->nama_program,
                'id_bidang_urusan' => $request->id_bidang_urusan,
                'user_id' => Auth::id(),
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
                'id_user' => session('id_user'),
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
