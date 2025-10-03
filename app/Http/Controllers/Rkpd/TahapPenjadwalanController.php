<?php

namespace App\Http\Controllers\Rkpd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rkpd\TahapPenjadwalan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TahapPenjadwalanController extends Controller
{
    public function index()
    {
        $data = TahapPenjadwalan::all();
        return view('rkpd.tahap-penjadwalan.index', compact('data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_tahap' => 'required|max:255',
        ], [
            'nama_tahap.required' => 'Nama tahap wajib diisi.',
            'nama_tahap.max' => 'Nama tahap maksimal 255 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Gagal menambah data tahap penjadwalan. Silakan periksa input Anda.');
        }

        try {
            DB::transaction(function () use ($request) {
                TahapPenjadwalan::create([
                    'nama_tahap' => $request->nama_tahap,
                ]);
            });

            return redirect()->route('rkpd.tahap-penjadwalan.index')
                ->with('success', 'Data tahap penjadwalan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambah data tahap penjadwalan. ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $tahap = TahapPenjadwalan::findOrFail($id);
            return view('rkpd.tahap-penjadwalan.edit', compact('tahap'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('rkpd.tahap-penjadwalan.index')
                ->with('error', 'Data tahap penjadwalan tidak ditemukan.');
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama_tahap' => 'required|max:255',
        ], [
            'nama_tahap.required' => 'Nama tahap wajib diisi.',
            'nama_tahap.max' => 'Nama tahap maksimal 255 karakter.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Gagal memperbarui data tahap penjadwalan. Silakan periksa input Anda.');
        }

        try {
            $tahap = TahapPenjadwalan::findOrFail($id);
            $tahap->update([
                'nama_tahap' => $request->nama_tahap,
            ]);

            return redirect()->route('rkpd.tahap-penjadwalan.index')
                ->with('success', 'Data tahap penjadwalan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data tahap penjadwalan. ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $tahap = TahapPenjadwalan::findOrFail($id);

            // Cek apakah tahap punya sub-tahap
            if ($tahap->subTahaps()->exists()) {
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus tahap karena masih memiliki sub tahap terkait.');
            }

            $namaTahap = $tahap->nama_tahap;
            $tahap->delete();

            return redirect()->route('rkpd.tahap-penjadwalan.index')
                ->with('success', "Data tahap '{$namaTahap}' berhasil dihapus.");
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()
                ->with('error', 'Data tahap penjadwalan tidak ditemukan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data tahap penjadwalan. ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:tahap_penjadwalan,id_tahap',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Data yang dipilih tidak valid.');
        }

        try {
            $ids = $request->ids;

            // Cek apakah ada tahap yang memiliki sub tahap
            $tahapWithRelations = TahapPenjadwalan::whereIn('id_tahap', $ids)
                ->whereHas('subTahaps')
                ->pluck('nama_tahap')
                ->toArray();

            if (!empty($tahapWithRelations)) {
                return redirect()->back()
                    ->with('error', 'Tidak dapat menghapus tahap: ' . implode(', ', $tahapWithRelations) . ' karena masih memiliki sub tahap terkait.');
            }

            $deletedCount = TahapPenjadwalan::whereIn('id_tahap', $ids)->delete();

            return redirect()->back()
                ->with('success', "{$deletedCount} data tahap penjadwalan berhasil dihapus.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus data tahap penjadwalan. ' . $e->getMessage());
        }
    }
}