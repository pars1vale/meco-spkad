<?php

namespace App\Repositories\Referensi;

use Illuminate\Support\Facades\DB;

/**
 * Repository untuk Akun Rekening
 */
class AkunRepository
{
    public function findById(int $id)
    {
        return DB::table('akun')
            ->where('id', $id)
            ->first();
    }

    public function getByJenisBelanja(string $field, int $tahunAnggaran)
    {
        return DB::table('akun')
            ->where('tahun_anggaran', $tahunAnggaran)
            ->where('active', 1)
            ->where($field, 1)
            ->where('set_input', 1)
            ->orderBy('kode_akun')
            ->get(['id', 'kode_akun', 'nama_akun', 'level']);
    }
}
