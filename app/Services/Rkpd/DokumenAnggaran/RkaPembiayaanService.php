<?php

namespace App\Services\Rkpd\DokumenAnggaran;

use App\Repositories\Rkpd\DokumenAnggaran\RkaPembiayaanRepository;
use App\Support\SkpdResolver;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class RkaPembiayaanService
{
    protected const LEVEL_GRAND_TOTAL = 1;
    protected const LEVELS_GROUP_HEADER = [3, 6, 9, 13];
    protected const LEVEL_LEAF = 19;
    protected const BULAN_INDO = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    public function __construct(protected RkaPembiayaanRepository $repo) {}

    public function getAllSkpdForList(int $tahunAnggaran)
    {
        return $this->repo->getAllSkpdForList($tahunAnggaran);
    }

    public function getTtdDefault(int $idSkpdDipilih, int $tahunAnggaran): array
    {
        $dataUnit = $this->repo->getDataUnitById($idSkpdDipilih, $tahunAnggaran);
        $namaTtdDefault = trim((string) ($dataUnit->namakepala ?? ''));
        $nipTtdDefault = trim((string) ($dataUnit->nipkepala ?? ''));

        return [
            'hasDefault' => $namaTtdDefault !== '' && $nipTtdDefault !== '',
            'nama' => $namaTtdDefault,
            'nip' => $nipTtdDefault,
        ];
    }

    public function formatTanggalIndo(?string $tanggalTtd): string
    {
        if (! $tanggalTtd || ! preg_match('/^(\d{2})-(\d{2})-(\d{4})$/', $tanggalTtd, $m)) {
            return (string) $tanggalTtd;
        }

        [$full, $tgl, $bulan, $tahun] = $m;
        $namaBulan = self::BULAN_INDO[(int) $bulan] ?? $bulan;

        return "{$tgl} {$namaBulan} {$tahun}";
    }

    public function formatRupiah($value): string
    {
        $abs = number_format(abs($value), 2, ',', '.');
        $formatted = 'Rp. ' . $abs;

        return $value < 0 ? "({$formatted})" : $formatted;
    }

    public function buildCetakData(int $idSkpdDipilih, int $tahunAnggaran, ?string $tanggalTtd = null, ?string $namaTtd = null, ?string $nipTtd = null): array
    {
        $idSkpd = SkpdResolver::resolveIndukId($idSkpdDipilih, $tahunAnggaran);
        $dataUnit = $this->repo->getDataUnitById($idSkpd, $tahunAnggaran);
        $ttdDefault = $this->getTtdDefault($idSkpd, $tahunAnggaran);
        $namaTtdFinal = $ttdDefault['hasDefault'] ? $ttdDefault['nama'] : ($namaTtd ?: '-');
        $nipTtdFinal = $ttdDefault['hasDefault'] ? $ttdDefault['nip'] : ($nipTtd ?: '-');

        // Label header (grand-total level 1 + group level 3/6/9/13) + leaf diambil
        // sekali, dipakai untuk grand-total gabungan maupun kedua grup.
        $labels = $this->repo->getAkunLabelsByLevels([
            self::LEVEL_GRAND_TOTAL,
            ...self::LEVELS_GROUP_HEADER,
            self::LEVEL_LEAF,
        ]);

        $leafPenerimaan = $this->repo->getPenerimaanPembiayaanLeaf($idSkpd, $tahunAnggaran);
        $leafPengeluaran = $this->repo->getPengeluaranPembiayaanLeaf($idSkpd, $tahunAnggaran);
        $leafGabungan = $leafPenerimaan->concat($leafPengeluaran);

        // Baris "6" (level 1) dihitung SEKALI dari leaf gabungan (penerimaan+pengeluaran),
        // BUKAN dari masing-masing grup — kalau dihitung per-grup, baris level-1 akan
        // muncul dobel dan nilainya cuma sebagian (bukan total sesungguhnya), menyimpang
        // dari mockup yang cuma punya satu baris "6" gabungan di paling atas.
        $grandTotalRow = null;
        if ($leafGabungan->isNotEmpty()) {
            $labelMap = $labels->keyBy('kode_akun');
            $panjang = $this->kodeLenForLevel($labels, self::LEVEL_GRAND_TOTAL, $leafGabungan->first()->kode_akun);
            $kodeGrandTotal = substr((string) $leafGabungan->first()->kode_akun, 0, $panjang);
            $grandTotalRow = [
                'type' => 'header',
                'level' => self::LEVEL_GRAND_TOTAL,
                'kode' => $kodeGrandTotal,
                'nama' => $labelMap[$kodeGrandTotal]->nama_akun ?? '',
                'jumlah' => $leafGabungan->sum('total'),
            ];
        }

        $penerimaan = $this->buildRincianDariLeaf($leafPenerimaan, $labels);
        $pengeluaran = $this->buildRincianDariLeaf($leafPengeluaran, $labels);

        $totalPenerimaan = $penerimaan['total'];
        $totalPengeluaran = $pengeluaran['total'];
        $netto = $totalPenerimaan - $totalPengeluaran;

        return [
            'organisasi' => trim(($dataUnit->kode_skpd ?? '') . ' ' . ($dataUnit->nama_skpd ?? '')),
            'namaUnit' => $dataUnit->nama_skpd ?? '-',
            'tanggalTtd' => $this->formatTanggalIndo($tanggalTtd),
            'namaTtd' => $namaTtdFinal,
            'nipTtd' => $nipTtdFinal,
            'grandTotalRow' => $grandTotalRow,
            'rowsPenerimaan' => $penerimaan['rows'],
            'rowsPengeluaran' => $pengeluaran['rows'],
            'totalPenerimaan' => $totalPenerimaan,
            'totalPengeluaran' => $totalPengeluaran,
            'totalNetto' => $netto,
            'isEmptyPenerimaan' => $penerimaan['isEmpty'],
            'isEmptyPengeluaran' => $pengeluaran['isEmpty'],
        ];
    }

    /**
     * Agregasi PHP-side dari leaf rows (bukan SQL JOIN+GROUP BY) — konsisten dengan
     * pola modul RKA-Pendapatan. Label kode_akun (header maupun leaf) diambil dari
     * akun.nama_akun, BUKAN data_pembiayaan.nama_akun (dikonfirmasi user).
     * Hanya level 3/6/9/13 (LEVELS_GROUP_HEADER) — level 1 grand-total dihitung
     * terpisah di buildCetakData() dari leaf gabungan, bukan di sini.
     *
     * @param Collection $leafRows hasil getPenerimaanPembiayaanLeaf() / getPengeluaranPembiayaanLeaf()
     * @param Collection $labels   hasil getAkunLabelsByLevels([...])
     */
    protected function buildRincianDariLeaf(Collection $leafRows, Collection $labels): array
    {
        if ($leafRows->isEmpty()) {
            return ['rows' => [], 'total' => 0, 'isEmpty' => true];
        }

        $labelMap = $labels->keyBy('kode_akun');

        $headerCodesByLevel = [];
        foreach (self::LEVELS_GROUP_HEADER as $level) {
            $codes = [];
            foreach ($leafRows as $leaf) {
                $panjang = $this->kodeLenForLevel($labels, $level, $leaf->kode_akun);
                $codes[substr((string) $leaf->kode_akun, 0, $panjang)] = true;
            }
            $headerCodesByLevel[$level] = array_keys($codes);
        }

        $sumByHeaderCode = [];
        foreach ($headerCodesByLevel as $level => $codes) {
            foreach ($codes as $kodeHeader) {
                $sumByHeaderCode[$kodeHeader] = 0;
                foreach ($leafRows as $leaf) {
                    if (Str::startsWith((string) $leaf->kode_akun, $kodeHeader)) {
                        $sumByHeaderCode[$kodeHeader] += (float) $leaf->total;
                    }
                }
            }
        }

        $rows = [];
        $lastHeaderPrinted = array_fill_keys(self::LEVELS_GROUP_HEADER, null);

        foreach ($leafRows->sortBy('kode_akun') as $leaf) {
            foreach (self::LEVELS_GROUP_HEADER as $level) {
                $panjang = $this->kodeLenForLevel($labels, $level, $leaf->kode_akun);
                $kodeHeader = substr((string) $leaf->kode_akun, 0, $panjang);

                if ($lastHeaderPrinted[$level] !== $kodeHeader) {
                    $lastHeaderPrinted[$level] = $kodeHeader;
                    $rows[] = [
                        'type' => 'header',
                        'level' => $level,
                        'kode' => $kodeHeader,
                        'nama' => $labelMap[$kodeHeader]->nama_akun ?? '',
                        'jumlah' => $sumByHeaderCode[$kodeHeader] ?? 0,
                    ];
                }
            }

            $rows[] = [
                'type' => 'leaf',
                'kode' => $leaf->kode_akun,
                // nama_akun leaf juga dari tabel akun (exact match kode_akun), bukan data_pembiayaan.nama_akun.
                'nama' => $labelMap[$leaf->kode_akun]->nama_akun ?? '',
                'jumlah' => $leaf->total,
            ];
        }

        return [
            'rows' => $rows,
            'total' => $leafRows->sum('total'),
            'isEmpty' => false,
        ];
    }

    /**
     * Panjang karakter kode_akun untuk level tertentu, dari tabel akun (field `level`
     * = jumlah karakter valid, dikonfirmasi user) — bukan hardcode.
     */
    protected function kodeLenForLevel(Collection $labels, int $level, string $kodeLeaf): int
    {
        foreach ($labels->where('level', $level) as $akun) {
            if (Str::startsWith($kodeLeaf, $akun->kode_akun) && strlen($akun->kode_akun) > 0) {
                return strlen($akun->kode_akun);
            }
        }

        throw new \RuntimeException("Tidak ditemukan akun level {$level} yang jadi prefix dari kode {$kodeLeaf}");
    }
}
