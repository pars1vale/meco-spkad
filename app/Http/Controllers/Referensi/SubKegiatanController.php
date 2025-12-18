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
        // UBAH: Tidak perlu lagi query data di sini
        // Hanya get list kegiatan untuk dropdown di modal
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

        return view('referensi.sub-kegiatan.index', compact('listKegiatan'));
    }

    // METHOD BARU untuk Ajax Server-Side
    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $query = SubKegiatan::with(['kegiatan.program.bidangUrusan.urusan'])
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
                ]);

            // Total records tanpa filter
            $totalRecords = $query->count();

            // Global search
            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $query->where(function ($q) use ($search) {
                    $q->where('sub_kegiatan.kode_sub_kegiatan', 'like', "%{$search}%")
                        ->orWhere('sub_kegiatan.nama_sub_kegiatan', 'like', "%{$search}%")
                        ->orWhere('kegiatan.kode_kegiatan', 'like', "%{$search}%")
                        ->orWhere('kegiatan.nama_kegiatan', 'like', "%{$search}%")
                        ->orWhere('program.kode_program', 'like', "%{$search}%")
                        ->orWhere('program.nama_program', 'like', "%{$search}%")
                        ->orWhere('bidang_urusan.kode_bidang_urusan', 'like', "%{$search}%")
                        ->orWhere('bidang_urusan.nama_bidang_urusan', 'like', "%{$search}%")
                        ->orWhere('urusan.kode_urusan', 'like', "%{$search}%")
                        ->orWhere('urusan.nama_urusan', 'like', "%{$search}%");
                });
            }

            // Total records setelah filter
            $totalFiltered = $query->count();

            // Sorting
            if ($request->has('order')) {
                $orderColumnIndex = $request->order[0]['column'];
                $orderDir = $request->order[0]['dir'];

                // Columns: 0=checkbox, 1=kode_sub_kegiatan, 2=nama_sub_kegiatan, 3=urusan_group, 4=bidang_group, 5=program_group, 6=kegiatan_group, 7=actions
                $columns = [
                    'sub_kegiatan.id',
                    'sub_kegiatan.kode_sub_kegiatan',
                    'sub_kegiatan.nama_sub_kegiatan',
                    'urusan.nama_urusan',
                    'bidang_urusan.nama_bidang_urusan',
                    'program.nama_program',
                    'kegiatan.nama_kegiatan',
                    'sub_kegiatan.id'
                ];

                if (isset($columns[$orderColumnIndex])) {
                    $query->orderBy($columns[$orderColumnIndex], $orderDir);
                }
            } else {
                // Default sorting
                $query->orderBy('urusan.kode_urusan', 'asc')
                    ->orderBy('bidang_urusan.kode_bidang_urusan', 'asc')
                    ->orderBy('program.kode_program', 'asc')
                    ->orderBy('kegiatan.kode_kegiatan', 'asc')
                    ->orderBy('sub_kegiatan.kode_sub_kegiatan', 'asc');
            }

            // Pagination
            $start = $request->start ?? 0;
            $length = $request->length ?? 10;

            $data = $query->skip($start)
                ->take($length)
                ->get();

            // Format data untuk DataTables
            $formattedData = [];
            foreach ($data as $item) {
                $formattedData[] = [
                    'id' => $item->id,
                    'kode_sub_kegiatan' => $item->kode_sub_kegiatan,
                    'nama_sub_kegiatan' => $item->nama_sub_kegiatan,
                    'urusan_group' => '[URUSAN] ' . $item->kode_urusan . ' ' . $item->nama_urusan,
                    'bidang_group' => '[BIDANG URUSAN] ' . $item->kode_bidang_urusan . ' ' . $item->nama_bidang_urusan,
                    'program_group' => '[PROGRAM] ' . $item->kode_program . ' ' . $item->nama_program,
                    'kegiatan_group' => '[KEGIATAN] ' . $item->kode_kegiatan . ' ' . $item->nama_kegiatan,
                    'kode_urusan' => $item->kode_urusan,
                    'nama_urusan' => $item->nama_urusan,
                    'kode_bidang_urusan' => $item->kode_bidang_urusan,
                    'nama_bidang_urusan' => $item->nama_bidang_urusan,
                    'kode_program' => $item->kode_program,
                    'nama_program' => $item->nama_program,
                    'kode_kegiatan' => $item->kode_kegiatan,
                    'nama_kegiatan' => $item->nama_kegiatan,
                    'actions' => $item->id
                ];
            }

            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => $totalRecords,
                'recordsFiltered' => $totalFiltered,
                'data' => $formattedData
            ]);
        }

        return response()->json(['error' => 'Invalid request'], 400);
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

            SubKegiatan::create([
                'kode_sub_kegiatan' => $validated['kode_sub_kegiatan'],
                'nama_sub_kegiatan' => $validated['nama_sub_kegiatan'],
                'id_kegiatan' => $validated['id_kegiatan'],
                'user_id' => session('id_user'),
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
                'user_id' => session('id_user'),
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
