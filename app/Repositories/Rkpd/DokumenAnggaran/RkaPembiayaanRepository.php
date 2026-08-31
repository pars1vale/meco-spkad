<?php

namespace App\Repositories\Rkpd\DokumenAnggaran;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class RkaPembiayaanRepository
{
    public function getPenerimaanPembiayaanLeaf(int $idSkpd, int $tahunAnggaran): Collection
    {
        return DB::table('data_pembiayaan')
            ->select('kode_akun', 'total')
            ->where('id_skpd', $idSkpd)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->where('type', 'penerimaan')
            ->orderBy('kode_akun')
            ->get();
    }

    public function getPengeluaranPembiayaanLeaf(int $idSkpd, int $tahunAnggaran): Collection
    {
        return DB::table('data_pembiayaan')
            ->select('kode_akun', 'total')
            ->where('id_skpd', $idSkpd)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->where('type', 'pengeluaran')
            ->orderBy('kode_akun')
            ->get();
    }

    /**
     * Label kode_akun -> nama_akun dari tabel akun, dibatasi ke level yang diminta
     * (header 1/3/6/9/13 + leaf 19) DAN is_pembiayaan = 1, supaya tidak salah ambil
     * label dari akun modul lain yang kebetulan share prefix/level.
     */
    public function getAkunLabelsByLevels(array $levels): Collection
    {
        return DB::table('akun')
            ->whereIn('level', $levels)
            ->where('is_pembiayaan', 1)
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
            ->orderBy('kode_skpd')
            ->get();
    }
}
