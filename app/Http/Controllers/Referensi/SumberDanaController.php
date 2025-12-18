<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\Referensi\SumberDana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SumberDanaController extends Controller
{
    public function index()
    {
        return view('referensi.sumber-dana.index');
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            // Gunakan Query Builder untuk optimasi memory
            $query = DB::table('sumber_dana');

            // Total records tanpa filter
            $totalRecords = DB::table('sumber_dana')->count();

            // Global search
            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('kode_dana', 'like', "%{$search}%")
                        ->orWhere('nama_dana', 'like', "%{$search}%")
                        ->orWhere('sumber_dana', 'like', "%{$search}%")
                        ->orWhere('set_input', 'like', "%{$search}%");
                });
            }

            // Total records setelah filter
            $totalFiltered = $query->count();

            // Sorting
            if ($request->has('order')) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderDir = $request->order[0]['dir'];

                // Columns: 0=checkbox, 1=kode_dana, 2=nama_dana, 3=sumber_dana, 4=set_input, 5=actions
                $columns = [
                    'id',
                    'kode_dana',
                    'nama_dana',
                    'sumber_dana',
                    'set_input',
                    'id'
                ];

                if (isset($columns[$orderColumnIndex])) {
                    $query->orderBy($columns[$orderColumnIndex], $orderDir);
                }
            } else {
                $query->orderBy('kode_dana', 'asc');
            }

            // Pagination
            $start = $request->start ?? 0;
            $length = $request->length ?? 10;

            // Select only needed columns
            $data = $query->select([
                'id',
                'kode_dana',
                'nama_dana',
                'sumber_dana',
                'set_input'
            ])
                ->skip($start)
                ->take($length)
                ->get();

            // Format data untuk DataTables
            $formattedData = [];
            foreach ($data as $item) {
                $formattedData[] = [
                    'id' => $item->id,
                    'kode_dana' => $item->kode_dana,
                    'nama_dana' => $item->nama_dana,
                    'sumber_dana' => $item->sumber_dana ?? '-',
                    'set_input' => $item->set_input ?? 'Tidak',
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
            'kode_dana' => 'required|string|max:50|unique:sumber_dana,kode_dana',
            'nama_dana' => 'required|string|max:255',
            'sumber_dana' => 'nullable|string',
        ], [
            'kode_dana.required' => 'Kode dana wajib diisi',
            'kode_dana.unique' => 'Kode dana sudah ada',
            'nama_dana.required' => 'Nama dana wajib diisi',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Gagal menambah data. Periksa kembali input Anda.');
        }

        try {
            $sumberDana = SumberDana::create([
                'kode_dana' => $request->kode_dana,
                'nama_dana' => $request->nama_dana,
                'sumber_dana' => $request->sumber_dana,
                'set_input' => 'Tidak',
                'time_stamp' => now()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data sumber dana berhasil ditambahkan',
                    'data' => $sumberDana
                ], 201);
            }

            return redirect()->route('referensi.sumber-dana.index')
                ->with('success', 'Data sumber dana berhasil ditambahkan');
        } catch (\Exception $e) {
            \Log::error('Error creating sumber dana: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan data',
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $sumberDana = SumberDana::findOrFail($id);
            return view('referensi.sumber-dana.edit', compact('sumberDana'));
        } catch (\Exception $e) {
            return redirect()->route('referensi.sumber-dana.index')
                ->with('error', 'Sumber dana tidak ditemukan');
        }
    }

    public function update(Request $request, $id)
    {
        $sumberDana = SumberDana::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'kode_dana' => 'required|string|max:50|unique:sumber_dana,kode_dana,' . $id,
            'nama_dana' => 'required|string|max:255',
            'sumber_dana' => 'nullable|string',
        ], [
            'kode_dana.required' => 'Kode dana wajib diisi',
            'kode_dana.unique' => 'Kode dana sudah ada',
            'nama_dana.required' => 'Nama dana wajib diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Gagal memperbarui data. Periksa kembali input Anda.');
        }

        try {
            $sumberDana->update([
                'kode_dana' => $request->kode_dana,
                'nama_dana' => $request->nama_dana,
                'sumber_dana' => $request->sumber_dana,
            ]);

            return redirect()->route('referensi.sumber-dana.index')
                ->with('success', 'Data sumber dana berhasil diperbarui');
        } catch (\Exception $e) {
            \Log::error('Error updating sumber dana: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $sumberDana = SumberDana::findOrFail($id);
            $nama_dana = $sumberDana->nama_dana;
            $sumberDana->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Data sumber dana '{$nama_dana}' berhasil dihapus"
                ]);
            }

            return redirect()->route('referensi.sumber-dana.index')
                ->with('success', "Data sumber dana '{$nama_dana}' berhasil dihapus");
        } catch (\Exception $e) {
            \Log::error('Error deleting sumber dana: ' . $e->getMessage());

            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus data'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|integer|exists:sumber_dana,id',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data yang dipilih tidak valid'
                ], 422);
            }

            return redirect()->back()->with('error', 'Data yang dipilih tidak valid');
        }

        try {
            $deletedCount = SumberDana::whereIn('id', $request->ids)->delete();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "{$deletedCount} data sumber dana berhasil dihapus"
                ]);
            }

            return redirect()->route('referensi.sumber-dana.index')
                ->with('success', "{$deletedCount} data sumber dana berhasil dihapus");
        } catch (\Exception $e) {
            \Log::error('Error bulk deleting sumber dana: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus data'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}
