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

    public function edit($id)
    {
        $data = DataUnit::findOrFail($id);
        $data_bidur = BidangUrusan::all();
        $pangkat = Pangkat::all();

        return view('pengaturan.profil.perangkat-daerah.edit', compact('data', 'data_bidur', 'pangkat'));
    }

    public function update(Request $request, $id)
    {
        $data = DataUnit::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'nama_skpd'     => 'required|string|max:255',
            'nipkepala'     => 'nullable|string|max:255',
            'namakepala'    => 'nullable|string|max:255',
            'pangkatkepala' => 'nullable|exists:pangkat,id',
            'statuskepala'  => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()
                ->with('error', 'Gagal memperbarui data.');
        }

        try {
            $data->update([
                'nama_skpd'     => $request->nama_skpd,
                'nipkepala'     => $request->nipkepala,
                'namakepala'    => $request->namakepala,
                'pangkatkepala' => $request->pangkatkepala,
                'statuskepala'  => $request->statuskepala,
                'updated_at'    => now(),
            ]);

            return redirect()->route('pengaturan.perangkat-daerah.index')
                ->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $data = DataUnit::findOrFail($id);
            $data->delete();

            return back()->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return back()->with('error', 'Tidak ada data yang dipilih untuk dihapus.');
        }

        try {
            DataUnit::whereIn('id', $ids)->delete();
            return back()->with('success', 'Data terpilih berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Kesalahan: ' . $e->getMessage());
        }
    }
}
