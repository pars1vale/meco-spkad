<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\Referensi\BidangUrusan;
use App\Models\Referensi\Urusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BidangUrusanController extends Controller
{
    public function index()
    {
        // UBAH: Tidak perlu lagi query data di sini
        // Hanya get list urusan untuk dropdown di modal
        $listUrusan = Urusan::orderBy('nama_urusan')->get();

        return view('referensi.bidang-urusan.index', compact('listUrusan'));
    }

    // METHOD BARU untuk Ajax Server-Side
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $query = BidangUrusan::with('urusan')
                ->join('urusan', 'bidang_urusan.id_urusan', '=', 'urusan.id')
                ->select([
                    'bidang_urusan.*',
                    'urusan.nama_urusan',
                    'urusan.kode_urusan'
                ]);

            // Total records tanpa filter
            $totalRecords = $query->count();

            // Global search
            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('bidang_urusan.kode_bidang_urusan', 'like', "%{$search}%")
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

                // Columns: 0=checkbox, 1=kode_bidang, 2=nama_bidang, 3=urusan_group(hidden), 4=actions
                $columns = [
                    'bidang_urusan.id',
                    'bidang_urusan.kode_bidang_urusan',
                    'bidang_urusan.nama_bidang_urusan',
                    'urusan.nama_urusan',
                    'bidang_urusan.id'
                ];

                if (isset($columns[$orderColumnIndex])) {
                    $query->orderBy($columns[$orderColumnIndex], $orderDir);
                }
            } else {
                // Default sorting
                $query->orderBy('urusan.nama_urusan', 'asc')
                    ->orderBy('bidang_urusan.kode_bidang_urusan', 'asc');
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
                    'kode_bidang_urusan' => $item->kode_bidang_urusan,
                    'nama_bidang_urusan' => $item->nama_bidang_urusan,
                    'urusan_group' => '[URUSAN] ' . $item->kode_urusan . ' ' . $item->nama_urusan,
                    'kode_urusan' => $item->kode_urusan,
                    'nama_urusan' => $item->nama_urusan,
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
            BidangUrusan::create([
                'kode_bidang_urusan' => $request->kode_bidang_urusan,
                'nama_bidang_urusan' => $request->nama_bidang_urusan,
                'id_urusan' => $request->id_urusan,
                'id_user' => session('id_user'),
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
                'id_user' => session('id_user'),
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
