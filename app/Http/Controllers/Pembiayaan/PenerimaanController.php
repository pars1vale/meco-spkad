<?php

namespace App\Http\Controllers\Pembiayaan;

use App\Http\Controllers\Controller;
use App\Models\Pembiayaan\Pembiayaan;
use App\Models\Pengaturan\Profil\PerangkatDaerah\DataUnit;
use App\Models\Referensi\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenerimaanController extends Controller
{
    public function index(Request $request)
    {
        $tahunAnggaran = session('tahun_anggaran', date('Y'));

        return view('pembiayaan.penerimaan.index', compact('tahunAnggaran'));
    }

    public function getDataIndex(Request $request)
    {
        try {
            $tahunAnggaran = session('tahun_anggaran', date('Y'));

            $query = DataUnit::select('data_unit.*')
                ->distinct()
                ->join('data_pembiayaan as dp', 'data_unit.id_skpd', '=', 'dp.id_skpd')
                ->where('dp.type', 'penerimaan')
                ->where('dp.active', 1)
                ->where('dp.tahun_anggaran', 2025);

            if ($search = $request->input('search.value')) {
                $query->where(function ($q) use ($search) {
                    $q->where('data_unit.nama_skpd', 'like', "%{$search}%")
                        ->orWhere('data_unit.kode_skpd', 'like', "%{$search}%")
                        ->orWhere('data_unit.namaunit', 'like', "%{$search}%")
                        ->orWhere('data_unit.kodeunit', 'like', "%{$search}%");
                });
            }

            $recordsTotal = (clone $query)->count('data_unit.id');
            $recordsFiltered = $recordsTotal;

            $colMap = [
                0 => 'data_unit.id',
                1 => 'data_unit.kode_skpd',
                2 => 'data_unit.nama_skpd',
                3 => 'data_unit.nama_skpd',
                4 => 'data_unit.nama_skpd',
            ];
            $colIdx = (int) $request->input('order.0.column', 1);
            $orderBy = $colMap[$colIdx] ?? 'data_unit.kode_skpd';
            $orderDir = $request->input('order.0.dir', 'asc') === 'desc' ? 'desc' : 'asc';
            $query->orderBy($orderBy, $orderDir);

            $start = max(0, (int) $request->input('start', 0));
            $length = (int) $request->input('length', 10);
            $skpdList = ($length > 0)
                ? $query->skip($start)->take($length)->get()
                : $query->get();

            $idSkpdList = $skpdList->pluck('id_skpd')->filter()->unique()->values()->toArray();
            $totals = collect();

            if (! empty($idSkpdList)) {
                $totals = Pembiayaan::select(
                    'id_skpd',
                    DB::raw('SUM(nilaimurni) as total_sebelum'),
                    DB::raw('SUM(total) as total_setelah')
                )
                    ->whereIn('id_skpd', $idSkpdList)
                    ->where('type', 'penerimaan')
                    ->where('active', 1)
                    ->where('tahun_anggaran', 2025)
                    ->groupBy('id_skpd')
                    ->get()
                    ->keyBy('id_skpd');
            }

            $rows = $skpdList->map(function ($skpd) use ($totals) {
                $total = $totals->get($skpd->id_skpd);

                return [
                    'id' => $skpd->id,
                    'id_skpd' => $skpd->id_skpd,
                    'kode_skpd' => $skpd->kode_skpd ?? $skpd->kodeunit,
                    'nama_skpd' => $skpd->nama_skpd ?? $skpd->namaunit,
                    'namakepala' => $skpd->namakepala ?? null,
                    'total_sebelum' => (float) ($total->total_sebelum ?? 0),
                    'total_setelah' => (float) ($total->total_setelah ?? 0),
                ];
            });

            $grandTotal = Pembiayaan::from('data_pembiayaan as dp')
                ->join('data_unit as du', 'dp.id_skpd', '=', 'du.id_skpd')
                ->where('dp.type', 'penerimaan')
                ->where('dp.active', 1)
                ->where('dp.tahun_anggaran', 2025)
                ->selectRaw('COALESCE(SUM(dp.nilaimurni),0) as grand_sebelum, COALESCE(SUM(dp.total),0) as grand_setelah')
                ->first();

            return response()->json([
                'draw' => (int) $request->input('draw'),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $rows->values(),
                'grandTotal' => [
                    'sebelum' => (float) $grandTotal->grand_sebelum,
                    'setelah' => (float) $grandTotal->grand_setelah,
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'draw' => (int) $request->input('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function rincian(Request $request, $id_skpd)
    {
        $tahunAnggaran = session('tahun_anggaran', date('Y'));

        $skpd = DataUnit::where('id_skpd', $id_skpd)->firstOrFail();

        $totalSetelah = Pembiayaan::where('id_skpd', $id_skpd)
            ->where('type', 'penerimaan')
            ->where('active', 1)
            ->where('tahun_anggaran', 2025)
            ->sum('total');

        $totalSebelum = Pembiayaan::where('id_skpd', $id_skpd)
            ->where('type', 'penerimaan')
            ->where('active', 1)
            ->where('tahun_anggaran', 2025)
            ->sum('nilaimurni');

        $jumlahRekening = Pembiayaan::where('id_skpd', $id_skpd)
            ->where('type', 'penerimaan')
            ->where('active', 1)
            ->where('tahun_anggaran', 2025)
            ->select('id_akun')
            ->distinct()
            ->count();

        $akunList = Akun::where('active', 1)
            ->where('is_pembiayaan', 1)
            ->where('tahun_anggaran', 2025)
            ->orderBy('kode_akun')
            ->get();

        return view('pembiayaan.penerimaan.rincian.index', compact(
            'skpd',
            'id_skpd',
            'totalSetelah',
            'totalSebelum',
            'jumlahRekening',
            'tahunAnggaran',
            'akunList'
        ));
    }

    public function getDataRincian(Request $request, $id_skpd)
    {
        try {
            $tahunAnggaran = session('tahun_anggaran', date('Y'));

            $query = Pembiayaan::from('data_pembiayaan as dp')
                ->leftJoin('akun as a', 'dp.id_akun', '=', 'a.id')
                ->select([
                    'dp.id',
                    'dp.id_skpd',
                    'dp.id_akun',
                    'dp.kode_akun',
                    'dp.nama_akun',
                    'dp.uraian',
                    'dp.keterangan',
                    'dp.nilaimurni',
                    'dp.total',
                    'dp.rekening',
                    'a.kode_akun as akun_kode',
                    'a.nama_akun as akun_nama',
                ])
                ->where('dp.id_skpd', $id_skpd)
                ->where('dp.type', 'penerimaan')
                ->where('dp.active', 1)
                ->where('dp.tahun_anggaran', 2025);

            if ($search = $request->input('search.value')) {
                $query->where(function ($q) use ($search) {
                    $q->where('a.kode_akun', 'like', "%{$search}%")
                        ->orWhere('a.nama_akun', 'like', "%{$search}%")
                        ->orWhere('dp.kode_akun', 'like', "%{$search}%")
                        ->orWhere('dp.nama_akun', 'like', "%{$search}%")
                        ->orWhere('dp.uraian', 'like', "%{$search}%")
                        ->orWhere('dp.keterangan', 'like', "%{$search}%");
                });
            }

            $recordsTotal = Pembiayaan::where('id_skpd', $id_skpd)
                ->where('type', 'penerimaan')
                ->where('active', 1)
                ->where('tahun_anggaran', 2025)
                ->count();

            $recordsFiltered = (clone $query)->count();

            $colMap = [
                0 => 'dp.id',
                1 => 'dp.id',
                2 => 'dp.kode_akun',
                3 => 'dp.uraian',
                4 => 'dp.keterangan',
                5 => 'dp.nilaimurni',
                6 => 'dp.total',
            ];
            $colIdx = (int) $request->input('order.0.column', 2);
            $orderBy = $colMap[$colIdx] ?? 'dp.kode_akun';
            $orderDir = $request->input('order.0.dir', 'asc') === 'desc' ? 'desc' : 'asc';
            $query->orderBy($orderBy, $orderDir);

            $start = max(0, (int) $request->input('start', 0));
            $length = (int) $request->input('length', 25);
            $rows = ($length > 0)
                ? $query->skip($start)->take($length)->get()
                : $query->get();

            $grandTotal = Pembiayaan::where('id_skpd', $id_skpd)
                ->where('type', 'penerimaan')
                ->where('active', 1)
                ->where('tahun_anggaran', 2025)
                ->selectRaw('SUM(nilaimurni) as total_sebelum, SUM(total) as total_setelah, COUNT(id) as jumlah')
                ->first();

            return response()->json([
                'draw' => (int) $request->input('draw'),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $rows,
                'grandTotal' => [
                    'sebelum' => (float) ($grandTotal->total_sebelum ?? 0),
                    'setelah' => (float) ($grandTotal->total_setelah ?? 0),
                    'jumlah' => (int) ($grandTotal->jumlah ?? 0),
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'draw' => (int) $request->input('draw'),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request, $id_skpd)
    {
        $request->validate([
            'id_akun' => 'required|integer|exists:akun,id',
            'keterangan' => 'nullable|string|max:500',
            'nilai' => 'required|numeric|min:0',
        ]);

        $tahunAnggaran = session('tahun_anggaran', date('Y'));
        $akun = Akun::findOrFail($request->id_akun);
        $now = now();
        $maxIdPembiayaan = DB::table('data_pembiayaan')->max('id_pembiayaan');

        DB::table('data_pembiayaan')->insert([
            'id_pembiayaan' => ($maxIdPembiayaan ?? 0) + 1,
            'createddate' => $now->format('Y-m-d'),
            'createdtime' => $now->format('H:i:s'),
            'updateddate' => $now->format('Y-m-d'),
            'updatedtime' => $now->format('H:i:s'),
            'update_at' => $now,
            'created_user' => auth()->id(),
            'updated_user' => auth()->id(),
            'id_skpd' => $id_skpd,
            'id_akun' => $akun->id,
            'kode_akun' => $akun->kode_akun,
            'nama_akun' => $akun->nama_akun,
            'rekening' => $akun->kode_akun.' - '.$akun->nama_akun,
            'keterangan' => $request->keterangan,
            'uraian' => $request->keterangan,
            'nilaimurni' => $request->nilai,
            'total' => $request->nilai,
            'type' => 'penerimaan',
            'volume' => 0,
            'active' => 1,
            'tahun_anggaran' => 2025, // masih hardcode, karena belum ada pilihan tahun anggaran di form login
            'id_jadwal_murni' => null,
            'program_koordinator' => null,
            'skpd_koordinator' => null,
            'urusan_koordinator' => null,
            'pagu_fmis' => null,
            'koefisien' => null,
            'kua_murni' => null,
            'kua_pak' => null,
            'rkpd_murni' => null,
            'rkpd_pak' => null,
            'satuan' => null,
            'user1' => null,
            'user2' => null,
        ]);

        return redirect()
            ->route('pembiayaan.penerimaan.rincian', $id_skpd)
            ->with('success', 'Data pembiayaan penerimaan berhasil ditambahkan.');
    }

    public function edit($id_skpd, $id_pembiayaan)
    {
        $tahunAnggaran = session('tahun_anggaran', date('Y'));

        $skpd = DataUnit::where('id_skpd', $id_skpd)->firstOrFail();

        $pembiayaan = DB::table('data_pembiayaan')
            ->where('id', $id_pembiayaan)
            ->where('id_skpd', $id_skpd)
            ->where('type', 'penerimaan')
            ->where('active', 1)
            ->first();

        abort_if(! $pembiayaan, 404, 'Data tidak ditemukan.');

        $akunList = Akun::where('active', 1)
            ->where('is_pembiayaan', 1)
            ->where('tahun_anggaran', 2025)
            ->orderBy('kode_akun')
            ->get();

        return view('pembiayaan.penerimaan.rincian.edit', compact(
            'skpd',
            'pembiayaan',
            'akunList',
            'id_skpd',
            'tahunAnggaran'
        ));
    }

    public function update(Request $request, $id_skpd, $id)
    {
        $request->validate([
            'id_akun' => 'required|integer|exists:akun,id',
            'keterangan' => 'nullable|string|max:500',
            'nilai' => 'required|numeric|min:0',
        ]);

        $akun = Akun::findOrFail($request->id_akun);
        $now = now();

        $affected = DB::table('data_pembiayaan')
            ->where('id', $id)
            ->where('id_skpd', $id_skpd)
            ->where('type', 'penerimaan')
            ->where('active', 1)
            ->update([
                'id_akun' => $akun->id,
                'kode_akun' => $akun->kode_akun,
                'nama_akun' => $akun->nama_akun,
                'rekening' => $akun->kode_akun.' - '.$akun->nama_akun,
                'keterangan' => $request->keterangan,
                'uraian' => $request->keterangan,
                'nilaimurni' => $request->nilai,
                'total' => $request->nilai,
                'updated_user' => auth()->id(),
                'updateddate' => $now->format('Y-m-d'),
                'updatedtime' => $now->format('H:i:s'),
                'update_at' => $now,
            ]);

        abort_if($affected === 0, 404, 'Data tidak ditemukan atau sudah tidak aktif.');

        return redirect()
            ->route('pembiayaan.penerimaan.rincian', $id_skpd)
            ->with('success', 'Data pembiayaan penerimaan berhasil diperbarui.');
    }

    // ────────────────────────────────────────────────────────────────
    //  DESTROY & BULK DELETE
    // ────────────────────────────────────────────────────────────────

    public function destroy($id_skpd, $id)
    {
        try {
            $affected = DB::table('data_pembiayaan')
                ->where('id', $id)
                ->where('id_skpd', $id_skpd)
                ->where('type', 'penerimaan')
                ->update(['active' => 0, 'update_at' => now()]);

            abort_if($affected === 0, 404, 'Data tidak ditemukan.');

            return response()->json([
                'success' => true,
                'message' => 'Data pembiayaan penerimaan berhasil dihapus.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function bulkDelete(Request $request, $id_skpd)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);

        try {
            $count = count($request->ids);

            DB::table('data_pembiayaan')
                ->whereIn('id', $request->ids)
                ->where('id_skpd', $id_skpd)
                ->where('type', 'penerimaan')
                ->update(['active' => 0, 'update_at' => now()]);

            return response()->json([
                'success' => true,
                'message' => $count.' data pembiayaan penerimaan berhasil dihapus.',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
