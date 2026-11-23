<?php

namespace App\Services\Rkpd\DokumenAnggaran;

use App\Repositories\Rkpd\DokumenAnggaran\RkaRekapBelanjaRepository;
use Illuminate\Support\Facades\DB;

class RkaRekapBelanjaService
{
    public function __construct(protected RkaRekapBelanjaRepository $repository) {}

    public function getAllSkpdForList(int $tahunAnggaran)
    {
        return DB::table('data_unit')
            ->where('tahun_anggaran', $tahunAnggaran)
            ->orderBy('kode_skpd')
            ->get();
    }

    public function getSkpdIdentity(int $idSkpd, int $tahunAnggaran): ?object
    {
        return DB::table('data_sub_keg_bl')
            ->where('id_skpd', $idSkpd)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->select('kode_skpd', 'nama_skpd')
            ->first();
    }

    /**
     * Susun baris cetak: hirarki (level 0-3 tanpa angka) + leaf sub_kegiatan
     * (level 4, digabung dengan totals) — merge di PHP by kode+level eksplisit.
     */
    public function getRekapBelanja(int $idSkpd, int $tahunAnggaran): array
    {
        $hierarchy = $this->repository->getHierarchy($idSkpd, $tahunAnggaran);
        $totals = $this->repository->getSubKegiatanTotals($idSkpd, $tahunAnggaran);

        $rows = [];
        $grandTotal = $this->emptyTotalRow();

        foreach ($hierarchy as $row) {
            if ($row['level'] == 4) {
                $t = $totals[$row['kode']] ?? $this->emptyTotalRow();
                $row = array_merge($row, [
                    'tahun_min_1' => $t['tahun_min_1'] ?? 0,
                    'tahun_plus_1' => $t['tahun_plus_1'] ?? 0,
                    'belanja_operasi' => $t['belanja_operasi'] ?? 0,
                    'belanja_modal' => $t['belanja_modal'] ?? 0,
                    'belanja_tidak_terduga' => $t['belanja_tidak_terduga'] ?? 0,
                    'belanja_transfer' => $t['belanja_transfer'] ?? 0,
                    'total_keseluruhan' => $t['total_keseluruhan'] ?? 0,
                    'sumber_dana' => null, // HOLD
                    'lokasi' => null,      // HOLD
                ]);

                foreach (['tahun_min_1', 'tahun_plus_1', 'belanja_operasi', 'belanja_modal', 'belanja_tidak_terduga', 'belanja_transfer', 'total_keseluruhan'] as $k) {
                    $grandTotal[$k] += $row[$k];
                }
            } else {
                $row = array_merge($row, $this->emptyTotalRow(), ['sumber_dana' => null, 'lokasi' => null]);
            }

            $rows[] = $row;
        }

        return ['rows' => $rows, 'grand_total' => $grandTotal];
    }

    private function emptyTotalRow(): array
    {
        return [
            'tahun_min_1' => 0,
            'tahun_plus_1' => 0,
            'belanja_operasi' => 0,
            'belanja_modal' => 0,
            'belanja_tidak_terduga' => 0,
            'belanja_transfer' => 0,
            'total_keseluruhan' => 0,
        ];
    }

    /**
     * Kolom & filter dikonfirmasi dari RkaPembiayaanService: data_unit.namakepala /
     * data_unit.nipkepala, difilter juga oleh tahun_anggaran (bukan cuma id_skpd).
     */
    public function getTtdDefault(int $idSkpd, int $tahunAnggaran): array
    {
        $dataUnit = DB::table('data_unit')
            ->where('id_skpd', $idSkpd)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->first();

        $namaTtdDefault = trim((string) ($dataUnit->namakepala ?? ''));
        $nipTtdDefault = trim((string) ($dataUnit->nipkepala ?? ''));

        return [
            'hasDefault' => $namaTtdDefault !== '' && $nipTtdDefault !== '',
            'nama' => $namaTtdDefault,
            'nip' => $nipTtdDefault,
        ];
    }
}
