<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\Referensi\Urusan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class UrusanController extends Controller
{
    public function index()
    {
        return view('referensi.urusan.index');
    }

    // Method baru untuk Ajax DataTables
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $query = Urusan::query();

            // Total records tanpa filter
            $totalRecords = $query->count();

            // Global search
            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('kode_urusan', 'like', "%{$search}%")
                        ->orWhere('nama_urusan', 'like', "%{$search}%");
                });
            }

            // Total records setelah filter
            $totalFiltered = $query->count();

            // Sorting
            if ($request->has('order')) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderDir = $request->order[0]['dir'];

                $columns = ['id', 'kode_urusan', 'nama_urusan'];

                if (isset($columns[$orderColumnIndex])) {
                    $query->orderBy($columns[$orderColumnIndex], $orderDir);
                }
            } else {
                $query->orderBy('id', 'asc');
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
        $validator = Validator::make($request->all(), [
            'kode_urusan' => 'required|max:10|unique:urusan,kode_urusan',
            'nama_urusan' => 'required|max:255',
        ], [
            'kode_urusan.required' => 'Kode urusan wajib diisi.',
            'kode_urusan.unique' => 'Kode urusan sudah digunakan.',
            'kode_urusan.max' => 'Kode urusan maksimal 10 karakter.',
            'nama_urusan.required' => 'Nama urusan wajib diisi.',
            'nama_urusan.max' => 'Nama urusan maksimal 255 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Gagal menambah data urusan. Silakan periksa input Anda.');
        }

        try {
            DB::transaction(function () use ($request) {
                $maxId = DB::table('urusan')->lockForUpdate()->max('id') ?? 0;
                $newId = $maxId + 1;

                DB::table('urusan')->insert([
                    'id' => $newId,
                    'kode_urusan' => $request->kode_urusan,
                    'nama_urusan' => $request->nama_urusan,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });

            return redirect()->route('referensi.urusan.index')
                ->with('success', 'Data urusan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambah data urusan. ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $urusan = Urusan::findOrFail($id);
            return view('referensi.urusan.edit', compact('urusan'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('referensi.urusan.index')
                ->with('error', 'Data urusan tidak ditemukan.');
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'kode_urusan' => 'required|max:10|unique:urusan,kode_urusan,' . $id,
            'nama_urusan' => 'required|max:255',
        ], [
            'kode_urusan.required' => 'Kode urusan wajib diisi.',
            'kode_urusan.unique' => 'Kode urusan sudah digunakan.',
            'kode_urusan.max' => 'Kode urusan maksimal 10 karakter.',
            'nama_urusan.required' => 'Nama urusan wajib diisi.',
            'nama_urusan.max' => 'Nama urusan maksimal 255 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Gagal memperbarui data urusan. Silakan periksa input Anda.');
        }

        try {
            $urusan = Urusan::findOrFail($id);
            $urusan->update([
                'kode_urusan' => $request->kode_urusan,
                'nama_urusan' => $request->nama_urusan,
            ]);

            return redirect()->route('referensi.urusan.index')
                ->with('success', 'Data urusan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data urusan. ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $urusan = Urusan::findOrFail($id);

            if ($urusan->bidangUrusan()->exists()) {
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus urusan karena masih memiliki bidang urusan terkait.');
            }

            $namaUrusan = $urusan->nama_urusan;
            $urusan->delete();

            return redirect()->route('referensi.urusan.index')
                ->with('success', "Data urusan '{$namaUrusan}' berhasil dihapus.");
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()
                ->with('error', 'Data urusan tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data urusan. ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:urusan,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Data yang dipilih tidak valid.');
        }

        try {
            $ids = $request->ids;

            $urusanWithRelations = Urusan::whereIn('id', $ids)
                ->whereHas('bidangUrusan')
                ->pluck('nama_urusan')
                ->toArray();

            if (!empty($urusanWithRelations)) {
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus urusan: ' . implode(', ', $urusanWithRelations) . ' karena masih memiliki bidang urusan terkait.');
            }

            $deletedCount = Urusan::whereIn('id', $ids)->delete();

            return redirect()->back()
                ->with('success', "{$deletedCount} data urusan berhasil dihapus.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data urusan. ' . $e->getMessage());
        }
    }
}
