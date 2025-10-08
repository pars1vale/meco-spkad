<?php

namespace App\Http\Controllers\Shs;

use App\Http\Controllers\Controller;
use App\Models\StandarHargaSatuan\DataSatuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SatuanController extends Controller
{
    public function index()
    {
        $data = DataSatuan::orderBy('nama_satuan', 'asc')->get();
        return view('shs.satuan.index', compact('data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_satuan' => 'required|string|max:50|unique:data_satuan,nama_satuan',
        ], [
            'nama_satuan.required' => 'Nama satuan harus diisi',
            'nama_satuan.max' => 'Nama satuan maksimal 50 karakter',
            'nama_satuan.unique' => 'Nama satuan sudah ada',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DataSatuan::create([
                'nama_satuan' => $request->nama_satuan,
            ]);

            return response()->json([
                'message' => 'Data satuan berhasil ditambahkan'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $satuan = DataSatuan::findOrFail($id);
        return view('shs.satuan.edit', compact('satuan'));
    }

    public function update(Request $request, $id)
    {
        $satuan = DataSatuan::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_satuan' => 'required|string|max:50|unique:data_satuan,nama_satuan,' . $id,
        ], [
            'nama_satuan.required' => 'Nama satuan harus diisi',
            'nama_satuan.max' => 'Nama satuan maksimal 50 karakter',
            'nama_satuan.unique' => 'Nama satuan sudah ada',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $satuan->update([
                'nama_satuan' => $request->nama_satuan,
            ]);

            return redirect()->route('satuan.index')
                ->with('success', 'Data satuan berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal update data: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $satuan = DataSatuan::findOrFail($id);
            $satuan->delete();

            return redirect()->route('referensi.satuan.index')
                ->with('success', 'Data satuan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|integer|exists:data_satuan,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Data yang dipilih tidak valid');
        }

        try {
            DB::beginTransaction();

            DataSatuan::whereIn('id', $request->ids)->delete();

            DB::commit();

            return redirect()->route('referensi.satuan.index')
                ->with('success', count($request->ids) . ' data satuan berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
