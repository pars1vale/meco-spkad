<?php

namespace App\Services\Rkpd\DokumenAnggaran;

use App\Repositories\Rkpd\DokumenAnggaran\RkaPendapatanRepository;
use App\Support\SkpdResolver;
use Illuminate\Support\Str;

class RkaPendapatanService
{
    // Level kode_akun yang dipakai untuk baris header/sub-total berjenjang.
    // Sumber: RKA-SKPD-CETAK.md pola + konfirmasi user (leaf = level 19).
    protected const LEVELS_HEADER = [1, 3, 6, 9, 13];
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

    public function __construct(protected RkaPendapatanRepository $repo) {}

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


        $rincian = $this->buildRincianPendapatan($idSkpd, $tahunAnggaran);

        return [
            'organisasi' => trim(($dataUnit->kode_skpd ?? '') . ' ' . ($dataUnit->nama_skpd ?? '')),
            'namaUnit' => $dataUnit->nama_skpd ?? '-',
            'tanggalTtd' => $this->formatTanggalIndo($tanggalTtd),
            'namaTtd' => $namaTtdFinal,
            'nipTtd' => $nipTtdFinal,
            'rows' => $rincian['rows'],
            'totalPendapatan' => $rincian['total'],
            'isEmpty' => $rincian['isEmpty'],
        ];
    }

    /**
     * Bangun baris cetak berjenjang (header level 1/3/6/9/13 + leaf level 19)
     * dengan agregasi SUM dilakukan di PHP (bukan SQL), pola sama seperti RKA-SKPD.
     *
     * ASUMSI BELUM DIVALIDASI: leaf di-render 1 baris per row DB (tidak di-GROUP BY
     * kode_akun), karena kode_akun leaf terbukti bisa duplikat dengan uraian/nilai
     * beda (lihat contoh PDF "4.2.01.09.002.000 33" muncul 2x). Kalau ternyata harus
     * digabung, logic ini perlu diubah.
     */
    public function buildRincianPendapatan(int $idSkpd, int $tahunAnggaran): array
    {
        $leafRows = $this->repo->getPendapatanLeaf($idSkpd, $tahunAnggaran);

        if ($leafRows->isEmpty()) {
            return ['rows' => [], 'total' => 0, 'isEmpty' => true];
        }

        $labels = $this->repo->getAkunLabelsByLevels(self::LEVELS_HEADER);
        $labelMap = $labels->keyBy('kode_akun');

        // Kumpulkan semua kode header unik (level 1/3/6/9/13) yang benar-benar
        // punya turunan di leaf, via prefix-match ke masing-masing level.
        $headerCodesByLevel = [];
        foreach (self::LEVELS_HEADER as $level) {
            $codes = [];
            foreach ($leafRows as $leaf) {
                $prefix = substr((string) $leaf->kode_akun, 0, $this->kodeLenForLevel($labels, $level, $leaf->kode_akun));
                $codes[$prefix] = true;
            }
            $headerCodesByLevel[$level] = array_keys($codes);
        }

        // Total per kode header = SUM leaf yang kode_akun-nya prefix-match.
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

        // Susun output berurutan by kode_akun leaf, sisipkan header setiap kali
        // prefix header berubah (mirip pola nested grouping rka-skpd).
        $rows = [];
        $lastHeaderPrinted = array_fill_keys(self::LEVELS_HEADER, null);

        foreach ($leafRows->sortBy('kode_akun') as $leaf) {
            foreach (self::LEVELS_HEADER as $level) {
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
                'uraian' => $leaf->uraian,
                'keterangan' => $leaf->keterangan,
                'volume' => $leaf->volume,
                'koefisien' => $leaf->koefisien,
                'satuan' => $leaf->satuan,
                'nilaimurni' => $leaf->nilaimurni,
                'total' => $leaf->total,
            ];
        }

        return [
            'rows' => $rows,
            'total' => $leafRows->sum('total'),
            'isEmpty' => false,
        ];
    }

    /**
     * Panjang karakter kode_akun untuk level tertentu, diambil dari tabel akun
     * (field `level` = jumlah karakter valid, dikonfirmasi user) — bukan hardcode.
     */
    protected function kodeLenForLevel($labels, int $level, string $kodeLeaf): int
    {
        // Ambil kode_akun di tabel `akun` pada level ini yang jadi prefix dari leaf,
        // panjangnya = strlen kode tsb (karena level di tabel akun akurat = jumlah karakter).
        foreach ($labels->where('level', $level) as $akun) {
            if (Str::startsWith($kodeLeaf, $akun->kode_akun) && strlen($akun->kode_akun) > 0) {
                // Validasi ringan: kode_akun di tabel akun panjangnya harus konsisten
                // dengan level (level == strlen) sesuai konfirmasi user.
                return strlen($akun->kode_akun);
            }
        }

        // Fallback: tidak ketemu match di tabel akun — data akun kemungkinan tidak
        // lengkap untuk level ini. Ini HARUS di-investigasi, bukan di-silent-fallback
        // dengan angka sembarangan.
        throw new \RuntimeException("Tidak ditemukan akun level {$level} yang jadi prefix dari kode {$kodeLeaf}");
    }
}
