<?php

namespace App\Services\Rkpd\DokumenAnggaran;

use App\Repositories\Rkpd\DokumenAnggaran\RkaSkpdRepository;
use App\Support\SkpdResolver;
use Illuminate\Support\Str;

class RkaSkpdService
{
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

    public function __construct(protected RkaSkpdRepository $repo) {}

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

    protected function groupLv6UnderLv2(\Illuminate\Support\Collection $lv2Labels, \Illuminate\Support\Collection $lv6Rows): array
    {
        $groups = [];

        foreach ($lv2Labels as $lv2) {
            $children = $lv6Rows->filter(
                fn($row) => Str::startsWith($row->kode_level, $lv2->kode_level)
            )->values();

            $sumChildren = $children->sum('jumlah');

            if ($sumChildren == 0 || $children->isEmpty()) {
                continue;
            }

            $groups[] = [
                'kode' => $lv2->kode_level,
                'kodeParts' => explode('.', $lv2->kode_level),
                'nama' => $lv2->nama_akun,
                'jumlah' => $sumChildren,
                'children' => $children->map(function ($child) {
                    $child->kodeParts = explode('.', $child->kode_level);
                    return $child;
                }),
            ];
        }

        return $groups;
    }

    protected function buildSection(string $sectionCode, \Illuminate\Support\Collection $lv6Rows): array
    {
        $lv1 = $this->repo->getLv1Labels($sectionCode)->first();
        $lv2Labels = $this->repo->getLv2Labels($sectionCode);
        $groups = $this->groupLv6UnderLv2($lv2Labels, $lv6Rows);
        $total = collect($groups)->sum('jumlah');

        return [
            'kode' => $sectionCode,
            'label' => $lv1->nama_akun ?? '-',
            'groups' => $groups,
            'total' => $total,
            'kosong' => $lv6Rows->isEmpty(),
        ];
    }

    public function buildCetakData(int $idSkpdDipilih, int $tahunAnggaran, ?string $tanggalTtd = null, ?string $namaTtd = null, ?string $nipTtd = null): array
    {
        $idSkpd = SkpdResolver::resolveIndukId($idSkpdDipilih, $tahunAnggaran);
        $dataUnit = $this->repo->getDataUnitById($idSkpd, $tahunAnggaran);
        $ttdDefault = $this->getTtdDefault($idSkpd, $tahunAnggaran);
        $namaTtdFinal = $ttdDefault['hasDefault'] ? $ttdDefault['nama'] : ($namaTtd ?: '-');
        $nipTtdFinal = $ttdDefault['hasDefault'] ? $ttdDefault['nip'] : ($nipTtd ?: '-');
        $pendapatan = $this->buildSection('4', $this->repo->getPendapatanLv6($idSkpd, $tahunAnggaran));
        $belanja = $this->buildSection('5', $this->repo->getBelanjaLv6($idSkpd, $tahunAnggaran));
        $pembiayaan = $this->buildSection('6', $this->repo->getPembiayaanLv6($idSkpd, $tahunAnggaran));
        $semuaKosong = $pendapatan['kosong'] && $belanja['kosong'] && $pembiayaan['kosong'];
        $tampilkanSurplusDefisit = ! $pendapatan['kosong'] && ! $belanja['kosong'];
        $surplusDefisit = $pendapatan['total'] - $belanja['total'];
        $penerimaanGroup = collect($pembiayaan['groups'])->firstWhere('kode', '6.1');
        $pengeluaranGroup = collect($pembiayaan['groups'])->firstWhere('kode', '6.2');
        $totalPenerimaan = $penerimaanGroup['jumlah'] ?? 0;
        $totalPengeluaran = $pengeluaranGroup['jumlah'] ?? 0;
        $pembiayaanNetto = $totalPenerimaan - $totalPengeluaran;

        return [
            'organisasi' => trim(($dataUnit->kode_skpd ?? '') . ' ' . ($dataUnit->nama_skpd ?? '')),
            'namaUnit' => $dataUnit->nama_skpd ?? '-',
            'tanggalTtd' => $this->formatTanggalIndo($tanggalTtd),
            'namaTtd' => $namaTtdFinal,
            'nipTtd' => $nipTtdFinal,
            'pendapatan' => $pendapatan,
            'belanja' => $belanja,
            'pembiayaan' => $pembiayaan,
            'totalPengeluaranPembiayaan' => $totalPengeluaran,
            'surplusDefisit' => $surplusDefisit,
            'pembiayaanNetto' => $pembiayaanNetto,
            'tampilkanSurplusDefisit' => $tampilkanSurplusDefisit,
            'tampilkanPembiayaanTotal' => ! $pembiayaan['kosong'],
            'semuaKosong' => $semuaKosong,
        ];
    }

    public function formatRupiah($value): string
    {
        $abs = number_format(abs($value), 2, ',', '.');
        $formatted = 'Rp. ' . $abs;

        return $value < 0 ? "({$formatted})" : $formatted;
    }
}
