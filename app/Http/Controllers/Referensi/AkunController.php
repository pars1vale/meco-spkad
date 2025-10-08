<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\Akun as ModelsAkun;
use App\Models\Referensi\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AkunController extends Controller
{
    public function index()
    {
        $data = Akun::orderBy('kode_akun')->get();
        return view('referensi.akun.index', compact('data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_akun' => 'required|string|max:255|unique:akun,kode_akun',
            'nama_akun' => 'required|string',
            'keterangan_akun' => 'nullable|string',
        ], [
            'kode_akun.required' => 'Kode akun wajib diisi',
            'kode_akun.unique' => 'Kode akun sudah ada',
            'nama_akun.required' => 'Nama akun wajib diisi',
        ]);

        // Validasi minimal satu tipe akun harus dipilih
        $isPendapatan = $request->has('is_pendapatan') ? 1 : 0;
        $isBelanja = $request->has('is_belanja') ? 1 : 0;
        $isPembiayaan = $request->has('is_pembiayaan') ? 1 : 0;

        // Custom validation untuk tipe akun
        if ($isPendapatan + $isBelanja + $isPembiayaan === 0) {
            $validator->errors()->add('tipe_akun', 'Minimal satu tipe akun harus dipilih');

            // Return JSON untuk AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Return redirect untuk traditional form
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Minimal satu tipe akun harus dipilih.');
        }

        // Check validator fails setelah custom validation
        if ($validator->fails()) {
            // Return JSON untuk AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Return redirect untuk traditional form
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Data gagal disimpan. Periksa kembali input Anda.');
        }

        try {
            $akun = new Akun();
            $akun->id = Akun::getNextId();
            $akun->kode_akun = $request->kode_akun;
            $akun->nama_akun = $request->nama_akun;
            $akun->keterangan_akun = $request->keterangan_akun;

            // Set boolean flags
            $akun->is_pendapatan = $isPendapatan;
            $akun->is_belanja = $isBelanja;
            $akun->is_pembiayaan = $isPembiayaan;

            // Set text values based on boolean flags
            $akun->pendapatan = $isPendapatan ? 'Ya' : 'Tidak';
            $akun->belanja = $isBelanja ? 'Ya' : 'Tidak';
            $akun->pembiayaan = $isPembiayaan ? 'Ya' : 'Tidak';

            $akun->save();

            // Return JSON untuk AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data akun berhasil ditambahkan',
                    'data' => $akun
                ], 201);
            }

            // Return redirect untuk traditional form
            return redirect()->route('referensi.akun.index')
                ->with('success', 'Data akun berhasil ditambahkan');
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Error storing akun: ' . $e->getMessage());

            // Return JSON untuk AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan data',
                    'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
                ], 500);
            }

            // Return redirect untuk traditional form
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function edit(string $id)
    {
        $akun = Akun::findOrFail($id);
        return view('referensi.akun.edit', compact('akun'));
    }

    public function update(Request $request, string $id)
    {
        $akun = Akun::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'kode_akun' => 'required|string|max:255|unique:akun,kode_akun,' . $id,
            'nama_akun' => 'required|string',
            'keterangan_akun' => 'nullable|string',
        ], [
            'kode_akun.required' => 'Kode akun wajib diisi',
            'kode_akun.unique' => 'Kode akun sudah ada',
            'nama_akun.required' => 'Nama akun wajib diisi',
        ]);

        // Validasi minimal satu tipe akun harus dipilih
        $isPendapatan = $request->has('is_pendapatan') ? 1 : 0;
        $isBelanja = $request->has('is_belanja') ? 1 : 0;
        $isPembiayaan = $request->has('is_pembiayaan') ? 1 : 0;

        if ($isPendapatan + $isBelanja + $isPembiayaan === 0) {
            return redirect()->back()
                ->withErrors(['tipe_akun' => 'Minimal satu tipe akun harus dipilih'])
                ->withInput()
                ->with('error', 'Minimal satu tipe akun harus dipilih.');
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Data gagal diperbarui. Periksa kembali input Anda.');
        }

        try {
            $akun->kode_akun = $request->kode_akun;
            $akun->nama_akun = $request->nama_akun;
            $akun->keterangan_akun = $request->keterangan_akun;

            // Set boolean flags
            $akun->is_pendapatan = $isPendapatan;
            $akun->is_belanja = $isBelanja;
            $akun->is_pembiayaan = $isPembiayaan;

            // Set text values based on boolean flags
            $akun->pendapatan = $isPendapatan ? 'ya' : 'tidak';
            $akun->belanja = $isBelanja ? 'ya' : 'tidak';
            $akun->pembiayaan = $isPembiayaan ? 'ya' : 'tidak';

            // Laravel automatically handles updated_at timestamp
            $akun->save();

            return redirect()->route('referensi.akun.index')
                ->with('success', 'Data akun berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $akun = Akun::findOrFail($id);
            $nama_akun = $akun->nama_akun;

            $akun->delete();

            return redirect()->route('referensi.akun.index')
                ->with('success', "Data akun '{$nama_akun}' berhasil dihapus");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array|min:1',
            'ids.*' => 'required|integer|exists:akun,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Data yang dipilih tidak valid');
        }

        try {
            $deletedCount = Akun::whereIn('id', $request->ids)->delete();

            return redirect()->route('referensi.akun.index')
                ->with('success', "{$deletedCount} data akun berhasil dihapus");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}
