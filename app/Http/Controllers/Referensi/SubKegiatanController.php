<?php

namespace App\Http\Controllers\Referensi;

use App\Http\Controllers\Controller;
use App\Models\Referensi\SubKegiatan;
use App\Models\Referensi\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SubKegiatanController extends Controller
{
    public function index()
    {
        $data = collect();
        try {
            $subKegiatan = SubKegiatan::with(['kegiatan.program.bidangUrusan.urusan'])
                ->join('kegiatan', 'sub_kegiatan.id_kegiatan', '=', 'kegiatan.id')
                ->join('program', 'kegiatan.id_program', '=', 'program.id')
                ->join('bidang_urusan', 'program.id_bidang_urusan', '=', 'bidang_urusan.id')
                ->join('urusan', 'bidang_urusan.id_urusan', '=', 'urusan.id')
                ->select([
                    'sub_kegiatan.*',
                    'kegiatan.nama_kegiatan',
                    'kegiatan.kode_kegiatan',
                    'program.nama_program',
                    'program.kode_program',
                    'bidang_urusan.nama_bidang_urusan',
                    'bidang_urusan.kode_bidang_urusan',
                    'urusan.nama_urusan',
                    'urusan.kode_urusan',
                    'urusan.id as id_urusan',
                    'bidang_urusan.id as id_bidang_urusan',
                    'program.id as id_program',
                    'kegiatan.id as id_kegiatan'
                ])
                ->orderBy('urusan.kode_urusan', 'asc')
                ->orderBy('bidang_urusan.kode_bidang_urusan', 'asc')
                ->orderBy('program.kode_program', 'asc')
                ->orderBy('kegiatan.kode_kegiatan', 'asc')
                ->orderBy('sub_kegiatan.kode_sub_kegiatan', 'asc')
                ->get();

            // Group by id_urusan untuk struktur yang sesuai dengan view
            $data = $subKegiatan->groupBy('id_urusan')->map(function ($group) {
                return $group->sortBy([
                    ['kode_bidang_urusan', 'asc'],
                    ['kode_program', 'asc'],
                    ['kode_kegiatan', 'asc'],
                    ['kode_sub_kegiatan', 'asc']
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Error fetching sub kegiatan data: ' . $e->getMessage());
            $data = collect();
        }

        // Get list kegiatan for dropdown (dengan data program, bidang urusan, dan urusan)
        $listKegiatan = Kegiatan::with(['program.bidangUrusan.urusan'])
            ->join('program', 'kegiatan.id_program', '=', 'program.id')
            ->join('bidang_urusan', 'program.id_bidang_urusan', '=', 'bidang_urusan.id')
            ->join('urusan', 'bidang_urusan.id_urusan', '=', 'urusan.id')
            ->select([
                'kegiatan.*',
                'program.nama_program',
                'program.kode_program',
                'bidang_urusan.nama_bidang_urusan',
                'bidang_urusan.kode_bidang_urusan',
                'urusan.nama_urusan',
                'urusan.kode_urusan'
            ])
            ->orderBy('urusan.nama_urusan')
            ->orderBy('bidang_urusan.nama_bidang_urusan')
            ->orderBy('program.nama_program')
            ->orderBy('kegiatan.nama_kegiatan')
            ->get();

        return view('referensi.sub-kegiatan.index', compact('data', 'listKegiatan'));
    }

    public function store(Request $request)
    {
        $rules = [
            'kode_sub_kegiatan' => 'required|string|max:20|unique:sub_kegiatan,kode_sub_kegiatan',
            'nama_sub_kegiatan' => 'required|string|max:500',
            'id_kegiatan' => 'required|exists:kegiatan,id'
        ];

        $messages = [
            'kode_sub_kegiatan.required' => 'Kode sub kegiatan wajib diisi.',
            'kode_sub_kegiatan.unique' => 'Kode sub kegiatan sudah digunakan.',
            'kode_sub_kegiatan.max' => 'Kode sub kegiatan maksimal 20 karakter.',
            'nama_sub_kegiatan.required' => 'Nama sub kegiatan wajib diisi.',
            'nama_sub_kegiatan.max' => 'Nama sub kegiatan maksimal 500 karakter.',
            'id_kegiatan.required' => 'Kegiatan wajib dipilih.',
            'id_kegiatan.exists' => 'Kegiatan tidak valid.'
        ];

        try {
            $validated = $request->validate($rules, $messages);

            DB::beginTransaction();

            // Create sub kegiatan - ID akan otomatis di-generate oleh model
            $subKegiatan = SubKegiatan::create([
                'kode_sub_kegiatan' => $validated['kode_sub_kegiatan'],
                'nama_sub_kegiatan' => $validated['nama_sub_kegiatan'],
                'id_kegiatan' => $validated['id_kegiatan'],
                'user_id' => Auth::id(),
                'time_stamp' => now()
            ]);

            DB::commit();

            return redirect()->route('referensi.sub-kegiatan.index')
                ->with('success', 'Sub Kegiatan berhasil ditambahkan.');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating sub kegiatan: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Gagal menambahkan sub kegiatan. Silakan coba lagi.')
                ->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $subKegiatan = SubKegiatan::with(['kegiatan.program.bidangUrusan.urusan'])->findOrFail($id);

            $listKegiatan = Kegiatan::with(['program.bidangUrusan.urusan'])
                ->join('program', 'kegiatan.id_program', '=', 'program.id')
                ->join('bidang_urusan', 'program.id_bidang_urusan', '=', 'bidang_urusan.id')
                ->join('urusan', 'bidang_urusan.id_urusan', '=', 'urusan.id')
                ->select([
                    'kegiatan.*',
                    'program.nama_program',
                    'program.kode_program',
                    'bidang_urusan.nama_bidang_urusan',
                    'bidang_urusan.kode_bidang_urusan',
                    'urusan.nama_urusan',
                    'urusan.kode_urusan'
                ])
                ->orderBy('urusan.nama_urusan')
                ->orderBy('bidang_urusan.nama_bidang_urusan')
                ->orderBy('program.nama_program')
                ->orderBy('kegiatan.nama_kegiatan')
                ->get();

            return view('referensi.sub-kegiatan.edit', compact('subKegiatan', 'listKegiatan'));
        } catch (\Exception $e) {
            Log::error('Error fetching sub kegiatan for edit: ' . $e->getMessage());
            return redirect()->route('referensi.sub-kegiatan.index')
                ->with('error', 'Sub Kegiatan tidak ditemukan.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $subKegiatan = SubKegiatan::findOrFail($id);

            $rules = [
                'kode_sub_kegiatan' => 'required|string|max:20|unique:sub_kegiatan,kode_sub_kegiatan,' . $id,
                'nama_sub_kegiatan' => 'required|string|max:500',
                'id_kegiatan' => 'required|exists:kegiatan,id'
            ];

            $messages = [
                'kode_sub_kegiatan.required' => 'Kode sub kegiatan wajib diisi.',
                'kode_sub_kegiatan.unique' => 'Kode sub kegiatan sudah digunakan.',
                'kode_sub_kegiatan.max' => 'Kode sub kegiatan maksimal 20 karakter.',
                'nama_sub_kegiatan.required' => 'Nama sub kegiatan wajib diisi.',
                'nama_sub_kegiatan.max' => 'Nama sub kegiatan maksimal 500 karakter.',
                'id_kegiatan.required' => 'Kegiatan wajib dipilih.',
                'id_kegiatan.exists' => 'Kegiatan tidak valid.'
            ];

            $validated = $request->validate($rules, $messages);

            DB::beginTransaction();

            $subKegiatan->update([
                'kode_sub_kegiatan' => $validated['kode_sub_kegiatan'],
                'nama_sub_kegiatan' => $validated['nama_sub_kegiatan'],
                'id_kegiatan' => $validated['id_kegiatan'],
                'user_id' => Auth::id(),
                'time_stamp' => now()
            ]);

            DB::commit();

            return redirect()->route('referensi.sub-kegiatan.index')
                ->with('success', 'Sub Kegiatan berhasil diperbarui.');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating sub kegiatan: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Gagal memperbarui sub kegiatan. Silakan coba lagi.')
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $subKegiatan = SubKegiatan::findOrFail($id);
            $subKegiatanName = $subKegiatan->nama_sub_kegiatan;

            $subKegiatan->delete();

            DB::commit();

            return redirect()->route('referensi.sub-kegiatan.index')
                ->with('success', "Sub Kegiatan '{$subKegiatanName}' berhasil dihapus.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting sub kegiatan: ' . $e->getMessage());

            return redirect()->route('referensi.sub-kegiatan.index')
                ->with('error', 'Gagal menghapus sub kegiatan. Silakan coba lagi.');
        }
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:sub_kegiatan,id'
        ], [
            'ids.required' => 'Pilih minimal satu sub kegiatan untuk dihapus.',
            'ids.array' => 'Data tidak valid.',
            'ids.min' => 'Pilih minimal satu sub kegiatan untuk dihapus.',
            'ids.*.exists' => 'Sub kegiatan tidak ditemukan.'
        ]);

        try {
            DB::beginTransaction();

            $deletedCount = SubKegiatan::whereIn('id', $request->ids)->delete();

            DB::commit();

            return redirect()->route('referensi.sub-kegiatan.index')
                ->with('success', "Berhasil menghapus {$deletedCount} sub kegiatan.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error bulk deleting sub kegiatan: ' . $e->getMessage());

            return redirect()->route('referensi.sub-kegiatan.index')
                ->with('error', 'Gagal menghapus sub kegiatan terpilih. Silakan coba lagi.');
        }
    }
}
