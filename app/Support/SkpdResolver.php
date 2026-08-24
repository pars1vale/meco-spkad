<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;

class SkpdResolver
{
    public static function resolveIndukId(int $idSkpdDipilih, int $tahunAnggaran): int
    {
        $dataUnit = DB::table('data_unit')
            ->where('id_skpd', $idSkpdDipilih)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->first();

        if (! $dataUnit) {
            return $idSkpdDipilih;
        }

        $isSkpdInduk = (int) ($dataUnit->is_skpd ?? 1) === 1;

        if ($isSkpdInduk) {
            return $idSkpdDipilih;
        }

        return (int) $dataUnit->id_unit;
    }
}
