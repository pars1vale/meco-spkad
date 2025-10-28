<?php

namespace App\Http\Controllers\Pengaturan\Profil\PerangkatDaerah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengaturan\Profil\PerangkatDaerah\DataUnit;
use App\Models\Referensi\BidangUrusan;
use App\Models\Pangkat;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class DataUnitController extends Controller
{
    public function index()
    {
        $data = DataUnit::all();
        $data_bidur = BidangUrusan::all();
        $pangkat = Pangkat::all();
        return view('pengaturan.profil.perangkat-daerah.index',compact('data','data_bidur','pangkat'));
    }

    public function store(Request $request)
    {
            $validator = Validator::make($request->all(), [
                'bidur1'         => 'required|exists:bidang_urusan,id',
                'bidur2'         => 'nullable|exists:bidang_urusan,id',
                'bidur3'         => 'nullable|exists:bidang_urusan,id',
                'kode_skpd_1'    => 'required|string|max:50',
                'kode_skpd_2'    => 'nullable|string|max:50',
                'nama_skpd'      => 'required|string|max:255',
                'nipkepala'      => 'required|string|max:255',
                'namakepala'     => 'required|string|max:255',
                'pangkatkepala'  => 'nullable|exists:pangkat,id',
                'statuskepala'   => 'nullable|string|max:50',
                'ispendapatan'   => 'nullable|boolean',
            ], [
                'bidur1.required' => 'Bidang urusan 1 wajib dipilih.',
                'kode_skpd_1.required' => 'Kode SKPD wajib diisi.',
                'nama_skpd.required' => 'Nama SKPD wajib diisi.',
                'nipkepala.required' => 'NIP Kepala wajib diisi.',
                'namakepala.required' => 'Nama Kepala wajib diisi.',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Gagal menambah SKPD. Silakan periksa input Anda.');
            }

            try {
                DB::transaction(function () use ($request) {

                    // === DAPATKAN ID BARU ===
                    $maxId = DB::table('data_unit')->lockForUpdate()->max('id') ?? 0;
                    $newId = $maxId + 1;

                    // === AMBIL kode_bidang_urusan DARI TABEL bidang_urusan ===
                    $bidur1Kode = DB::table('bidang_urusan')
                        ->where('id', $request->bidur1)
                        ->value('kode_bidang_urusan');

                    $bidur2Kode = $request->bidur2
                        ? DB::table('bidang_urusan')->where('id', $request->bidur2)->value('kode_bidang_urusan')
                        : null;

                    $bidur3Kode = $request->bidur3
                        ? DB::table('bidang_urusan')->where('id', $request->bidur3)->value('kode_bidang_urusan')
                        : null;

                    if (!$bidur1Kode) {
                        throw new \Exception("Kode bidang urusan 1 tidak ditemukan untuk ID {$request->bidur1}");
                    }

                    // === ATUR NILAI DEFAULT ===
                    $bidur1 = $bidur1Kode;
                    $bidur2 = $bidur2Kode ?: '0.00';
                    $bidur3 = $bidur3Kode ?: '0.00';
                    $kode1  = $request->kode_skpd_1;
                    $kode2  = $request->kode_skpd_2 ?: '0000';

                    // === GABUNGKAN JADI kode_skpd ===
                    $kode_skpd = "{$bidur1}.{$bidur2}.{$bidur3}.{$kode1}.{$kode2}";
                    // contoh hasil: 1.05.0.00.0.00.05.0000

                    // === SIMPAN KE DATABASE ===
                    DB::table('data_unit')->insert([
                        'id'             => $newId,
                        'bidur_1'        => $request->bidur1, // simpan ID referensi
                        'bidur_2'        => $request->bidur2,
                        'bidur_3'        => $request->bidur3,
                        'kode_skpd'      => $kode_skpd,
                        'kode_skpd_1'    => $kode1,
                        'kode_skpd_2'    => $kode2,
                        'status'         => 'SKPD',
                        'nama_skpd'      => $request->nama_skpd,
                        'nipkepala'      => $request->nipkepala,
                        'namakepala'     => $request->namakepala,
                        'pangkatkepala'  => $request->pangkatkepala,
                        'statuskepala'   => $request->statuskepala,
                        'ispendapatan'   => $request->has('ispendapatan') ? 1 : 0,
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ]);
                });

                return redirect()->route('pengaturan.perangkat-daerah.index')
                    ->with('success', 'Data SKPD berhasil ditambahkan.');

            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Terjadi kesalahan saat menyimpan SKPD: ' . $e->getMessage());
            }
    }

    public function unitskpdstore(Request $request)
{
    $validator = Validator::make($request->all(), [
        'skpd_id'            => 'required|exists:data_unit,id',
        'kode_unit'          => 'required|string|max:10',
        'nama_unit'          => 'required|string|max:255',
        'nip_kepala_unit'    => 'nullable|string|max:255',
        'nama_kepala_unit'   => 'nullable|string|max:255',
        'pangkat_kepala_unit'=> 'nullable|exists:pangkat,id',
        'status_kepala_unit' => 'nullable|string|max:50',
    ], [
        'skpd_id.required'   => 'SKPD induk wajib dipilih.',
        'kode_unit.required' => 'Kode Unit wajib diisi.',
        'nama_unit.required' => 'Nama Unit SKPD wajib diisi.',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput()
            ->with('error', 'Gagal menambah Unit SKPD. Silakan periksa input Anda.');
    }

    try {
        DB::transaction(function () use ($request) {
            // Ambil data SKPD induk
            $skpd = DB::table('data_unit')->where('id', $request->skpd_id)->first();

            if (!$skpd) {
                throw new \Exception('SKPD induk tidak ditemukan.');
            }

            // Dapatkan ID baru
            $maxId = DB::table('data_unit')->lockForUpdate()->max('id') ?? 0;
            $newId = $maxId + 1;

            // ====== REVISI PEMBUATAN KODE UNIT ======
            // Pecah kode SKPD induk jadi array
            $kodeParts = explode('.', $skpd->kode_skpd);

            // Ganti elemen terakhir (biasanya 0000) dengan kode unit baru
            $kodeParts[count($kodeParts) - 1] = str_pad($request->kode_unit, 4, '0', STR_PAD_LEFT);

            // Gabungkan kembali jadi kode utuh
            $kode_unit_full = implode('.', $kodeParts);
            // ==========================================

            // Simpan ke database
            DB::table('data_unit')->insert([
                'id'               => $newId,
                'bidur_1'          => $skpd->bidur_1,
                'bidur_2'          => $skpd->bidur_2,
                'bidur_3'          => $skpd->bidur_3,
                'kode_skpd'        => $kode_unit_full,
                'kode_skpd_1'      => $skpd->kode_skpd_1,
                'kode_skpd_2'      => $request->kode_unit,
                'status'           => 'Unit SKPD',
                'nama_skpd'        => $request->nama_unit,
                'nipkepala'        => $request->nip_kepala_unit,
                'namakepala'       => $request->nama_kepala_unit,
                'pangkatkepala'    => $request->pangkat_kepala_unit,
                'statuskepala'     => $request->status_kepala_unit,
                'ispendapatan'     => 0,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        });

        return redirect()->route('pengaturan.perangkat-daerah.index')
            ->with('success', 'Data Unit SKPD berhasil ditambahkan.');

    } catch (\Exception $e) {
        return redirect()->back()
            ->withInput()
            ->with('error', 'Terjadi kesalahan saat menyimpan Unit SKPD: ' . $e->getMessage());
    }
}







    // public function edit($id)
    // {
    //     try {
    //         $urusan = Urusan::findOrFail($id);
    //         return view('referensi.urusan.edit', compact('urusan'));
    //     } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
    //         return redirect()->route('referensi.urusan.index')
    //             ->with('error', 'Data urusan tidak ditemukan.');
    //     }
    // }

    // public function update(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'kode_urusan' => 'required|max:10|unique:urusan,kode_urusan,' . $id,
    //         'nama_urusan' => 'required|max:255',
    //     ], [
    //         'kode_urusan.required' => 'Kode urusan wajib diisi.',
    //         'kode_urusan.unique' => 'Kode urusan sudah digunakan.',
    //         'kode_urusan.max' => 'Kode urusan maksimal 10 karakter.',
    //         'nama_urusan.required' => 'Nama urusan wajib diisi.',
    //         'nama_urusan.max' => 'Nama urusan maksimal 255 karakter.',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()
    //             ->withErrors($validator)
    //             ->withInput()
    //             ->with('error', 'Gagal memperbarui data urusan. Silakan periksa input Anda.');
    //     }

    //     try {
    //         $urusan = Urusan::findOrFail($id);
    //         $urusan->update([
    //             'kode_urusan' => $request->kode_urusan,
    //             'nama_urusan' => $request->nama_urusan,
    //         ]);

    //         return redirect()->route('referensi.urusan.index')
    //             ->with('success', 'Data urusan berhasil diperbarui.');
    //     } catch (\Exception $e) {
    //         return redirect()->back()
    //             ->withInput()
    //             ->with('error', 'Gagal memperbarui data urusan. ' . $e->getMessage());
    //     }
    // }

    // public function destroy($id)
    // {
    //     try {
    //         $urusan = Urusan::findOrFail($id);

    //         // Cek apakah urusan memiliki relasi dengan bidang urusan
    //         if ($urusan->bidangUrusan()->exists()) {
    //             return redirect()->back()
    //                 ->with('error', 'Tidak dapat menghapus urusan karena masih memiliki bidang urusan terkait.');
    //         }

    //         $namaUrusan = $urusan->nama_urusan;
    //         $urusan->delete();

    //         return redirect()->route('referensi.urusan.index')
    //             ->with('success', "Data urusan '{$namaUrusan}' berhasil dihapus.");
    //     } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
    //         return redirect()->back()
    //             ->with('error', 'Data urusan tidak ditemukan.');
    //     } catch (\Exception $e) {
    //         return redirect()->back()
    //             ->with('error', 'Gagal menghapus data urusan. ' . $e->getMessage());
    //     }
    // }

    // public function bulkDelete(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'ids' => 'required|array|min:1',
    //         'ids.*' => 'integer|exists:urusan,id',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()
    //             ->with('error', 'Data yang dipilih tidak valid.');
    //     }

    //     try {
    //         $ids = $request->ids;

    //         // Cek apakah ada urusan yang memiliki relasi dengan bidang urusan
    //         $urusanWithRelations = Urusan::whereIn('id', $ids)
    //             ->whereHas('bidangUrusan')
    //             ->pluck('nama_urusan')
    //             ->toArray();

    //         if (!empty($urusanWithRelations)) {
    //             return redirect()->back()
    //                 ->with('error', 'Tidak dapat menghapus urusan: ' . implode(', ', $urusanWithRelations) . ' karena masih memiliki bidang urusan terkait.');
    //         }

    //         $deletedCount = Urusan::whereIn('id', $ids)->delete();

    //         return redirect()->back()
    //             ->with('success', "{$deletedCount} data urusan berhasil dihapus.");
    //     } catch (\Exception $e) {
    //         return redirect()->back()
    //             ->with('error', 'Gagal menghapus data urusan. ' . $e->getMessage());
    //     }
    // }
}
