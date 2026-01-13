<?php

namespace App\Http\Controllers\StandarHargaSatuan;

use App\Http\Controllers\Controller;
use App\Models\StandarHargaSatuan\DataSSH;
use App\Models\StandarHargaSatuan\KelompokSatuanHarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DataSSHController extends Controller
{
    public function index(Request $request)
    {
        $query = DataSSH::with('kelompokSatuanHarga');

        // Apply filters if provided
        if ($request->filled('tipe')) {
            $query->byTipe($request->tipe);
        }

        if ($request->filled('tahun')) {
            $query->byTahun($request->tahun);
        }

        if ($request->filled('kelompok')) {
            $query->byKelompok($request->kelompok);
        }

        if ($request->filled('status_lock')) {
            if ($request->status_lock == '1') {
                $query->locked();
            } elseif ($request->status_lock == '0') {
                $query->unlocked();
            }
        }

        $data = $query->orderBy('kode_standar_harga', 'asc')->get();

        // Get filter options
        $tahunList = DataSSH::distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        $kelompokList = KelompokSatuanHarga::where('active', 1)
            ->orderBy('kode_kategori', 'asc')
            ->get();

        return view('standarhargasatuan.standarharga.index', compact('data', 'tahunList', 'kelompokList'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_kel_standar_harga' => 'required|exists:data_kelompok_satuan_harga,id_kategori',
            'tipe_standar_harga' => 'required|in:SSH,HSPK,ASB,SBU',
            'kode_standar_harga' => 'required|string|max:50|unique:data_ssh,kode_standar_harga',
            'nama_standar_harga' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'harga' => 'required|numeric|min:0',
            'spek' => 'nullable|string',
            'nilai_tkdn' => 'nullable|numeric|min:0|max:100',
            'ket_teks' => 'nullable|string',
            'tahun' => 'required|integer|min:2000|max:2100',
            'id_daerah' => 'required|integer',
            'is_pdn' => 'nullable|boolean',
        ], [
            'id_kel_standar_harga.required' => 'Kelompok standar harga wajib dipilih',
            'id_kel_standar_harga.exists' => 'Kelompok standar harga tidak valid',
            'tipe_standar_harga.required' => 'Tipe standar harga wajib dipilih',
            'kode_standar_harga.required' => 'Kode standar harga wajib diisi',
            'kode_standar_harga.unique' => 'Kode standar harga sudah digunakan',
            'nama_standar_harga.required' => 'Nama standar harga wajib diisi',
            'satuan.required' => 'Satuan wajib diisi',
            'harga.required' => 'Harga wajib diisi',
            'tahun.required' => 'Tahun wajib diisi',
            'id_daerah.required' => 'ID Daerah wajib diisi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            // Get kelompok data
            $kelompok = KelompokSatuanHarga::where('id_kategori', $request->id_kel_standar_harga)->firstOrFail();

            // Generate IDs
            $idStandarHarga = DataSSH::getNextIdStandarHarga();
            $idUnik = DataSSH::generateIdUnik(
                $request->tahun,
                $request->tipe_standar_harga,
                $request->id_daerah
            );

            DataSSH::create([
                'id_standar_harga' => $idStandarHarga,
                'id_unik' => $idUnik,
                'id_kel_standar_harga' => $request->id_kel_standar_harga,
                'kode_kel_standar_harga' => $kelompok->kode_kategori,
                'nama_kel_standar_harga' => $kelompok->uraian_kategori,
                'tipe_standar_harga' => $request->tipe_standar_harga,
                'kode_standar_harga' => $request->kode_standar_harga,
                'nama_standar_harga' => $request->nama_standar_harga,
                'satuan' => $request->satuan,
                'harga' => $request->harga,
                'spek' => $request->spek,
                'nilai_tkdn' => $request->nilai_tkdn ?? 0,
                'ket_teks' => $request->ket_teks,
                'tahun' => $request->tahun,
                'id_daerah' => $request->id_daerah,
                'is_pdn' => $request->is_pdn ?? 0,
                'is_locked' => 0,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data SSH berhasil disimpan',
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
        $ssh = DataSSH::with('kelompokSatuanHarga')->findOrFail($id);
        $kelompokList = KelompokSatuanHarga::where('active', 1)
            ->orderBy('kode_kategori', 'asc')
            ->get();

        return view('standarhargasatuan.standarharga.edit', compact('ssh', 'kelompokList'));
    }

    public function update(Request $request, $id)
    {
        $ssh = DataSSH::findOrFail($id);

        // Check if locked
        if ($ssh->is_locked) {
            return redirect()->back()
                ->with('error', 'Data terkunci dan tidak dapat diubah');
        }

        $validator = Validator::make($request->all(), [
            'id_kel_standar_harga' => 'required|exists:data_kelompok_satuan_harga,id_kategori',
            'tipe_standar_harga' => 'required|in:SSH,HSPK,ASB,SBU',
            'kode_standar_harga' => [
                'required',
                'string',
                'max:50',
                Rule::unique('data_ssh', 'kode_standar_harga')->ignore($id, 'id_standar_harga'),
            ],
            'nama_standar_harga' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'harga' => 'required|numeric|min:0',
            'spek' => 'nullable|string',
            'nilai_tkdn' => 'nullable|numeric|min:0|max:100',
            'ket_teks' => 'nullable|string',
            'tahun' => 'required|integer|min:2000|max:2100',
            'id_daerah' => 'required|integer',
            'is_pdn' => 'nullable|boolean',
        ], [
            'id_kel_standar_harga.required' => 'Kelompok standar harga wajib dipilih',
            'kode_standar_harga.required' => 'Kode standar harga wajib diisi',
            'kode_standar_harga.unique' => 'Kode standar harga sudah digunakan',
            'nama_standar_harga.required' => 'Nama standar harga wajib diisi',
            'satuan.required' => 'Satuan wajib diisi',
            'harga.required' => 'Harga wajib diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Get kelompok data
            $kelompok = KelompokSatuanHarga::where('id_kategori', $request->id_kel_standar_harga)->firstOrFail();

            $ssh->update([
                'id_kel_standar_harga' => $request->id_kel_standar_harga,
                'kode_kel_standar_harga' => $kelompok->kode_kategori,
                'nama_kel_standar_harga' => $kelompok->uraian_kategori,
                'tipe_standar_harga' => $request->tipe_standar_harga,
                'kode_standar_harga' => $request->kode_standar_harga,
                'nama_standar_harga' => $request->nama_standar_harga,
                'satuan' => $request->satuan,
                'harga' => $request->harga,
                'spek' => $request->spek,
                'nilai_tkdn' => $request->nilai_tkdn ?? 0,
                'ket_teks' => $request->ket_teks,
                'tahun' => $request->tahun,
                'id_daerah' => $request->id_daerah,
                'is_pdn' => $request->is_pdn ?? 0,
            ]);

            return redirect()->route('data_ssh.index')
                ->with('success', 'Data SSH berhasil diupdate');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengupdate data: '.$e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $ssh = DataSSH::findOrFail($id);

            if ($ssh->is_locked) {
                return redirect()->route('data_ssh.index')
                    ->with('error', 'Data terkunci dan tidak dapat dihapus');
            }

            $ssh->delete();

            return redirect()->route('data_ssh.index')
                ->with('success', 'Data SSH berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('data_ssh.index')
                ->with('error', 'Gagal menghapus data: '.$e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:data_ssh,id_standar_harga',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Data yang dipilih tidak valid');
        }

        try {
            // Check for locked items
            $lockedCount = DataSSH::whereIn('id_standar_harga', $request->ids)
                ->where('is_locked', 1)
                ->count();

            if ($lockedCount > 0) {
                return redirect()->back()
                    ->with('error', "Terdapat {$lockedCount} data yang terkunci dan tidak dapat dihapus");
            }

            DataSSH::whereIn('id_standar_harga', $request->ids)->delete();

            return redirect()->route('data_ssh.index')
                ->with('success', count($request->ids).' data SSH berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->route('data_ssh.index')
                ->with('error', 'Gagal menghapus data: '.$e->getMessage());
        }
    }

    // Toggle lock status
    public function toggleLock($id)
    {
        try {
            $ssh = DataSSH::findOrFail($id);
            $ssh->toggleLock();

            return response()->json([
                'success' => true,
                'message' => 'Status lock berhasil diubah',
                'is_locked' => $ssh->is_locked,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengubah status: '.$e->getMessage(),
            ], 500);
        }
    }

    // API untuk mendapatkan data berdasarkan kelompok
    public function getByKelompok(Request $request)
    {
        $idKelompok = $request->get('id_kelompok');
        $tahun = $request->get('tahun');

        if (! $idKelompok) {
            return response()->json([
                'success' => false,
                'message' => 'ID Kelompok tidak valid',
            ], 400);
        }

        $query = DataSSH::where('id_kel_standar_harga', $idKelompok);

        if ($tahun) {
            $query->where('tahun', $tahun);
        }

        $data = $query->orderBy('kode_standar_harga', 'asc')->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
