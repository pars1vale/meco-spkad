<?php

namespace App\Http\Controllers\Rkpd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rkpd\TahapPenjadwalan;
use App\Models\Rkpd\SubTahapPenjadwalan;

class SubTahapController extends Controller
{
    public function index()
    {
        $data = collect();

        try {
            // Ambil semua sub tahap beserta tahap induknya
            $subTahaps = SubTahapPenjadwalan::with('tahap')
                ->orderBy('id_tahap', 'asc')
                ->orderBy('nama_sub_tahap', 'asc')
                ->get();

            // Group berdasarkan nama tahap
            $data = $subTahaps->groupBy(function ($item) {
                return $item->tahap->nama_tahap ?? 'Tanpa Tahap';
            });
        } catch (\Exception $e) {
            \Log::error('Error fetching sub tahap data: ' . $e->getMessage());
            $data = collect();
        }

        // List Tahap untuk dropdown/filter
        $listTahap = TahapPenjadwalan::orderBy('nama_tahap', 'asc')->get();

        return view('rkpd.sub-tahap.index', compact('data', 'listTahap'));
    }
    public function store(Request $request)
    {
        $request->validate([
        'id_tahap' => 'required|exists:tahap_penjadwalan,id_tahap',
        'nama_sub_tahap' => 'required|max:255',
        ], [
        'id_tahap.required' => 'Tahap wajib dipilih.',
        'id_tahap.exists' => 'Tahap tidak valid.',
        'nama_sub_tahap.required' => 'Nama sub tahap wajib diisi.',
        'nama_sub_tahap.max' => 'Nama sub tahap maksimal 255 karakter.',
        ]);

        try {
        SubTahapPenjadwalan::create([
        'id_tahap' => $request->id_tahap,
        'nama_sub_tahap' => $request->nama_sub_tahap,
        ]);

        return redirect()->route('rkpd.sub-tahap.index')
        ->with('success', 'Sub tahap berhasil ditambahkan.');
        } catch (\Exception $e) {
        \Log::error('Error creating sub tahap: ' . $e->getMessage());
        return redirect()->back()
        ->with('error', 'Gagal menambahkan sub tahap.')
        ->withInput();
    }
    }

    public function edit($id)
{
    try {
        $subTahap = SubTahapPenjadwalan::with('tahap')->findOrFail($id);
        $listTahap = TahapPenjadwalan::orderBy('nama_tahap')->get();

        return view('rkpd.sub-tahap.edit', compact('subTahap', 'listTahap'));
    } catch (\Exception $e) {
        return redirect()->route('rkpd.sub-tahap.index')
            ->with('error', 'Sub tahap tidak ditemukan.');
    }
}

public function update(Request $request, $id)
{
    $subTahap = SubTahapPenjadwalan::findOrFail($id);

    $request->validate([
        'id_tahap' => 'required|exists:tahap_penjadwalan,id_tahap',
        'nama_sub_tahap' => 'required|max:255',
    ], [
        'id_tahap.required' => 'Tahap wajib dipilih.',
        'id_tahap.exists' => 'Tahap tidak valid.',
        'nama_sub_tahap.required' => 'Nama sub tahap wajib diisi.',
        'nama_sub_tahap.max' => 'Nama sub tahap maksimal 255 karakter.',
    ]);

    try {
        $subTahap->update([
            'id_tahap' => $request->id_tahap,
            'nama_sub_tahap' => $request->nama_sub_tahap,
        ]);

        return redirect()->route('rkpd.sub-tahap.index')
            ->with('success', 'Sub tahap berhasil diperbarui.');
    } catch (\Exception $e) {
        \Log::error('Error updating sub tahap: ' . $e->getMessage());
        return redirect()->back()
            ->with('error', 'Gagal memperbarui sub tahap.')
            ->withInput();
    }
}

public function destroy($id)
{
    try {
        $subTahap = SubTahapPenjadwalan::findOrFail($id);
        $subTahap->delete();

        return redirect()->route('rkpd.sub-tahap.index')
            ->with('success', 'Sub tahap berhasil dihapus.');
    } catch (\Exception $e) {
        \Log::error('Error deleting sub tahap: ' . $e->getMessage());
        return redirect()->route('rkpd.sub-tahap.index')
            ->with('error', 'Gagal menghapus sub tahap.');
    }
}

public function bulkDelete(Request $request)
{
    $request->validate([
        'ids' => 'required|array',
        'ids.*' => 'exists:sub_tahap_penjadwalan,id_sub_tahap'
    ]);

    try {
        $deletedCount = SubTahapPenjadwalan::whereIn('id_sub_tahap', $request->ids)->delete();

        return redirect()->route('rkpd.sub-tahap.index')
            ->with('success', "Berhasil menghapus {$deletedCount} sub tahap.");
    } catch (\Exception $e) {
        \Log::error('Error bulk deleting sub tahap: ' . $e->getMessage());
        return redirect()->route('rkpd.sub-tahap.index')
            ->with('error', 'Gagal menghapus sub tahap terpilih.');
    }
}


}