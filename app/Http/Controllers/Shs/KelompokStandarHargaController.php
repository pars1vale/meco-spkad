<?php

namespace App\Http\Controllers\Shs;

use App\Http\Controllers\Controller;
use App\Models\StandarHargaSatuan\DataKelompokStandarHarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KelompokStandarHargaController extends Controller
{
    public function index()
    {
        $data = DataKelompokStandarHarga::orderBy('kode_kelompok_standar_harga', 'asc')->get();
        return view('shs.kelompokstandarharga.index', compact('data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_kelompok_standar_harga' => 'required|string|max:30|unique:kelompok_standar_harga,kode_kelompok_standar_harga',
            'nama_kelompok_standar_harga' => 'required|string',
            'tipe_kelompok' => 'required|in:SSH,HSPK,ASB,SBU',
        ], [
            'kode_kelompok_standar_harga.required' => 'Kode kelompok wajib diisi',
            'kode_kelompok_standar_harga.unique' => 'Kode kelompok sudah digunakan',
            'nama_kelompok_standar_harga.required' => 'Nama kelompok wajib diisi',
            'tipe_kelompok.required' => 'Tipe kelompok wajib dipilih',
            'tipe_kelompok.in' => 'Tipe kelompok tidak valid',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DataKelompokStandarHarga::create([
                'kode_kelompok_standar_harga' => $request->kode_kelompok_standar_harga,
                'nama_kelompok_standar_harga' => $request->nama_kelompok_standar_harga,
                'tipe_kelompok' => $request->tipe_kelompok,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data kelompok standar harga berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $kelompok = DataKelompokStandarHarga::findOrFail($id);
        return view('shs.kelompokstandarharga.edit', compact('kelompok'));
    }

    public function update(Request $request, $id)
    {
        $kelompok = DataKelompokStandarHarga::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'kode_kelompok_standar_harga' => 'required|string|max:30|unique:kelompok_standar_harga,kode_kelompok_standar_harga,' . $id,
            'nama_kelompok_standar_harga' => 'required|string',
            'tipe_kelompok' => 'required|in:SSH,HSPK,ASB,SBU',
        ], [
            'kode_kelompok_standar_harga.required' => 'Kode kelompok wajib diisi',
            'kode_kelompok_standar_harga.unique' => 'Kode kelompok sudah digunakan',
            'nama_kelompok_standar_harga.required' => 'Nama kelompok wajib diisi',
            'tipe_kelompok.required' => 'Tipe kelompok wajib dipilih',
            'tipe_kelompok.in' => 'Tipe kelompok tidak valid',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $kelompok->update([
                'kode_kelompok_standar_harga' => $request->kode_kelompok_standar_harga,
                'nama_kelompok_standar_harga' => $request->nama_kelompok_standar_harga,
                'tipe_kelompok' => $request->tipe_kelompok,
            ]);

            return redirect()->route('kelompok_satuan_harga.index')
                ->with('success', 'Data kelompok standar harga berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengupdate data: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $kelompok = DataKelompokStandarHarga::findOrFail($id);

            if ($kelompok->standarHarga()->count() > 0) {
                return redirect()->route('kelompok_satuan_harga.index')
                    ->with('error', 'Tidak dapat menghapus kelompok yang masih memiliki standar harga terkait');
            }

            $kelompok->delete();

            return redirect()->route('kelompok_satuan_harga.index')
                ->with('success', 'Data kelompok standar harga berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('kelompok_satuan_harga.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:kelompok_standar_harga,id'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Data yang dipilih tidak valid');
        }

        try {
            $kelompokWithRelations = DataKelompokStandarHarga::whereIn('id', $request->ids)
                ->withCount('standarHarga')
                ->having('standar_harga_count', '>', 0)
                ->count();

            if ($kelompokWithRelations > 0) {
                return redirect()->back()
                    ->with('error', 'Beberapa kelompok masih memiliki standar harga terkait dan tidak dapat dihapus');
            }

            DataKelompokStandarHarga::whereIn('id', $request->ids)->delete();

            return redirect()->route('kelompok_satuan_harga.index')
                ->with('success', count($request->ids) . ' data kelompok standar harga berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('kelompok_satuan_harga.index')
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    // API untuk mendapatkan kelompok berdasarkan tipe (untuk AJAX)
    public function getByTipe(Request $request)
    {
        $tipe = $request->get('tipe');

        if (!$tipe || !in_array($tipe, ['SSH', 'HSPK', 'ASB', 'SBU'])) {
            return response()->json([
                'success' => false,
                'message' => 'Tipe tidak valid'
            ], 400);
        }

        $kelompok = DataKelompokStandarHarga::where('tipe_kelompok', $tipe)
            ->orderBy('nama_kelompok_standar_harga', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $kelompok
        ]);
    }
}
