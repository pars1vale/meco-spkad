<?php

namespace App\Http\Controllers\StandarHargaSatuan;

use App\Http\Controllers\Controller;
use App\Models\StandarHargaSatuan\KelompokSatuanHarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KelompokSatuanHargaController extends Controller
{
    public function index()
    {
        $data = KelompokSatuanHarga::orderBy('kode_kategori', 'asc')->get();

        // Get distinct tahun anggaran untuk filter
        $tahunList = KelompokSatuanHarga::distinct()
            ->orderBy('tahun_anggaran', 'desc')
            ->pluck('tahun_anggaran');

        return view('standarhargasatuan.kelompok.index', compact('data', 'tahunList'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_kategori' => 'required|string|max:50|unique:data_kelompok_satuan_harga,kode_kategori',
            'uraian_kategori' => 'required|string',
            'tipe_kelompok' => 'required|in:SSH,HSPK,ASB,SBU',
            'tahun_anggaran' => 'required|integer|min:2000|max:2100',
            'active' => 'nullable|boolean',
        ], [
            'kode_kategori.required' => 'Kode kategori wajib diisi',
            'kode_kategori.unique' => 'Kode kategori sudah digunakan',
            'uraian_kategori.required' => 'Uraian kategori wajib diisi',
            'tipe_kelompok.required' => 'Tipe kelompok wajib dipilih',
            'tipe_kelompok.in' => 'Tipe kelompok tidak valid',
            'tahun_anggaran.required' => 'Tahun anggaran wajib diisi',
            'tahun_anggaran.min' => 'Tahun anggaran minimal 2000',
            'tahun_anggaran.max' => 'Tahun anggaran maksimal 2100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            KelompokSatuanHarga::create([
                'id_kategori' => null,
                'kode_kategori' => $request->kode_kategori,
                'uraian_kategori' => $request->uraian_kategori,
                'tipe_kelompok' => $request->tipe_kelompok,
                'tahun_anggaran' => $request->tahun_anggaran,
                'active' => $request->active ?? 1,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data kelompok satuan harga berhasil disimpan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: '.$e->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        $kelompok = KelompokSatuanHarga::findOrFail($id);

        return view('standarhargasatuan.kelompok.edit', compact('kelompok'));
    }

    public function update(Request $request, $id)
    {
        $kelompok = KelompokSatuanHarga::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'uraian_kategori' => 'required|string',
            'tipe_kelompok' => 'required|in:SSH,HSPK,ASB,SBU',
            'tahun_anggaran' => 'required|integer|min:2000|max:2100',
            'active' => 'nullable|boolean',
        ], [
            'kode_kategori.required' => 'Kode kategori wajib diisi',
            'kode_kategori.unique' => 'Kode kategori sudah digunakan',
            'uraian_kategori.required' => 'Uraian kategori wajib diisi',
            'tipe_kelompok.required' => 'Tipe kelompok wajib dipilih',
            'tipe_kelompok.in' => 'Tipe kelompok tidak valid',
            'tahun_anggaran.required' => 'Tahun anggaran wajib diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $kelompok->update([
                'id_kategori' => null,
                'kode_kategori' => $request->kode_kategori,
                'uraian_kategori' => $request->uraian_kategori,
                'tipe_kelompok' => $request->tipe_kelompok,
                'tahun_anggaran' => $request->tahun_anggaran,
                'active' => $request->active ?? 0,
            ]);

            return redirect()->route('kelompok_satuan_harga.index')
                ->with('success', 'Data kelompok satuan harga berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengupdate data: '.$e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $kelompok = KelompokSatuanHarga::findOrFail($id);

            // Check jika ada relasi dengan data SSH (uncomment jika sudah ada relasi)
            // if ($kelompok->dataSSH()->count() > 0) {
            //     return redirect()->route('kelompok_satuan_harga.index')
            //         ->with('error', 'Tidak dapat menghapus kelompok yang masih memiliki data SSH terkait');
            // }

            $kelompok->delete();

            return redirect()->route('kelompok_satuan_harga.index')
                ->with('success', 'Data kelompok satuan harga berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('kelompok_satuan_harga.index')
                ->with('error', 'Gagal menghapus data: '.$e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:data_kelompok_satuan_harga,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Data yang dipilih tidak valid');
        }

        try {
            // Check relasi (uncomment jika sudah ada)
            // $kelompokWithRelations = KelompokSatuanHarga::whereIn('id', $request->ids)
            //     ->withCount('dataSSH')
            //     ->having('data_ssh_count', '>', 0)
            //     ->count();

            // if ($kelompokWithRelations > 0) {
            //     return redirect()->back()
            //         ->with('error', 'Beberapa kelompok masih memiliki data SSH terkait dan tidak dapat dihapus');
            // }

            KelompokSatuanHarga::whereIn('id', $request->ids)->delete();

            return redirect()->route('kelompok_satuan_harga.index')
                ->with('success', count($request->ids).' data kelompok satuan harga berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('kelompok_satuan_harga.index')
                ->with('error', 'Gagal menghapus data: '.$e->getMessage());
        }
    }

    // Toggle status active
    public function toggleActive($id)
    {
        try {
            $kelompok = KelompokSatuanHarga::findOrFail($id);
            $kelompok->toggleActive();

            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diubah',
                'active' => $kelompok->active,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: '.$e->getMessage(),
            ], 500);
        }
    }

    // API untuk mendapatkan kelompok berdasarkan tipe (untuk AJAX)
    public function getByTipe(Request $request)
    {
        $tipe = $request->get('tipe');
        $tahun = $request->get('tahun');

        if (! $tipe || ! in_array($tipe, ['SSH', 'HSPK', 'ASB', 'SBU'])) {
            return response()->json([
                'success' => false,
                'message' => 'Tipe tidak valid',
            ], 400);
        }

        $query = KelompokSatuanHarga::where('tipe_kelompok', $tipe)
            ->where('active', 1);

        if ($tahun) {
            $query->where('tahun_anggaran', $tahun);
        }

        $kelompok = $query->orderBy('kode_kategori', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $kelompok,
        ]);
    }

    // API untuk mendapatkan kelompok berdasarkan tahun
    public function getByTahun(Request $request)
    {
        $tahun = $request->get('tahun');

        if (! $tahun) {
            return response()->json([
                'success' => false,
                'message' => 'Tahun tidak valid',
            ], 400);
        }

        $kelompok = KelompokSatuanHarga::where('tahun_anggaran', $tahun)
            ->where('active', 1)
            ->orderBy('kode_kategori', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $kelompok,
        ]);
    }
}
