<?php

namespace App\Services\Rkpd;

use App\Repositories\Rkpd\RenjaRepository;
use App\Repositories\Referensi\AkunRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Service untuk Business Logic RENJA
 * 
 * Responsibility:
 * - Validasi business rules
 * - Koordinasi antar repository
 * - Transform data untuk presentation
 * - Transaction management
 */
class RenjaService
{
    protected $renjaRepo;
    protected $akunRepo;

    // Mapping jenis belanja ke field akun
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
        'TANAH' => 'is_modal_tanah'
    ];

    public function __construct(
        RenjaRepository $renjaRepo,
        AkunRepository $akunRepo
    ) {
        $this->renjaRepo = $renjaRepo;
        $this->akunRepo = $akunRepo;
    }

    /**
     * Get data untuk halaman index
     */
    public function getIndexData(): array
    {
        return [
            'data' => $this->renjaRepo->getAll(),
            'data_unit' => $this->renjaRepo->getDataUnit(2025),
            'sumberdana' => $this->renjaRepo->getSumberDana(),
            'daerah' => $this->renjaRepo->getDataDaerah(),
            'kec' => $this->renjaRepo->getDataKecamatan(),
            'kel' => $this->renjaRepo->getDataKelurahan(),
            'bln' => $this->renjaRepo->getDataBulan()
        ];
    }

    /**
     * Get sub kegiatan berdasarkan SKPD
     */
    public function getSubKegiatanBySkpd(int $idSkpd, int $tahunAnggaran): array
    {
        $subKegiatan = $this->renjaRepo->getSubKegiatanWithIndikatorBySkpd($idSkpd, $tahunAnggaran);

        return [
            'data' => $subKegiatan,
            'count' => $subKegiatan->count()
        ];
    }

    /**
     * Create RENJA baru dengan validasi business rules
     */
    public function createRenja(array $data): array
    {
        DB::beginTransaction();

        try {
            // 1. Validasi SKPD exists
            $dataUnit = $this->renjaRepo->getDataUnitById($data['id_skpd'], 2025);
            if (!$dataUnit) {
                throw new \Exception('Data SKPD tidak ditemukan');
            }

            // 2. Validasi Sub Kegiatan exists
            $subKegiatanData = $this->renjaRepo->getSubKegiatanDetailById(
                $data['id_skpd'],
                $data['id_sub_kegiatan'],
                2025
            );
            if (!$subKegiatanData) {
                throw new \Exception('Data sub kegiatan tidak ditemukan');
            }

            // 3. Hitung total pagu
            $totalPagu = array_sum(array_column($data['sumber_dana'], 'pagu'));

            // 4. Generate kode
            $codes = $this->generateKodeBelanja($subKegiatanData, $dataUnit);

            // 5. Insert sub kegiatan
            $idSubKegBl = $this->renjaRepo->createSubKegiatanBelanja([
                'sub_kegiatan' => $subKegiatanData,
                'data_unit' => $dataUnit,
                'codes' => $codes,
                'total_pagu' => $totalPagu,
                'waktu_awal' => $data['waktu_awal'] ?? null,
                'waktu_akhir' => $data['waktu_akhir'] ?? null,
                'pagu_n_depan' => $data['pagu_n_depan'] ?? 0,
                'tahun_anggaran' => 2025
            ]);

            // 6. Insert sumber dana
            $this->insertSumberDana($data['sumber_dana'], $idSubKegBl, $codes['kode_sbl']);

            // 7. Insert indikator (jika ada)
            if (!empty($data['indikator'])) {
                $this->insertIndikator($data['indikator'], $idSubKegBl, $codes['kode_sbl']);
            }

            DB::commit();

            $message = 'Sub Kegiatan berhasil ditambahkan dengan ' . count($data['sumber_dana']) . ' sumber dana';
            if (!empty($data['indikator'])) {
                $message .= ' dan ' . count($data['indikator']) . ' indikator';
            }

            return [
                'success' => true,
                'message' => $message,
                'id' => $idSubKegBl
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating RENJA: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get data untuk DataTables dengan grouping
     */
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
            'data' => $formattedData
        ];
    }

    /**
     * Get detail rincian sub kegiatan
     */
    public function getRincianSubKegiatan(int $id): array
    {
        $subKegiatan = $this->renjaRepo->getSubKegiatanWithUnit($id);

        if (!$subKegiatan) {
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
            'totalPerObjek' => $totalPerObjek
        ];
    }

    /**
     * Get akun berdasarkan jenis belanja
     */
    public function getAkunByJenisBelanja(string $jenisBelanja, int $tahunAnggaran): array
    {
        if (!isset(self::JENIS_BELANJA_MAPPING[$jenisBelanja])) {
            return [
                'success' => false,
                'message' => 'Jenis belanja tidak valid: ' . $jenisBelanja
            ];
        }

        $field = self::JENIS_BELANJA_MAPPING[$jenisBelanja];
        $akunList = $this->akunRepo->getByJenisBelanja($field, $tahunAnggaran);

        $data = $akunList->map(function ($akun) {
            return [
                'id' => $akun->id,
                'kode_akun' => $akun->kode_akun,
                'nama_akun' => $akun->nama_akun,
                'text' => $akun->kode_akun . ' - ' . $akun->nama_akun,
                'level' => $akun->level
            ];
        });

        return [
            'success' => true,
            'data' => $data,
            'count' => $data->count()
        ];
    }

    /**
     * Get detail akun
     */
    public function getDetailAkun(int $akunId): array
    {
        $akun = $this->akunRepo->findById($akunId);

        if (!$akun) {
            return [
                'success' => false,
                'message' => 'Akun tidak ditemukan'
            ];
        }

        return [
            'success' => true,
            'data' => [
                'id' => $akun->id,
                'kode_akun' => $akun->kode_akun,
                'nama_akun' => $akun->nama_akun,
                'level' => $akun->level,
                'set_input' => $akun->set_input
            ]
        ];
    }

    /**
     * Get list paket belanja
     */
    public function getPaketBelanjaList(array $params): array
    {
        $paketList = $this->renjaRepo->getPaketBelanjaList(
            $params['id_rinci_sub_bl'],
            $params['tipe_paket'],
            $params['jenis_bl'] ?? null
        );

        return [
            'success' => true,
            'message' => 'Data paket berhasil dimuat',
            'data' => $paketList,
            'count' => $paketList->count()
        ];
    }

    /**
     * Create paket belanja baru
     */
    public function createPaketBelanja(array $data): array
    {
        DB::beginTransaction();

        try {
            // Validasi sub kegiatan
            $subKegiatan = $this->renjaRepo->getSubKegiatanBelanjaById($data['id_rinci_sub_bl']);
            if (!$subKegiatan) {
                throw new \Exception('Sub kegiatan tidak ditemukan');
            }

            // Validasi akun
            $akun = $this->akunRepo->findById($data['id_akun']);
            if (!$akun) {
                throw new \Exception('Akun tidak ditemukan');
            }

            // Get sumber dana
            $sumberDana = $this->renjaRepo->getFirstSumberDana($data['id_rinci_sub_bl']);

            // Insert paket
            $idPaket = $this->renjaRepo->createPaketBelanja([
                'sub_kegiatan' => $subKegiatan,
                'akun' => $akun,
                'sumber_dana' => $sumberDana,
                'tipe_paket' => $data['tipe_paket'],
                'uraian_paket' => $data['uraian_paket'],
                'jenis_bl' => $data['jenis_bl']
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Paket belanja berhasil ditambahkan',
                'data' => [
                    'id' => $idPaket,
                    'uraian_paket' => $data['uraian_paket'],
                    'is_paket' => $data['tipe_paket']
                ]
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating paket: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Create rincian belanja detail
     */
    public function createRincianBelanja(array $data): array
    {
        DB::beginTransaction();

        try {
            // Validasi
            $subKegiatan = $this->renjaRepo->getSubKegiatanBelanjaById($data['id_rinci_sub_bl']);
            if (!$subKegiatan) {
                throw new \Exception('Sub kegiatan tidak ditemukan');
            }

            $akun = $this->akunRepo->findById($data['id_akun']);
            if (!$akun) {
                throw new \Exception('Akun tidak ditemukan');
            }

            // Get nama paket jika ada
            $namaPaket = null;
            if ($data['id_paket_belanja']) {
                $paket = $this->renjaRepo->getPaketBelanjaById($data['id_paket_belanja']);
                $namaPaket = $paket->subtitle_teks ?? null;
            }

            $sumberDana = $this->renjaRepo->getFirstSumberDana($data['id_rinci_sub_bl']);

            // Calculate total
            $volume = floatval($data['volume']);
            $hargaSatuan = floatval($data['harga_satuan']);
            $totalHarga = $volume * $hargaSatuan;

            // Insert rincian
            $idRka = $this->renjaRepo->createRincianBelanja([
                'sub_kegiatan' => $subKegiatan,
                'akun' => $akun,
                'sumber_dana' => $sumberDana,
                'paket' => [
                    'id' => $data['id_paket_belanja'],
                    'nama' => $namaPaket,
                    'tipe' => $data['tipe_paket']
                ],
                'uraian' => $data['uraian'],
                'volume' => $volume,
                'satuan' => $data['satuan'],
                'harga_satuan' => $hargaSatuan,
                'total_harga' => $totalHarga,
                'jenis_bl' => $data['jenis_bl']
            ]);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Rincian belanja berhasil ditambahkan',
                'data' => [
                    'id' => $idRka,
                    'total' => $totalHarga
                ]
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating rincian: ' . $e->getMessage());
            throw $e;
        }
    }

    // ==================== PRIVATE HELPER METHODS ====================

    private function generateKodeBelanja($subKegiatan, $dataUnit): array
    {
        $idSubSkpd = $dataUnit->id_setup_unit ?? 0;

        return [
            'id_unik_sub_bl' => uniqid('subbl_', true),
            'kode_bl' => "{$subKegiatan->id_skpd}.{$idSubSkpd}.{$subKegiatan->id_program}.{$subKegiatan->id_kegiatan}",
            'kode_sbl' => "{$subKegiatan->id_skpd}.{$idSubSkpd}.{$subKegiatan->id_program}.{$subKegiatan->id_kegiatan}.{$subKegiatan->id_sub_kegiatan}"
        ];
    }

    private function insertSumberDana(array $sumberDanaList, int $idSubKegBl, string $kodeSbl): void
    {
        foreach ($sumberDanaList as $dana) {
            $this->renjaRepo->createDanaSubKegiatan([
                'id_sumber_dana' => $dana['id_sumber_dana'],
                'pagu' => $dana['pagu'],
                'idsubbl' => $idSubKegBl,
                'kode_sbl' => $kodeSbl
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
                'kode_sbl' => $kodeSbl
            ]);
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
                ? '<span class="badge badge-' . $randomColor . ' ms-2">' . $jumlahUsulan . ' Usulan Pokir</span>'
                : '';

            $checkIcon = $jumlahIndikator > 0
                ? '<i class="ki-outline ki-check-circle fs-2 text-success ms-2"></i>'
                : '';

            $formattedData[] = [
                'DT_RowIndex' => count($formattedData) + 1,
                'checkbox' => '',
                'group_skpd' => $row->kode_skpd . ' ' . $row->nama_skpd,
                'group_urusan' => $row->kode_urusan . ' ' . $row->nama_urusan,
                'group_program' => $row->kode_program . ' ' . $row->nama_program,
                'group_kegiatan' => $row->kode_giat . ' ' . $row->nama_giat,
                'sub_kegiatan' => $this->renderSubKegiatanColumn($row, $checkIcon, $usulanBadge),
                'status_sub_kegiatan' => '<span class="badge badge-light-danger">DIKUNCI</span>',
                'status_rincian' => '<span class="badge badge-light-danger">DIKUNCI</span>',
                'sebelum_perubahan' => number_format($row->pagumurni ?? 0, 2, '.', ','),
                'pagu_validasi' => number_format($row->pagu ?? 0, 2, '.', ','),
                'total_rincian' => number_format($row->pagu ?? 0, 3, '.', ','),
                'total_realisasi' => '0.00',
                'persentase' => '0.00 %',
                'aksi' => $this->renderActionButtons($row->id)
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
                    <a href="#" class="text-primary fw-bold">' . $row->kode_sub_giat . ' ' . $row->nama_sub_giat . '</a>
                    ' . $checkIcon . '
                    ' . $usulanBadge . '
                </div>
            </div>
        ';
    }

    private function renderActionButtons(int $id): string
    {
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
                        <a class="dropdown-item btn-lihat-sub-kegiatan" href="#" data-id="' . $id . '">
                            <i class="ki-outline ki-file-down fs-5 me-2 text-primary"></i>
                            Lihat Sub Kegiatan
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item btn-lihat-rincian" href="#" data-id="' . $id . '">
                            <i class="ki-outline ki-document fs-5 me-2 text-info"></i>
                            Lihat Rincian Belanja
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item btn-rka-paket" href="#" data-id="' . $id . '">
                            <i class="ki-outline ki-package fs-5 me-2 text-success"></i>
                            RKA Paket / Kelompok
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item btn-rka-rincian" href="#" data-id="' . $id . '">
                            <i class="ki-outline ki-copy fs-5 me-2 text-warning"></i>
                            RKA Rincian Belanja
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
                    })
                ];
            });
    }
}
