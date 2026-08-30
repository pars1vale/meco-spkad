<?php

namespace App\Repositories\Rkpd\DokumenAnggaran;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RkaPendapatanRepository
{

    public function getPendapatanLv1(int $idSkpd, int $tahunAnggaran): Collection
    {
        return DB::table('data_pendapatan as dp')
            ->join('akun as da', function ($join) {
                $join->on(DB::raw('LEFT(dp.kode_akun, da.level)'), '=', DB::raw('da.kode_akun'))
                    ->where('da.level', '=', 1);
            })
            ->select('da.kode_akun as kode_level', 'da.nama_akun as nama_akun', DB::raw('SUM(dp.total) as jumlah'))
            ->where('dp.id_skpd', $idSkpd)
            ->where('dp.tahun_anggaran', $tahunAnggaran)
            ->groupBy('dp.id_skpd', 'da.kode_akun', 'da.nama_akun', 'da.level')
            ->orderBy('da.kode_akun')
            ->get();
    }

    public function getPendapatanLv3(int $idSkpd, int $tahunAnggaran): Collection
    {
        return DB::table('data_pendapatan as dp')
            ->join('akun as da', function ($join) {
                $join->on(DB::raw('LEFT(dp.kode_akun, da.level)'), '=', DB::raw('da.kode_akun'))
                    ->where('da.level', '=', 3);
            })
            ->select('da.kode_akun as kode_level', 'da.nama_akun as nama_akun', DB::raw('SUM(dp.total) as jumlah'))
            ->where('dp.id_skpd', $idSkpd)
            ->where('dp.tahun_anggaran', $tahunAnggaran)
            ->groupBy('dp.id_skpd', 'da.kode_akun', 'da.nama_akun', 'da.level')
            ->orderBy('da.kode_akun')
            ->get();
    }

    public function getPendapatanLv6(int $idSkpd, int $tahunAnggaran): Collection
    {
        return DB::table('data_pendapatan as dp')
            ->join('akun as da', function ($join) {
                $join->on(DB::raw('LEFT(dp.kode_akun, da.level)'), '=', DB::raw('da.kode_akun'))
                    ->where('da.level', '=', 6);
            })
            ->select('da.kode_akun as kode_level', 'da.nama_akun as nama_akun', DB::raw('SUM(dp.total) as jumlah'))
            ->where('dp.id_skpd', $idSkpd)
            ->where('dp.tahun_anggaran', $tahunAnggaran)
            ->groupBy('dp.id_skpd', 'da.kode_akun', 'da.nama_akun', 'da.level')
            ->orderBy('da.kode_akun')
            ->get();
    }

    public function getPendapatanLv9(int $idSkpd, int $tahunAnggaran): Collection
    {
        return DB::table('data_pendapatan as dp')
            ->join('akun as da', function ($join) {
                $join->on(DB::raw('LEFT(dp.kode_akun, da.level)'), '=', DB::raw('da.kode_akun'))
                    ->where('da.level', '=', 9);
            })
            ->select('da.kode_akun as kode_level', 'da.nama_akun as nama_akun', DB::raw('SUM(dp.total) as jumlah'))
            ->where('dp.id_skpd', $idSkpd)
            ->where('dp.tahun_anggaran', $tahunAnggaran)
            ->groupBy('dp.id_skpd', 'da.kode_akun', 'da.nama_akun', 'da.level')
            ->orderBy('da.kode_akun')
            ->get();
    }

    public function getPendapatanLv13(int $idSkpd, int $tahunAnggaran): Collection
    {
        return DB::table('data_pendapatan as dp')
            ->join('akun as da', function ($join) {
                $join->on(DB::raw('LEFT(dp.kode_akun, da.level)'), '=', DB::raw('da.kode_akun'))
                    ->where('da.level', '=', 13);
            })
            ->select('da.kode_akun as kode_level', 'da.nama_akun as nama_akun', DB::raw('SUM(dp.total) as jumlah'))
            ->where('dp.id_skpd', $idSkpd)
            ->where('dp.tahun_anggaran', $tahunAnggaran)
            ->groupBy('dp.id_skpd', 'da.kode_akun', 'da.nama_akun', 'da.level')
            ->orderBy('da.kode_akun')
            ->get();
    }

    public function getPendapatanLeaf(int $idSkpd, int $tahunAnggaran): Collection
    {
        return DB::table('data_pendapatan')
            ->select('kode_akun', 'uraian', 'keterangan', 'nama_akun', 'volume', 'koefisien', 'satuan', 'nilaimurni', 'total')
            ->where('id_skpd', $idSkpd)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->orderBy('kode_akun')
            ->get();
    }

    public function getAkunLabelsByLevels(array $levels): Collection
    {
        return DB::table('akun')
            ->whereIn('level', $levels)
            ->select('kode_akun', 'nama_akun', 'level')
            ->orderBy('kode_akun')
            ->get();
    }

    public function getDataUnitById(int $idSkpd, int $tahunAnggaran)
    {
        return DB::table('data_unit')
            ->where('id_skpd', $idSkpd)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->first();
    }

    public function getAllSkpdForList(int $tahunAnggaran): Collection
    {
        return DB::table('data_unit')
            ->where('tahun_anggaran', $tahunAnggaran)
            // ->orderBy('nama_skpd')
            ->orderBy('kode_skpd')
            ->get();
    }

    public function getDataUnitWithPendapatan(int $tahunAnggaran)
    {
        return DB::table('data_unit AS du')
            ->join('data_pendapatan AS dp', 'du.id_skpd', '=', 'dp.id_skpd')
            ->where('du.tahun_anggaran', $tahunAnggaran)
            ->select(
                'du.kode_skpd',
                'du.nama_skpd'
            )
            ->distinct()
            ->orderBy('du.kode_skpd', 'asc')
            ->get();
    }
}
