<?php

namespace App\Services\Rkpd;

use App\Repositories\Referensi\AkunRepository;
use App\Repositories\Rkpd\RenjaRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RenjaService
{
    protected $renjaRepo;

    protected $akunRepo;

    protected const JENIS_BELANJA_MAPPING = [
        'BTL-GAJI' => 'is_gaji_asn',
        'BARJAS-MODAL' => 'is_barjas',
        'BUNGA' => 'is_bunga',
        'SUBSIDI' => 'is_subsidi',
        'HIBAH-BRG' => 'is_hibah_brg',
        'HIBAH' => 'is_hibah_uang',
        'BANSOS-BRG' => 'is_sosial_brg',
        'BANSOS' => 'is_sosial_uang',
        'BAGI-HASIL' => 'is_bagi_hasil',
        'BANKEU' => 'is_bankeu_umum',
        'BANKEU-KHUSUS' => 'is_bankeu_khusus',
        'BTT' => 'is_btt',
        'BOS' => 'is_bos',
        'BLUD' => 'is_bl',
        'TANAH' => 'is_modal_tanah',
    ];

    public function __construct(
        RenjaRepository $renjaRepo,
        AkunRepository $akunRepo
    ) {
        $this->renjaRepo = $renjaRepo;
        $this->akunRepo = $akunRepo;
    }

    public function getIndexData(): array
    {
        $tahunAnggaran = $this->getTahunAnggaran();

        return [
            'data' => $this->renjaRepo->getAll(),
            'data_unit' => $this->renjaRepo->getDataUnit($tahunAnggaran),
            'sumberdana' => $this->renjaRepo->getSumberDana(),
            'daerah' => $this->renjaRepo->getDataDaerah(),
            'kec' => $this->renjaRepo->getDataKecamatan(),
            'kel' => $this->renjaRepo->getDataKelurahan(),
            'bln' => $this->renjaRepo->getDataBulan(),
        ];
    }

    public function getSubKegiatanBySkpd(int $idSkpd, int $tahunAnggaran): array
    {
        $subKegiatan = $this->renjaRepo->getSubKegiatanWithIndikatorBySkpd($idSkpd, $tahunAnggaran);

        return [
            'data' => $subKegiatan,
            'count' => $subKegiatan->count(),
        ];
    }

    public function getEditData(int $idSubBl): array
    {
        $subKegiatan = $this->renjaRepo->getSubKegiatanForEdit($idSubBl);

        if (! $subKegiatan) {
            return ['subKegiatan' => null];
        }

        $tahunAnggaran = $this->getTahunAnggaran();
        $sumberDana = $this->renjaRepo->getSumberDanaForEdit($subKegiatan->id, $subKegiatan->kode_sbl);
        $indikator = $this->renjaRepo->getIndikatorForEdit($subKegiatan->id, $subKegiatan->kode_sbl);
        $dataUnit = $this->renjaRepo->getDataUnitById($subKegiatan->id_skpd, $tahunAnggaran);
        $allSumberDana = $this->renjaRepo->getSumberDana();
        $dataBulan = $this->renjaRepo->getDataBulan();
        $dataKecamatan = $this->renjaRepo->getDataKecamatan();
        $dataKelurahan = $this->renjaRepo->getDataKelurahan();
        $dataDaerah = $this->renjaRepo->getDataDaerah();

        return [
            'subKegiatan' => $subKegiatan,
            'sumberDana' => $sumberDana,
            'indikator' => $indikator,
            'dataUnit' => $dataUnit,
            'data_unit' => $this->renjaRepo->getDataUnit($tahunAnggaran),
            'allSumberDana' => $allSumberDana,
            'sumberdana' => $allSumberDana,
            'bln' => $dataBulan,
            'kec' => $dataKecamatan,
            'kel' => $dataKelurahan,
            'daerah' => $dataDaerah,
        ];
    }

    public function createRenja(array $data): array
    {
        DB::beginTransaction();

        try {
            $tahunAnggaran = $this->getTahunAnggaran();
            $dataUnit = $this->renjaRepo->getDataUnitById($data['id_skpd'], $tahunAnggaran);
            if (! $dataUnit) {
                throw new \Exception('Data SKPD tidak ditemukan');
            }

            $subKegiatanData = $this->renjaRepo->getSubKegiatanDetailById(
                $data['id_skpd'],
                $data['id_sub_kegiatan'],
                $tahunAnggaran
            );
            if (! $subKegiatanData) {
                throw new \Exception('Data sub kegiatan tidak ditemukan');
            }

            $totalPagu = array_sum(array_column($data['sumber_dana'], 'pagu'));
            $codes = $this->generateKodeBelanja($subKegiatanData, $dataUnit);

            $idSubKegBl = $this->renjaRepo->createSubKegiatanBelanja([
                'sub_kegiatan' => $subKegiatanData,
                'data_unit' => $dataUnit,
                'codes' => $codes,
                'total_pagu' => $totalPagu,
                'waktu_awal' => $data['waktu_awal'] ?? null,
                'waktu_akhir' => $data['waktu_akhir'] ?? null,
                'pagu_n_depan' => $data['pagu_n_depan'] ?? 0,
                'tahun_anggaran' => $tahunAnggaran,
            ]);

            $this->insertSumberDana($data['sumber_dana'], $idSubKegBl, $codes['kode_sbl']);

            if (! empty($data['indikator'])) {
                $this->insertIndikator($data['indikator'], $idSubKegBl, $codes['kode_sbl']);
            }

            DB::commit();

            $message = 'Sub Kegiatan berhasil ditambahkan dengan '.count($data['sumber_dana']).' sumber dana';
            if (! empty($data['indikator'])) {
                $message .= ' dan '.count($data['indikator']).' indikator';
            }

            return [
                'success' => true,
                'message' => $message,
                'id' => $idSubKegBl,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating RENJA: '.$e->getMessage());
            throw $e;
        }
    }

    public function updateRenja(int $idSubBl, array $data): array
    {
        DB::beginTransaction();

        try {
            $subKegiatanBelanja = $this->renjaRepo->getSubKegiatanForEdit($idSubBl);
            if (! $subKegiatanBelanja) {
                throw new \Exception('Sub kegiatan tidak ditemukan');
            }

            $totalPagu = array_sum(array_column($data['sumber_dana'], 'pagu'));

            $this->renjaRepo->updateSubKegiatanBelanja($subKegiatanBelanja->id, [
                'pagu' => $totalPagu,
                'waktu_awal' => $data['waktu_awal'] ?? null,
                'waktu_akhir' => $data['waktu_akhir'] ?? null,
                'pagu_n_depan' => $data['pagu_n_depan'] ?? 0,
                'nama_lokasi' => (string) 604,
            ]);

            $this->replaceSumberDana($data['sumber_dana'], $subKegiatanBelanja);

            if (isset($data['indikator'])) {
                $this->replaceIndikator($data['indikator'], $subKegiatanBelanja);
            }

            DB::commit();

            return [
                'success' => true,
                'message' => 'Sub kegiatan berhasil diupdate',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating RENJA: '.$e->getMessage());
            throw $e;
        }
    }

    public function deleteRenja(int $idSubBl): array
    {
        DB::beginTransaction();

        try {
            $subKegiatanBelanja = $this->renjaRepo->getSubKegiatanForEdit($idSubBl);
            if (! $subKegiatanBelanja) {
                throw new \Exception('Sub kegiatan tidak ditemukan');
            }

            $rincianCount = $this->renjaRepo->countRincianBelanja($subKegiatanBelanja->id);
            if ($rincianCount > 0) {
                throw new \Exception('Tidak dapat menghapus! Sub kegiatan ini sudah memiliki '.$rincianCount.' rincian belanja.');
            }

            $this->renjaRepo->softDeleteSubKegiatan($subKegiatanBelanja->id);
            $this->renjaRepo->softDeleteSumberDana($subKegiatanBelanja->id);
            $this->renjaRepo->softDeleteIndikator($subKegiatanBelanja->id);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Sub kegiatan berhasil dihapus',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting RENJA: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Susun data RENJA 1 SKPD penuh (semua Urusan/Program/Kegiatan/Sub Kegiatan) untuk export PDF,
     * mengikuti struktur laporan RENJA OPD standar (Urusan > Bidang Urusan > Program > Kegiatan > Sub Kegiatan).
     *
     * CATATAN KETERBATASAN DATA: Beberapa kolom di format laporan RENJA OPD standar
     * (Target Akhir Periode Renstra, Realisasi Capaian Renja Tahun Lalu, Prakiraan Capaian
     * Tahun Berjalan, Prioritas Nasional, Prioritas Daerah, Kelompok Sasaran, Target Prakiraan
     * Maju Tahun Depan) TIDAK memiliki kolom yang jelas pemetaannya di skema data_sub_keg_bl
     * saat ini. Field-field tersebut akan ditampilkan sebagai '-' dan ditandai dengan komentar
     * HTML `<!-- UNMAPPED: nama_field -->` pada hasil render (lihat helper flaggedValue()),
     * supaya mudah dicari & dipetakan ulang begitu kolom sumber datanya sudah jelas.
     */
    public function getExportPdfData(int $idSkpd, int $tahunAnggaran): array
    {
        $skpd = $this->renjaRepo->getDataUnitById($idSkpd, $tahunAnggaran);

        if (! $skpd) {
            return ['skpd' => null];
        }

        $rows = $this->renjaRepo->getRenjaRowsForExport($idSkpd, $tahunAnggaran);

        if ($rows->isEmpty()) {
            return [
                'skpd' => $skpd,
                'tahunAnggaran' => $tahunAnggaran,
                'grouped' => [],
                'grandTotalPagu' => 0,
                'grandTotalPaguMurni' => 0,
            ];
        }

        $ids = $rows->pluck('id')->all();
        $sumberDanaMap = $this->renjaRepo->getSumberDanaByIds($ids);
        $indikatorMap = $this->renjaRepo->getIndikatorByIds($ids);

        $grouped = $this->groupRowsForExportPdf($rows, $sumberDanaMap, $indikatorMap);

        return [
            'skpd' => $skpd,
            'tahunAnggaran' => $tahunAnggaran,
            'grouped' => $grouped,
            'grandTotalPagu' => $rows->sum('pagu'),
            'grandTotalPaguMurni' => $rows->sum('pagumurni'),
        ];
    }

    /**
     * Kelompokkan baris flat data_sub_keg_bl menjadi struktur nested:
     * Urusan -> Bidang Urusan -> Program -> Kegiatan -> [Sub Kegiatan...]
     * beserta subtotal pagu di tiap level.
     */
    private function groupRowsForExportPdf($rows, $sumberDanaMap, $indikatorMap): array
    {
        $grouped = [];

        foreach ($rows as $row) {
            $urusanKey = $row->kode_urusan;
            $bidangKey = $row->kode_bidang_urusan;
            $programKey = $row->kode_program;
            $kegiatanKey = $row->kode_giat;

            if (! isset($grouped[$urusanKey])) {
                $grouped[$urusanKey] = [
                    'kode' => $row->kode_urusan,
                    'nama' => $row->nama_urusan,
                    'total_pagu' => 0,
                    'bidang' => [],
                ];
            }

            if (! isset($grouped[$urusanKey]['bidang'][$bidangKey])) {
                $grouped[$urusanKey]['bidang'][$bidangKey] = [
                    'kode' => $row->kode_bidang_urusan,
                    'nama' => $row->nama_bidang_urusan,
                    'total_pagu' => 0,
                    'program' => [],
                ];
            }

            if (! isset($grouped[$urusanKey]['bidang'][$bidangKey]['program'][$programKey])) {
                $grouped[$urusanKey]['bidang'][$bidangKey]['program'][$programKey] = [
                    'kode' => $row->kode_program,
                    'nama' => $row->nama_program,
                    'total_pagu' => 0,
                    'kegiatan' => [],
                ];
            }

            if (! isset($grouped[$urusanKey]['bidang'][$bidangKey]['program'][$programKey]['kegiatan'][$kegiatanKey])) {
                $grouped[$urusanKey]['bidang'][$bidangKey]['program'][$programKey]['kegiatan'][$kegiatanKey] = [
                    'kode' => $row->kode_giat,
                    'nama' => $row->nama_giat,
                    'total_pagu' => 0,
                    'sub_kegiatan' => [],
                ];
            }

            $sumberDanaText = isset($sumberDanaMap[$row->id])
                ? $sumberDanaMap[$row->id]->pluck('namadana')->filter()->unique()->implode(', ')
                : null;

            $indikatorText = isset($indikatorMap[$row->id])
                ? $indikatorMap[$row->id]->map(function ($ind) {
                    return trim(($ind->output_teks ?? '').' - '.($ind->target_output ?? '').' '.($ind->satuan_output ?? ''));
                })->filter()->implode('; ')
                : null;

            $grouped[$urusanKey]['bidang'][$bidangKey]['program'][$programKey]['kegiatan'][$kegiatanKey]['sub_kegiatan'][] = [
                'kode_sub_giat' => $row->kode_sub_giat,
                'nama_sub_giat' => $row->nama_sub_giat,
                'indikator' => $this->flaggedValue($indikatorText, 'indikator_sub_kegiatan'),
                'pagu' => $row->pagu ?? 0,
                'pagu_n_lalu' => $this->flaggedValue($row->pagu_n_lalu ?? null, 'realisasi_capaian_renja_tahun_lalu'),
                'pagu_n_depan' => $this->flaggedValue($row->pagu_n_depan ?? null, 'prakiraan_maju_pagu_tahun_depan'),
                'target_akhir_renstra' => $this->flaggedValue(null, 'target_akhir_periode_renstra'),
                'prakiraan_capaian' => $this->flaggedValue(null, 'prakiraan_capaian_tahun_berjalan'),
                'lokasi' => $this->flaggedValue($row->nama_lokasi ?? null, 'lokasi'),
                'sumber_dana' => $this->flaggedValue($sumberDanaText, 'sumber_dana'),
                'prioritas_nasional' => $this->flaggedValue(null, 'prioritas_nasional'),
                'prioritas_daerah' => $this->flaggedValue(null, 'prioritas_daerah'),
                'kelompok_sasaran' => $this->flaggedValue($row->sasaran ?? null, 'kelompok_sasaran'),
                'target_maju_2027' => $this->flaggedValue(null, 'target_prakiraan_maju_tahun_depan'),
                'pd_penanggung_jawab' => $row->nama_skpd,
                'waktu_awal' => $row->waktu_awal ?? null,
                'waktu_akhir' => $row->waktu_akhir ?? null,
            ];

            $grouped[$urusanKey]['total_pagu'] += ($row->pagu ?? 0);
            $grouped[$urusanKey]['bidang'][$bidangKey]['total_pagu'] += ($row->pagu ?? 0);
            $grouped[$urusanKey]['bidang'][$bidangKey]['program'][$programKey]['total_pagu'] += ($row->pagu ?? 0);
            $grouped[$urusanKey]['bidang'][$bidangKey]['program'][$programKey]['kegiatan'][$kegiatanKey]['total_pagu'] += ($row->pagu ?? 0);
        }

        return $grouped;
    }

    /**
     * Helper flag untuk kolom yang belum ada pemetaan sumber data yang jelas.
     * Mengembalikan '-' (ditampilkan ke user) tapi disisipi komentar HTML sebagai
     * penanda "field ini belum dipetakan" agar mudah di-grep di source PDF nanti.
     * Begitu field_name sudah ada mapping-nya di database, cukup isi $value aslinya
     * dan flag ini otomatis tidak muncul lagi.
     */
    private function flaggedValue($value, string $fieldName): string
    {
        if ($value === null || $value === '') {
            return '-<!-- UNMAPPED: '.$fieldName.' -->';
        }

        return e($value);
    }

    public function getDataTableData(array $params): array
    {
        $searchValue = $params['search']['value'] ?? null;
        $start = $params['start'] ?? 0;
        $length = $params['length'] ?? 10;

        $query = $this->renjaRepo->getDataTableQuery($searchValue);
        $totalRecords = $this->renjaRepo->getTotalRecords();
        $totalFiltered = $this->renjaRepo->getFilteredCount($searchValue);

        $data = $query->skip($start)->take($length)->get();
        $formattedData = $this->formatDataTableRows($data);

        return [
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalFiltered,
            'data' => $formattedData,
        ];
    }

    public function getRincianSubKegiatan(int $id): array
    {
        $subKegiatan = $this->renjaRepo->getSubKegiatanWithUnit($id);

        if (! $subKegiatan) {
            return ['subKegiatan' => null];
        }

        $sumberDana = $this->renjaRepo->getSumberDanaBySubKegiatan($id);
        $indikator = $this->renjaRepo->getIndikatorBySubKegiatan($id);
        $rincianBelanja = $this->renjaRepo->getRincianBelanjaBySubKegiatan($id);
        $totalPerObjek = $this->groupRincianByObjek($rincianBelanja);

        return [
            'subKegiatan' => $subKegiatan,
            'sumberDana' => $sumberDana,
            'indikator' => $indikator,
            'rincianBelanja' => $rincianBelanja,
            'totalPerObjek' => $totalPerObjek,
        ];
    }

<<<<<<< HEAD
     public function getCetakRincianData(int $idSubBl): array
{
    $base = $this->getRincianSubKegiatan($idSubBl);

    if (! $base['subKegiatan']) {
        return ['subKegiatan' => null];
    }

    $subKegiatan = $base['subKegiatan'];
    $rincianBelanja = $base['rincianBelanja'];
    $tahunAnggaran = $subKegiatan->tahun_anggaran ?? date('Y');

    $spmData = $this->renjaRepo->getSpmBySubGiat($subKegiatan->id_sub_giat, (int) $tahunAnggaran);

    return [
        'subKegiatan' => $subKegiatan,
        'sumberDana' => $base['sumberDana'],
        'indikator' => $base['indikator'],
        'kabupaten' => config('app.nama_kabupaten', 'Yahukimo'),
        'tahunAnggaran' => $tahunAnggaran,
        'waktuPelaksanaan' => $this->formatBulanRange($subKegiatan->waktu_awal ?? null, $subKegiatan->waktu_akhir ?? null),
        'spm' => $spmData->pluck('spm_teks')->filter()->unique()->implode(', ') ?: '-',
        'jenisLayanan' => $spmData->pluck('layanan_teks')->filter()->unique()->implode(', ') ?: '-',
        'rincianRows' => $this->buildRincianRows($rincianBelanja, (int) $tahunAnggaran),
        'grandTotal' => $rincianBelanja->sum(function ($item) {
            return $item->total_harga ?? (($item->volume ?? 0) * ($item->harga_satuan ?? 0));
        }),
        'ttd' => [
            'tempat' => config('app.nama_kabupaten', 'Yahukimo'),
            'jabatan' => 'Kepala '.($subKegiatan->nama_skpd ?? ''),
            'nama' => '-<!-- UNMAPPED: pejabat_penandatangan -->',
            'nip' => '-<!-- UNMAPPED: nip_pejabat -->',
        ],
    ];
}

    private function formatBulanRange($bulanAwal, $bulanAkhir): string
    {
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        $awal = $namaBulan[(int) $bulanAwal] ?? '-';
        $akhir = $namaBulan[(int) $bulanAkhir] ?? '-';

        return "{$awal} s.d {$akhir}";
    }

    // private function buildAkunHierarchy($rincianBelanja): array
    // {
    //     $totals = [];

    //     foreach ($rincianBelanja as $item) {
    //         $kode = $item->kode_akun;
    //         $jumlah = ($item->volume ?? 0) * ($item->harga_satuan ?? 0);
    //         $parts = explode('.', $kode);

    //         $prefix = '';
    //         foreach ($parts as $i => $part) {
    //             $prefix = $i === 0 ? $part : $prefix.'.'.$part;
    //             $totals[$prefix] = ($totals[$prefix] ?? 0) + $jumlah;
    //         }
    //     }

    //     $namaAkunMap = $this->akunRepo->getByKodeList(array_keys($totals));

    //     $rows = [];
    //     foreach ($totals as $kode => $jumlah) {
    //         $rows[] = [
    //             'kode' => $kode,
    //             'uraian' => $namaAkunMap[$kode]->nama_akun ?? '-<!-- UNMAPPED: nama_akun_level -->',
    //             'jumlah' => $jumlah,
    //         ];
    //     }

    //     usort($rows, fn ($a, $b) => strnatcmp($a['kode'], $b['kode']));

    //     return $rows;
    // }

   private function buildRincianRows($rincianBelanja, int $tahunAnggaran): array
    {
        $totals = [];
        $itemsByKode = [];

        foreach ($rincianBelanja as $item) {
            $kode = $item->kode_akun;
            $jumlah = $item->total_harga ?? (($item->volume ?? 0) * ($item->harga_satuan ?? 0));

            $parts = explode('.', $kode);
            $prefix = '';
            foreach ($parts as $i => $part) {
                $prefix = $i === 0 ? $part : $prefix.'.'.$part;
                $totals[$prefix] = ($totals[$prefix] ?? 0) + $jumlah;
            }

            $itemsByKode[$kode][] = $item;
        }

        $namaAkunMap = $this->akunRepo->getByKodeList(array_keys($totals), $tahunAnggaran);

        $prefixes = array_keys($totals);
        usort($prefixes, fn ($a, $b) => strnatcmp($a, $b));

        $rows = [];

        foreach ($prefixes as $kode) {
            $rows[] = [
                'type' => 'header',
                'kode' => $kode,
                'uraian' => $namaAkunMap[$kode]->nama_akun ?? '-<!-- UNMAPPED: kode akun '.$kode.' -->',
                'jumlah' => $totals[$kode],
            ];

            // kalau kode ini persis kode_akun yang dipakai di rincian (leaf),
            // langsung susulin baris paket > mintag > item di bawahnya
            if (isset($itemsByKode[$kode])) {
                $rows = array_merge($rows, $this->buildPaketMintagRows($itemsByKode[$kode]));
            }
        }

        return $rows;
    }

    private function buildPaketMintagRows(array $items): array
    {
        $rows = [];
        $byPaket = collect($items)->groupBy(fn ($i) => $i->idsubtitle ?: 'no_paket_'.$i->id);

        foreach ($byPaket as $paketItems) {
            $first = $paketItems->first();
            $totalHarga = fn ($i) => $i->total_harga ?? (($i->volume ?? 0) * ($i->harga_satuan ?? 0));

            $rows[] = [
                'type' => 'paket',
                'label' => $first->subtitle_teks ?: 'Tanpa Paket',
                'sumberDana' => '-<!-- UNMAPPED: sumber_dana_per_paket -->',
                'jumlah' => $paketItems->sum($totalHarga),
            ];

            $byMintag = $paketItems->groupBy(fn ($i) => $i->ket_bl_teks ?: 'Tanpa Kategori');

            foreach ($byMintag as $mintagItems) {
                $rows[] = [
                    'type' => 'mintag',
                    'label' => $mintagItems->first()->ket_bl_teks ?: 'Tanpa Kategori',
                    'jumlah' => $mintagItems->sum($totalHarga),
                ];

                foreach ($mintagItems as $it) {
                    $rows[] = [
                        'type' => 'item',
                        'uraian' => $it->nama_komponen ?? $it->spek,
                        'spesifikasi' => $it->spek_komponen ?? $it->spek ?? '-',
                        'koefisien' => $it->volume,
                        'satuan' => $it->satuan ?? '',
                        'harga' => $it->harga_satuan,
                        'ppn' => 0,
                        'jumlah' => $totalHarga($it),
                    ];
                }
            }
        }

        return $rows;
=======
    public function getRingkasanPaket(int $id): array
    {
        $subKegiatan = $this->renjaRepo->getSubKegiatanWithUnit($id);

        if (! $subKegiatan) {
            return ['subKegiatan' => null];
        }

        $sumberDana = $this->renjaRepo->getSumberDanaBySubKegiatan($id);
        $indikator = $this->renjaRepo->getIndikatorBySubKegiatan($id);
        $rincian = $this->renjaRepo->getRincianBelanjaBySubKegiatan($id);

        // Buat map sumber dana: id_dana → nama_dana (untuk ditempel ke tiap paket)
        $sumberDanaMap = $sumberDana->keyBy('iddana');

        // -------------------------------------------------------
        // Kelompokkan: Paket (subtitle_teks) → Mintag (ket_bl_teks)
        // -------------------------------------------------------
        $paketGroup = [];
        $totalKeseluruhan = 0;

        foreach ($rincian as $item) {
            // Skip baris header paket (marker)
            if ($item->ket_bl_teks === '--- PAKET/KELOMPOK ---') {
                continue;
            }

            $paketKey = $item->idsubtitle ?? 'no_paket_'.$item->id;
            $mintagKey = $item->ket_bl_teks ?: 'Tanpa Kategori';
            $itemTotal = $item->total_harga ?? (($item->volume ?? 0) * ($item->harga_satuan ?? 0));

            // Init paket
            if (! isset($paketGroup[$paketKey])) {
                // Ambil nama sumber dana paket ini
                $namaDana = '-';
                if ($item->id_dana && isset($sumberDanaMap[$item->id_dana])) {
                    $namaDana = $sumberDanaMap[$item->id_dana]->namadana;
                } elseif (! empty($item->nama_dana)) {
                    $namaDana = $item->nama_dana;
                }

                $paketGroup[$paketKey] = [
                    'idsubtitle' => $item->idsubtitle,
                    'title' => $item->subtitle_teks ?? 'Tanpa Paket',
                    'nama_dana' => $namaDana,
                    'is_paket' => $item->is_paket,
                    'jenis_bl' => $item->jenis_bl,
                    'total' => 0,
                    'mintag' => [],
                ];
            }

            // Init mintag
            if (! isset($paketGroup[$paketKey]['mintag'][$mintagKey])) {
                $paketGroup[$paketKey]['mintag'][$mintagKey] = [
                    'title' => $mintagKey,
                    'total' => 0,
                ];
            }

            $paketGroup[$paketKey]['mintag'][$mintagKey]['total'] += $itemTotal;
            $paketGroup[$paketKey]['total'] += $itemTotal;
            $totalKeseluruhan += $itemTotal;
        }

        return [
            'subKegiatan' => $subKegiatan,
            'sumberDana' => $sumberDana,
            'indikator' => $indikator,
            'paketGroup' => $paketGroup,
            'totalKeseluruhan' => $totalKeseluruhan,
        ];
>>>>>>> fa65b713ddad04c9bdb89087939694b5017d9b9e
    }

    public function getAkunByJenisBelanja(string $jenisBelanja, int $tahunAnggaran): array
    {
        if (! isset(self::JENIS_BELANJA_MAPPING[$jenisBelanja])) {
            return [
                'success' => false,
                'message' => 'Jenis belanja tidak valid: '.$jenisBelanja,
            ];
        }

        $field = self::JENIS_BELANJA_MAPPING[$jenisBelanja];
        $akunList = $this->akunRepo->getByJenisBelanja($field, $tahunAnggaran);

        $data = $akunList->map(function ($akun) {
            return [
                'id' => $akun->id,
                'kode_akun' => $akun->kode_akun,
                'nama_akun' => $akun->nama_akun,
                'text' => $akun->kode_akun.' - '.$akun->nama_akun,
                'level' => $akun->level,
            ];
        });

        return [
            'success' => true,
            'data' => $data,
        ];
    }

    public function getDetailAkun(int $akunId): array
    {
        $akun = $this->akunRepo->findById($akunId);

        if (! $akun) {
            return [
                'success' => false,
                'message' => 'Akun tidak ditemukan',
            ];
        }

        return [
            'success' => true,
            'data' => [
                'kode_rekening' => $akun->kode_akun,
                'nama_rekening' => $akun->nama_akun,
            ],
        ];
    }

    public function createPaketBelanja(array $data): array
    {
        DB::beginTransaction();

        try {
            $subKegiatan = $this->renjaRepo->getSubKegiatanBelanjaById($data['id_rinci_sub_bl']);
            if (! $subKegiatan) {
                throw new \Exception('Sub kegiatan tidak ditemukan');
            }

            $akun = $this->akunRepo->findById($data['id_akun']);
            if (! $akun) {
                throw new \Exception('Akun tidak ditemukan');
            }

            $sumberDana = $this->renjaRepo->getFirstSumberDana($data['id_rinci_sub_bl']);

            $idPaket = $this->renjaRepo->createPaketBelanja([
                'sub_kegiatan' => $subKegiatan,
                'akun' => $akun,
                'sumber_dana' => $sumberDana,
                'jenis_bl' => $data['jenis_bl'],
                'tipe_paket' => $data['tipe_paket'],
                'uraian_paket' => $data['uraian_paket'],
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Paket belanja berhasil ditambahkan',
                'data' => ['id' => $idPaket],
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating paket: '.$e->getMessage());
            throw $e;
        }
    }

    public function createRincianBelanja(array $data): array
    {
        DB::beginTransaction();

        try {
            $subKegiatan = $this->renjaRepo->getSubKegiatanBelanjaById($data['id_rinci_sub_bl']);
            if (! $subKegiatan) {
                throw new \Exception('Sub kegiatan tidak ditemukan');
            }

            $akun = $this->akunRepo->findById($data['id_akun']);
            if (! $akun) {
                throw new \Exception('Akun tidak ditemukan');
            }

            $namaPaket = null;
            if ($data['id_paket_belanja']) {
                $paket = $this->renjaRepo->getPaketBelanjaById($data['id_paket_belanja']);
                $namaPaket = $paket->subtitle_teks ?? null;
            }

            $sumberDana = $this->renjaRepo->getFirstSumberDana($data['id_rinci_sub_bl']);

            $volume = floatval($data['volume']);
            $hargaSatuan = floatval($data['harga_satuan']);
            $totalHarga = $volume * $hargaSatuan;

            $idRka = $this->renjaRepo->createRincianBelanja([
                'sub_kegiatan' => $subKegiatan,
                'akun' => $akun,
                'sumber_dana' => $sumberDana,
                'paket' => [
                    'id' => $data['id_paket_belanja'],
                    'nama' => $namaPaket,
                    'tipe' => $data['tipe_paket'],
                ],
                'uraian' => $data['uraian'],
                'volume' => $volume,
                'satuan' => $data['satuan'],
                'harga_satuan' => $hargaSatuan,
                'total_harga' => $totalHarga,
                'jenis_bl' => $data['jenis_bl'],
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Rincian belanja berhasil ditambahkan',
                'data' => [
                    'id' => $idRka,
                    'total' => $totalHarga,
                ],
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating rincian: '.$e->getMessage());
            throw $e;
        }
    }

    private function getTahunAnggaran(): int
    {
        $tahunAnggaran = session('tahun_anggaran');

        if (is_numeric($tahunAnggaran) && (int) $tahunAnggaran > 0) {
            return (int) $tahunAnggaran;
        }

        return (int) date('Y');
    }

    private function generateKodeBelanja($subKegiatan, $dataUnit): array
    {
        $idSubSkpd = $dataUnit->id_setup_unit ?? 0;

        return [
            'id_unik_sub_bl' => uniqid('subbl_', true),
            'kode_bl' => "{$subKegiatan->id_skpd}.{$idSubSkpd}.{$subKegiatan->id_program}.{$subKegiatan->id_kegiatan}",
            'kode_sbl' => "{$subKegiatan->id_skpd}.{$idSubSkpd}.{$subKegiatan->id_program}.{$subKegiatan->id_kegiatan}.{$subKegiatan->id_sub_kegiatan}",
        ];
    }

    private function insertSumberDana(array $sumberDanaList, int $idSubKegBl, string $kodeSbl): void
    {
        foreach ($sumberDanaList as $dana) {
            $this->renjaRepo->createDanaSubKegiatan([
                'id_sumber_dana' => $dana['id_sumber_dana'],
                'pagu' => $dana['pagu'],
                'idsubbl' => $idSubKegBl,
                'kode_sbl' => $kodeSbl,
            ]);
        }
    }

    private function insertIndikator(array $indikatorList, int $idSubKegBl, string $kodeSbl): void
    {
        foreach ($indikatorList as $indikator) {
            $targetValue = str_replace('.', '', $indikator['target']);

            $this->renjaRepo->createIndikatorSubKegiatan([
                'output_teks' => $indikator['indikator_text'],
                'target_output' => $targetValue,
                'satuan_output' => $indikator['satuan'],
                'id_output_bl' => $indikator['id_indikator'] ?? 0,
                'idsubbl' => $idSubKegBl,
                'kode_sbl' => $kodeSbl,
            ]);
        }
    }

    private function replaceSumberDana(array $sumberDanaList, $subKegiatanBelanja): void
    {
        $this->renjaRepo->deleteSumberDanaBySubKegiatan($subKegiatanBelanja->id);

        foreach ($sumberDanaList as $dana) {
            $sumberDanaInfo = $this->renjaRepo->getMasterSumberDana($dana['id_sumber_dana']);

            if ($sumberDanaInfo) {
                $paguValue = is_numeric($dana['pagu'])
                    ? $dana['pagu']
                    : str_replace(',', '', $dana['pagu']);

                $this->renjaRepo->insertSumberDana([
                    'nama_dana' => $sumberDanaInfo->nama_dana,
                    'kode_dana' => $sumberDanaInfo->kode_dana,
                    'id_dana' => $sumberDanaInfo->id_dana,
                    'pagu' => floatval($paguValue),
                    'kode_sbl' => $subKegiatanBelanja->kode_sbl,
                    'idsubbl' => $subKegiatanBelanja->id,
                ]);
            }
        }
    }

    private function replaceIndikator(array $indikatorList, $subKegiatanBelanja): void
    {
        $this->renjaRepo->deleteIndikatorBySubKegiatan($subKegiatanBelanja->id);

        foreach ($indikatorList as $indikator) {
            if (! empty($indikator['target'])) {
                $targetValue = str_replace(['.', ','], '', $indikator['target']);

                $this->renjaRepo->insertIndikator([
                    'output_teks' => $indikator['indikator_text'],
                    'target_output' => $targetValue,
                    'satuan_output' => $indikator['satuan'],
                    'id_output_bl' => $indikator['id_indikator'] ?? 0,
                    'idsubbl' => $subKegiatanBelanja->id,
                    'kode_sbl' => $subKegiatanBelanja->kode_sbl,
                ]);
            }
        }
    }

    private function formatDataTableRows($data): array
    {
        $formattedData = [];
        $badgeColors = ['danger', 'primary', 'success', 'warning', 'info'];

        foreach ($data as $row) {
            $jumlahIndikator = $this->renjaRepo->countIndikator($row->kode_sbl);
            $jumlahUsulan = $row->jumlah_sumber_dana ?? 0;

            $randomColor = $badgeColors[array_rand($badgeColors)];
            $usulanBadge = $jumlahUsulan > 0
                ? '<span class="badge badge-'.$randomColor.' ms-2">'.$jumlahUsulan.' Usulan Pokir</span>'
                : '';

            $checkIcon = $jumlahIndikator > 0
                ? '<i class="ki-outline ki-check-circle fs-2 text-success ms-2"></i>'
                : '';

            $formattedData[] = [
                'DT_RowIndex' => count($formattedData) + 1,
                'checkbox' => '',
                'group_skpd' => $row->kode_skpd.' '.$row->nama_skpd,
                'group_urusan' => $row->kode_urusan.' '.$row->nama_urusan,
                'group_program' => $row->kode_program.' '.$row->nama_program,
                'group_kegiatan' => $row->kode_giat.' '.$row->nama_giat,
                'sub_kegiatan' => $this->renderSubKegiatanColumn($row, $checkIcon, $usulanBadge),
                'status_sub_kegiatan' => '<span class="badge badge-light-danger">DIKUNCI</span>',
                'status_rincian' => '<span class="badge badge-light-danger">DIKUNCI</span>',
                'sebelum_perubahan' => number_format($row->pagumurni ?? 0, 2, '.', ','),
                'pagu_validasi' => number_format($row->pagu ?? 0, 2, '.', ','),
                'total_rincian' => number_format($row->pagu ?? 0, 3, '.', ','),
                'total_realisasi' => '0.00',
                'persentase' => '0.00 %',
                'aksi' => $this->renderActionButtons($row->id_sub_bl ?? $row->id ?? 0),
            ];
        }

        return $formattedData;
    }

    private function renderSubKegiatanColumn($row, string $checkIcon, string $usulanBadge): string
    {
        return '
            <div class="d-flex align-items-center">
                <button class="btn btn-sm btn-icon btn-light me-3 btn-collapse">
                    <i class="ki-outline ki-minus fs-3"></i>
                </button>
                <div>
                    <a href="#" class="text-primary fw-bold">'.$row->kode_sub_giat.' '.$row->nama_sub_giat.'</a>
                    '.$checkIcon.'
                    '.$usulanBadge.'
                </div>
            </div>
        ';
    }

    private function renderActionButtons(?int $id): string
    {
        if (! $id || $id === 0) {
            return '<span class="badge badge-light-secondary">No Action</span>';
        }

        return '
            <div class="btn-group">
                <button class="btn btn-sm btn-icon btn-light btn-active-light-primary" type="button" data-bs-toggle="dropdown">
                    <i class="ki-outline ki-category fs-3"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li class="px-3 py-2">
                        <div class="text-gray-800 fw-bold fs-6">Pilih Aksi</div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    
                    <li>
                        <a class="dropdown-item" href="/rkpd/renja/'.$id.'/edit">
                            <i class="ki-outline ki-pencil fs-5 me-2 text-warning"></i>
                            Edit Sub Kegiatan
                        </a>
                    </li>
                    
                    <li>
                        <a class="dropdown-item btn-lihat-rincian" href="#" data-id="'.$id.'">
                            <i class="ki-outline ki-document fs-5 me-2 text-info"></i>
                            Lihat Rincian Belanja
                        </a>
                    </li>

<<<<<<< HEAD
                     <li>
                        <a class="dropdown-item" href="'.route('renja.cetak-rincian', $id).'" target="_blank">
                            <i class="ki-outline ki-printer fs-5 me-2 text-primary"></i>
                            Cetak Rincian Belanja
=======
                    <li>
                        <a class="dropdown-item" href="/rkpd/renja/'.$id.'/ringkasan-paket">
                            <i class="ki-outline ki-folder fs-5 me-2 text-primary"></i>
                            RKA Paket / Kelompok
>>>>>>> fa65b713ddad04c9bdb89087939694b5017d9b9e
                        </a>
                    </li>
                    
                    <li><hr class="dropdown-divider"></li>
                    
                    <li>
                        <a class="dropdown-item btn-delete-renja" href="#" data-id="'.$id.'">
                            <i class="ki-outline ki-trash fs-5 me-2 text-danger"></i>
                            Hapus Sub Kegiatan
                        </a>
                    </li>
                </ul>
            </div>
        ';
    }

    private function groupRincianByObjek($rincianBelanja)
    {
        return $rincianBelanja
            ->groupBy('kode_akun')
            ->map(function ($items, $kodeAkun) {
                $firstItem = $items->first();

                $rincianDetail = $items->filter(function ($item) {
                    return $item->idsubtitle !== null ||
                        ($item->subtitle_teks === null || $item->subtitle_teks === '');
                });

                return [
                    'kode_rekening' => $kodeAkun,
                    'nama_rekening' => $firstItem->nama_akun,
                    'total' => $rincianDetail->sum(function ($item) {
                        return ($item->volume ?? 0) * ($item->harga_satuan ?? 0);
                    }),
                    'items' => $items->map(function ($item) {
                        $item->uraian = $item->ket_bl_teks ?? $item->spek;

                        return $item;
                    }),
                ];
            });
    }
}
