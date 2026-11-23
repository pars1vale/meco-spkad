<?php

namespace App\Repositories\Rkpd\DokumenAnggaran;

use Illuminate\Support\Facades\DB;

class RkaRekapBelanjaRepository
{
    public function getHierarchy(int $idSkpd, int $tahunAnggaran): array
    {
        $sql = "
            SELECT MIN(id_urusan) AS id_ref, kode_urusan AS kode, nama_urusan AS nama, 0 AS level
            FROM data_sub_keg_bl WHERE id_skpd = ? AND tahun_anggaran = ?
            GROUP BY kode_urusan, nama_urusan
            UNION ALL
            SELECT MIN(id_bidang_urusan), kode_bidang_urusan, nama_bidang_urusan, 1
            FROM data_sub_keg_bl WHERE id_skpd = ? AND tahun_anggaran = ?
            GROUP BY kode_bidang_urusan, nama_bidang_urusan
            UNION ALL
            SELECT MIN(id_program), kode_program, nama_program, 2
            FROM data_sub_keg_bl WHERE id_skpd = ? AND tahun_anggaran = ?
            GROUP BY kode_program, nama_program
            UNION ALL
            SELECT MIN(id_giat), kode_giat, nama_giat, 3
            FROM data_sub_keg_bl WHERE id_skpd = ? AND tahun_anggaran = ?
            GROUP BY kode_giat, nama_giat
            UNION ALL
            SELECT MIN(id_sub_giat), kode_sub_giat, nama_sub_giat, 4
            FROM data_sub_keg_bl WHERE id_skpd = ? AND tahun_anggaran = ?
            GROUP BY kode_sub_giat, nama_sub_giat
        ";

        $bindings = [];
        for ($i = 0; $i < 5; $i++) {
            $bindings[] = $idSkpd;
            $bindings[] = $tahunAnggaran;
        }

        $rows = array_map(fn($r) => (array) $r, DB::select($sql, $bindings));
        usort($rows, fn($a, $b) => $this->compareKode($a['kode'], $b['kode']));

        return $rows;
    }

    // RISK: asumsi semua segmen kode 2-digit zero-padded konsisten. Belum diverifikasi.
    private function compareKode(?string $a, ?string $b): int
    {
        $segA = preg_split('/\./', (string) $a);
        $segB = preg_split('/\./', (string) $b);
        $len = max(count($segA), count($segB));

        for ($i = 0; $i < $len; $i++) {
            $partA = $segA[$i] ?? '';
            $partB = $segB[$i] ?? '';
            if ($partA === $partB) continue;
            if (is_numeric($partA) && is_numeric($partB)) {
                return (int) $partA <=> (int) $partB;
            }
            return strcmp($partA, $partB);
        }
        return 0;
    }

    // RISK: mapping jenis_bl belum full-verified. RISK: kode_sbl diasumsikan 1:1 ke sub_kegiatan.
    public function getSubKegiatanTotals(int $idSkpd, int $tahunAnggaran): array
    {
        $sql = "
            SELECT
                dskbl.kode_sub_giat AS kode,
                MIN(dskbl.pagu_n_lalu) AS tahun_min_1,
                MIN(dskbl.pagu_n_depan) AS tahun_plus_1,
                SUM(CASE WHEN drka.jenis_bl IN ('BTL-GAJI','HIBAH','BANSOS','BUNGA') THEN drka.total_harga ELSE 0 END) AS belanja_operasi,
                SUM(CASE WHEN drka.jenis_bl IN ('TANAH','BARJAS-MODAL') THEN drka.total_harga ELSE 0 END) AS belanja_modal,
                SUM(CASE WHEN drka.jenis_bl = 'BTT' THEN drka.total_harga ELSE 0 END) AS belanja_tidak_terduga,
                SUM(CASE WHEN drka.jenis_bl IN ('BANKEU','BANKEU-KHUSUS','BOS') THEN drka.total_harga ELSE 0 END) AS belanja_transfer,
                SUM(CASE WHEN drka.jenis_bl NOT IN ('BTL-GAJI','HIBAH','BANSOS','BUNGA','TANAH','BARJAS-MODAL','BTT','BANKEU','BANKEU-KHUSUS','BOS') THEN drka.total_harga ELSE 0 END) AS belanja_tidak_terklasifikasi,
                SUM(drka.total_harga) AS total_keseluruhan
            FROM data_sub_keg_bl dskbl
            LEFT JOIN data_rka drka ON drka.kode_sbl = dskbl.kode_sbl
            WHERE dskbl.id_skpd = ? AND dskbl.tahun_anggaran = ?
            GROUP BY dskbl.kode_sub_giat
        ";

        $rows = DB::select($sql, [$idSkpd, $tahunAnggaran]);
        $indexed = [];
        foreach ($rows as $r) {
            $r = (array) $r;
            if ((float) $r['belanja_tidak_terklasifikasi'] > 0) {
                logger()->warning('RkaRekapBelanja: jenis_bl tidak terklasifikasi ditemukan', [
                    'kode_sub_giat' => $r['kode'],
                    'nilai' => $r['belanja_tidak_terklasifikasi'],
                ]);
            }
            $indexed[$r['kode']] = $r;
        }

        return $indexed;
    }
}
