<?php

namespace App\Http\Controllers\StandarHargaSatuan;

use App\Http\Controllers\Controller;
use App\Models\Referensi\Akun;
use App\Models\StandarHargaSatuan\DataSatuan;
use App\Models\StandarHargaSatuan\KelompokSatuanHarga;
use App\Models\StandarHargaSatuan\StandarHarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StandarHargaController extends Controller
{
    public function index()
    {
        $data = StandarHarga::with(['kelompokStandarHarga', 'satuan', 'rekeningBelanja'])
            ->orderBy('kode_standar_harga', 'asc')
            ->get();

        $kelompok = KelompokSatuanHarga::orderBy('nama_kelompok_standar_harga', 'asc')->get();
        // $satuan = DataSatuan::orderBy('nama_satuan', 'asc')->get();
        $akun = Akun::where('is_belanja', 1)->orderBy('kode_akun', 'asc')->get();

        // return view('standarhargasatuan.standarharga.index', compact('data', 'kelompok', 'satuan', 'akun'));
        return view('standarhargasatuan.standarharga.index', compact('data', 'kelompok', 'akun'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_standar_harga' => 'required|string|max:50|unique:standar_harga,kode_standar_harga',
            'tipe_standar_harga' => 'required|in:SSH,HSPK,ASB,SBU',
            'id_kelompok_standar_harga' => 'required|exists:kelompok_standar_harga,id',
            'id_satuan' => 'required|exists:data_satuan,id',
            'nama_standar_harga' => 'required|string',
            'spesifikasi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'nilai_tkdn' => 'nullable|numeric|min:0|max:100',
            'is_pdn' => 'nullable|boolean',
            'rekening_belanja' => 'required|array|min:1',
            'rekening_belanja.*.id_akun' => 'required|exists:akun,id',
        ], [
            'kode_standar_harga.required' => 'Kode standar harga wajib diisi',
            'kode_standar_harga.unique' => 'Kode standar harga sudah digunakan',
            'tipe_standar_harga.required' => 'Tipe standar harga wajib dipilih',
            'id_kelompok_standar_harga.required' => 'Kelompok standar harga wajib dipilih',
            'id_satuan.required' => 'Satuan wajib dipilih',
            'nama_standar_harga.required' => 'Nama standar harga wajib diisi',
            'harga.required' => 'Harga wajib diisi',
            'rekening_belanja.required' => 'Minimal satu rekening belanja harus dipilih',
            'rekening_belanja.min' => 'Minimal satu rekening belanja harus dipilih',
            'rekening_belanja.*.id_akun.required' => 'Setiap rekening harus dipilih',
            'rekening_belanja.*.id_akun.exists' => 'Rekening tidak valid',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();
        try {
            $standarHarga = StandarHarga::create([
                'kode_standar_harga' => $request->kode_standar_harga,
                'tipe_standar_harga' => $request->tipe_standar_harga,
                'id_kelompok_standar_harga' => $request->id_kelompok_standar_harga,
                'id_satuan' => $request->id_satuan,
                'nama_standar_harga' => $request->nama_standar_harga,
                'spesifikasi' => $request->spesifikasi,
                'harga' => $request->harga,
                'nilai_tkdn' => $request->nilai_tkdn ?? 0,
                'is_pdn' => $request->is_pdn ?? false,
            ]);

            // Extract rekening IDs from repeater data
            $rekeningIds = [];
            foreach ($request->rekening_belanja as $rekening) {
                if (isset($rekening['id_akun']) && ! empty($rekening['id_akun'])) {
                    $rekeningIds[] = $rekening['id_akun'];
                }
            }

            // Remove duplicates
            $rekeningIds = array_unique($rekeningIds);

            if (empty($rekeningIds)) {
                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => 'Minimal satu rekening belanja harus dipilih',
                ], 422);
            }

            // Attach rekening belanja
            $standarHarga->rekeningBelanja()->attach($rekeningIds);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data standar harga berhasil disimpan dengan '.count($rekeningIds).' rekening belanja',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan data: '.$e->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        $standarHarga = StandarHarga::with(['kelompokStandarHarga', 'satuan', 'rekeningBelanja'])->findOrFail($id);
        $kelompok = KelompokSatuanHarga::orderBy('nama_kelompok_standar_harga', 'asc')->get();
        // $satuan = DataSatuan::orderBy('nama_satuan', 'asc')->get();
        $akun = Akun::where('is_belanja', 1)->orderBy('kode_akun', 'asc')->get();

        // return view('standarhargasatuan.standarharga.edit', compact('standarHarga', 'kelompok', 'satuan', 'akun'));
        return view('standarhargasatuan.standarharga.edit', compact('standarHarga', 'kelompok', 'akun'));
    }

    public function update(Request $request, $id)
    {
        $standarHarga = StandarHarga::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'kode_standar_harga' => 'required|string|max:50|unique:standar_harga,kode_standar_harga,'.$id,
            'id_kelompok_standar_harga' => 'required|exists:kelompok_standar_harga,id',
            'id_satuan' => 'required|exists:data_satuan,id',
            'nama_standar_harga' => 'required|string',
            'spesifikasi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'nilai_tkdn' => 'nullable|numeric|min:0|max:100',
            'is_pdn' => 'nullable|boolean',
            'rekening_belanja' => 'required|array|min:1',
            'rekening_belanja.*' => 'exists:akun,id',
        ], [
            'kode_standar_harga.required' => 'Kode standar harga wajib diisi',
            'kode_standar_harga.unique' => 'Kode standar harga sudah digunakan',
            'id_kelompok_standar_harga.required' => 'Kelompok standar harga wajib dipilih',
            'id_satuan.required' => 'Satuan wajib dipilih',
            'nama_standar_harga.required' => 'Nama standar harga wajib diisi',
            'harga.required' => 'Harga wajib diisi',
            'rekening_belanja.required' => 'Minimal satu rekening belanja harus dipilih',
            'rekening_belanja.min' => 'Minimal satu rekening belanja harus dipilih',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $standarHarga->update([
                'kode_standar_harga' => $request->kode_standar_harga,
                'id_kelompok_standar_harga' => $request->id_kelompok_standar_harga,
                'id_satuan' => $request->id_satuan,
                'nama_standar_harga' => $request->nama_standar_harga,
                'spesifikasi' => $request->spesifikasi,
                'harga' => $request->harga,
                'nilai_tkdn' => $request->nilai_tkdn ?? 0,
                'is_pdn' => $request->is_pdn ?? false,
            ]);

            // Sync rekening belanja
            $standarHarga->rekeningBelanja()->sync($request->rekening_belanja);

            DB::commit();

            return redirect()->route('standar_harga.index')
                ->with('success', 'Data standar harga berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Gagal mengupdate data: '.$e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $standarHarga = StandarHarga::findOrFail($id);
            $standarHarga->delete();

            return redirect()->route('standar_harga.index')
                ->with('success', 'Data standar harga berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('standar_harga.index')
                ->with('error', 'Gagal menghapus data: '.$e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:standar_harga,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Data yang dipilih tidak valid');
        }

        try {
            StandarHarga::whereIn('id', $request->ids)->delete();

            return redirect()->route('standar_harga.index')
                ->with('success', count($request->ids).' data standar harga berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('standar_harga.index')
                ->with('error', 'Gagal menghapus data: '.$e->getMessage());
        }
    }

    public function addRekening(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'rekening_belanja' => 'required|array|min:1',
            'rekening_belanja.*' => 'exists:akun,id',
        ], [
            'rekening_belanja.required' => 'Minimal satu rekening belanja harus dipilih',
            'rekening_belanja.min' => 'Minimal satu rekening belanja harus dipilih',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->with('error', 'Minimal satu rekening belanja harus dipilih')
                ->withErrors($validator);
        }

        try {
            $standarHarga = StandarHarga::findOrFail($id);

            // Get existing rekening IDs
            $existingIds = $standarHarga->rekeningBelanja()->pluck('id_akun')->toArray();

            // Filter out already attached rekening
            $newRekeningIds = array_diff($request->rekening_belanja, $existingIds);

            if (empty($newRekeningIds)) {
                return redirect()->route('standar_harga.index')
                    ->with('info', 'Rekening yang dipilih sudah ada');
            }

            // Attach new rekening belanja
            $standarHarga->rekeningBelanja()->attach($newRekeningIds);

            return redirect()->route('standar_harga.index')
                ->with('success', count($newRekeningIds).' rekening belanja berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->route('standar_harga.index')
                ->with('error', 'Gagal menambahkan rekening: '.$e->getMessage());
        }
    }

    /**
     * Remove rekening belanja from standar harga
     */
    public function removeRekening(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'id_akun' => 'required|exists:akun,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Data tidak valid');
        }

        try {
            $standarHarga = StandarHarga::findOrFail($id);

            // Check if this is the last rekening
            if ($standarHarga->rekeningBelanja()->count() <= 1) {
                return redirect()->route('standar_harga.index')
                    ->with('error', 'Tidak dapat menghapus rekening terakhir. Minimal harus ada satu rekening belanja.');
            }

            // Detach the rekening
            $standarHarga->rekeningBelanja()->detach($request->id_akun);

            return redirect()->route('standar_harga.index')
                ->with('success', 'Rekening belanja berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('standar_harga.index')
                ->with('error', 'Gagal menghapus rekening: '.$e->getMessage());
        }
    }
}
