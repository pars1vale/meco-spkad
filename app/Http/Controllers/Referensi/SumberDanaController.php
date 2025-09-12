<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\Referensi\SumberDana;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SumberDanaController extends Controller
{
    public function index()
    {
        $data = SumberDana::orderBy('kode_dana')->get();
        return view('referensi.sumber-dana.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_dana' => 'required|string|max:50|unique:sumber_dana,kode_dana',
            'nama_dana' => 'required|string|max:255',
            'sumber_dana' => 'nullable|string',
        ], [
            'kode_dana.required' => 'Kode Dana wajib diisi',
            'kode_dana.unique' => 'Kode Dana sudah ada',
            'nama_dana.required' => 'Nama Dana wajib diisi',
        ]);

        try {
            $nextId = $this->getNextId();

            DB::table('sumber_dana')->insert([
                'id' => $nextId,
                'kode_dana' => $request->kode_dana,
                'nama_dana' => $request->nama_dana,
                'sumber_dana' => $request->sumber_dana,
                'time_stamp' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data Sumber Dana berhasil ditambahkan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $sumberDana = DB::table('sumber_dana')->where('id', $id)->first();

        if (!$sumberDana) {
            return redirect()->route('referensi.sumber-dana.index')
                ->with('error', 'Data Sumber Dana tidak ditemukan');
        }

        return view('referensi.sumber-dana.edit', compact('sumberDana'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_dana' => [
                'required',
                'string',
                'max:50',
                Rule::unique('sumber_dana', 'kode_dana')->ignore($id)
            ],
            'nama_dana' => 'required|string|max:255',
            'sumber_dana' => 'nullable|string',
        ], [
            'kode_dana.required' => 'Kode Dana wajib diisi',
            'kode_dana.unique' => 'Kode Dana sudah ada',
            'nama_dana.required' => 'Nama Dana wajib diisi',
        ]);

        try {
            $updated = DB::table('sumber_dana')
                ->where('id', $id)
                ->update([
                    'kode_dana' => $request->kode_dana,
                    'nama_dana' => $request->nama_dana,
                    'sumber_dana' => $request->sumber_dana,
                    'updated_at' => now(),
                ]);

            if ($updated) {
                return redirect()->route('referensi.sumber-dana.index')
                    ->with('success', 'Data Sumber Dana berhasil diupdate');
            } else {
                return redirect()->back()
                    ->with('error', 'Data tidak ditemukan atau tidak ada perubahan')
                    ->withInput();
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengupdate data: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $deleted = DB::table('sumber_dana')->where('id', $id)->delete();

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data Sumber Dana berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:sumber_dana,id'
        ]);

        try {
            $deleted = DB::table('sumber_dana')
                ->whereIn('id', $request->ids)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => $deleted . ' data berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getNextId()
    {
        $maxId = DB::table('sumber_dana')->max('id');
        return $maxId ? $maxId + 1 : 1;
    }
}
