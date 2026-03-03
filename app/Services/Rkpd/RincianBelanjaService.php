<?php

namespace App\Services\Rkpd;

use App\Repositories\Referensi\AkunRepository;
use App\Repositories\Rkpd\RincianBelanjaRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RincianBelanjaService
{
    protected $rincianRepo;

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
        RincianBelanjaRepository $rincianRepo,
        AkunRepository $akunRepo
    ) {
        $this->rincianRepo = $rincianRepo;
        $this->akunRepo = $akunRepo;
    }

    /**
     * Get rincian sub kegiatan untuk ditampilkan di view
     */
    public function getRincianSubKegiatan(int $id): array
    {
        // $id di sini adalah id_sub_bl dari route /renja/{id}/rincian
        $subKegiatan = $this->rincianRepo->getSubKegiatanBelanjaByIdSubBl($id);

        if (! $subKegiatan) {
            return ['subKegiatan' => null];
        }

        $sumberDana = $this->rincianRepo->getSumberDanaBySubKegiatan(
            $subKegiatan->id,
            $subKegiatan->kode_sbl
        );

        $indikator = $this->rincianRepo->getIndikatorBySubKegiatan(
            $subKegiatan->id,
            $subKegiatan->kode_sbl
        );

        $rincianBelanja = $this->rincianRepo->getRincianBelanjaBySubKegiatan($subKegiatan->id);

        $groupedRincian = $this->groupRincianByObjek($rincianBelanja);

        // Format data terkelompok untuk view (nested structure)
        $dataTerkelompok = $this->formatDataTerkelompok($rincianBelanja);

        return [
            'subKegiatan' => $subKegiatan,
            'sumberDana' => $sumberDana,
            'indikator' => $indikator,
            'rincianBelanja' => $rincianBelanja,
            'groupedRincian' => $groupedRincian,
            'dataTerkelompok' => $dataTerkelompok,
        ];
    }

    /**
     * Get list paket belanja
     */
    public function getPaketBelanjaList(int $idRinciSubBl, int $tipePaket): array
    {
        $subKegiatan = $this->rincianRepo->getSubKegiatanBelanjaById($idRinciSubBl);

        if (! $subKegiatan) {
            return [
                'success' => false,
                'message' => 'Sub kegiatan tidak ditemukan',
                'data' => [],
            ];
        }

        $paketList = $this->rincianRepo->getPaketBelanjaList($subKegiatan->kode_sbl, $tipePaket);

        $formattedData = $paketList->map(function ($item) {
            $displayText = preg_replace('/^\[\#\]\s*/', '', $item->uraian_paket);

            return [
                'id' => $item->id,
                'uraian_paket' => $displayText,
                'uraian_paket_full' => $item->uraian_paket,
                'is_paket' => $item->is_paket,
                'kode_akun' => $item->kode_akun,
                'nama_akun' => $item->nama_akun,
                'jenis_bl' => $item->jenis_bl,
                'idsubtitle' => $item->idsubtitle,
            ];
        });

        return [
            'success' => true,
            'data' => $formattedData,
            'message' => 'Data paket berhasil dimuat',
        ];
    }

    /**
     * Create paket belanja
     */
    public function createPaketBelanja(array $data): array
    {
        DB::beginTransaction();

        try {
            $subKegiatan = $this->rincianRepo->getSubKegiatanBelanjaById($data['id_rinci_sub_bl']);
            if (! $subKegiatan) {
                throw new \Exception('Sub kegiatan tidak ditemukan');
            }

            $akun = $this->akunRepo->getAkunById($data['akun_id']);
            if (! $akun) {
                throw new \Exception('Akun tidak ditemukan');
            }

            $sumberDana = $this->rincianRepo->getSumberDanaById($data['id_sumber_dana']);

            $idPaket = $this->rincianRepo->createPaketBelanja([
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
                'id' => $idPaket,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating paket belanja: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Get akun by jenis belanja
     */
    public function getAkunByJenisBelanja(string $jenisBelanja, int $tahunAnggaran): array
    {
        if (! isset(self::JENIS_BELANJA_MAPPING[$jenisBelanja])) {
            return [
                'success' => false,
                'message' => 'Jenis belanja tidak valid',
                'data' => [],
            ];
        }

        $column = self::JENIS_BELANJA_MAPPING[$jenisBelanja];

        $akun = $this->akunRepo->getAkunByJenisBelanja($column, $tahunAnggaran);

        return [
            'success' => true,
            'data' => $akun,
            'message' => 'Data akun berhasil dimuat',
        ];
    }

    /**
     * Get detail akun
     */
    public function getDetailAkun(int $akunId): array
    {
        $akun = $this->akunRepo->getAkunById($akunId);

        if (! $akun) {
            return [
                'success' => false,
                'message' => 'Akun tidak ditemukan',
            ];
        }

        return [
            'success' => true,
            'data' => $akun,
        ];
    }

    /**
     * Create rincian belanja
     */
    public function createRincianBelanja(array $data): array
    {
        DB::beginTransaction();

        try {
            $subKegiatan = $this->rincianRepo->getSubKegiatanBelanjaById($data['id_rinci_sub_bl']);
            if (! $subKegiatan) {
                throw new \Exception('Sub kegiatan tidak ditemukan');
            }

            $akun = $this->akunRepo->getAkunById($data['akun_id']);
            if (! $akun) {
                throw new \Exception('Akun tidak ditemukan');
            }

            $sumberDana = $this->rincianRepo->getSumberDanaById($data['id_sumber_dana']);

            $paket = $this->rincianRepo->getPaketBelanjaById($data['id_paket_belanja']);
            if (! $paket) {
                throw new \Exception('Paket belanja tidak ditemukan');
            }

            $totalHarga = $data['volume'] * $data['harga_satuan'];

            $idRincian = $this->rincianRepo->createRincianBelanja([
                'sub_kegiatan' => $subKegiatan,
                'akun' => $akun,
                'sumber_dana' => $sumberDana,
                'paket' => [
                    'id' => $paket->idsubtitle ?? $paket->id,
                    'nama' => $paket->subtitle_teks,
                    'tipe' => $paket->is_paket,
                ],
                'jenis_bl' => $data['jenis_bl'],
                'uraian' => $data['uraian'],
                'volume' => $data['volume'],
                'satuan' => $data['satuan'],
                'harga_satuan' => $data['harga_satuan'],
                'total_harga' => $totalHarga,
            ]);

            // Update total pagu sub kegiatan
            $totalRincian = $this->rincianRepo->calculateTotalRincian($subKegiatan->id);
            $this->rincianRepo->updatePaguSubKegiatan($subKegiatan->id, $totalRincian);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Rincian belanja berhasil ditambahkan',
                'id' => $idRincian,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating rincian belanja: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Update rincian belanja
     */
    public function updateRincianBelanja(int $id, array $data): array
    {
        DB::beginTransaction();

        try {
            $rincian = $this->rincianRepo->getRincianBelanjaById($id);
            if (! $rincian) {
                throw new \Exception('Rincian belanja tidak ditemukan');
            }

            $totalHarga = $data['volume'] * $data['harga_satuan'];

            $updated = $this->rincianRepo->updateRincianBelanja($id, [
                'uraian' => $data['uraian'],
                'volume' => $data['volume'],
                'satuan' => $data['satuan'],
                'harga_satuan' => $data['harga_satuan'],
                'total_harga' => $totalHarga,
            ]);

            if (! $updated) {
                throw new \Exception('Gagal mengupdate rincian belanja');
            }

            // Update total pagu sub kegiatan
            $totalRincian = $this->rincianRepo->calculateTotalRincian($rincian->idsubbl);
            $this->rincianRepo->updatePaguSubKegiatan($rincian->idsubbl, $totalRincian);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Rincian belanja berhasil diupdate',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating rincian belanja: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete rincian belanja
     */
    public function deleteRincianBelanja(int $id): array
    {
        DB::beginTransaction();

        try {
            $rincian = $this->rincianRepo->getRincianBelanjaById($id);
            if (! $rincian) {
                throw new \Exception('Rincian belanja tidak ditemukan');
            }

            $deleted = $this->rincianRepo->softDeleteRincianBelanja($id);

            if (! $deleted) {
                throw new \Exception('Gagal menghapus rincian belanja');
            }

            // Update total pagu sub kegiatan
            $totalRincian = $this->rincianRepo->calculateTotalRincian($rincian->idsubbl);
            $this->rincianRepo->updatePaguSubKegiatan($rincian->idsubbl, $totalRincian);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Rincian belanja berhasil dihapus',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting rincian belanja: '.$e->getMessage());
            throw $e;
        }
    }

    /**
     * Group rincian by kode akun
     */
    private function groupRincianByObjek($rincianBelanja)
    {
        return $rincianBelanja
            ->groupBy('kode_akun')
            ->map(function ($items, $kodeAkun) {
                $firstItem = $items->first();

                // Filter hanya rincian detail (bukan header paket)
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

    /**
     * Format data terkelompok untuk view dengan nested structure
     * Structure: Hashtag [#] -> Mintag [-] -> Rekening -> Items
     */
    private function formatDataTerkelompok($rincianBelanja)
    {
        $result = [];

        // Group by hashtag (subtitle_teks) - Paket/Kelompok
        $groupedByHashtag = $rincianBelanja->groupBy('subtitle_teks');

        foreach ($groupedByHashtag as $hashtag => $itemsHashtag) {
            if (empty($hashtag)) {
                continue; // Skip jika tidak ada hashtag
            }

            $hashtagData = [
                'title' => $hashtag,
                'total' => 0,
                'mintag' => [],
            ];

            // Group by mintag (ket_bl_teks untuk items dengan idsubtitle)
            $itemsWithSubtitle = $itemsHashtag->filter(function ($item) {
                return $item->idsubtitle !== null && $item->ket_bl_teks !== '--- PAKET/KELOMPOK ---';
            });

            // Jika ada items, group by mintag
            $groupedByMintag = $itemsWithSubtitle->groupBy(function ($item) {
                // Extract mintag dari ket_bl_teks (text sebelum nama komponen)
                // Format: [-] Kategori Belanja -> Nama Komponen
                $text = $item->ket_bl_teks ?? $item->spek;
                if (preg_match('/^\[\-\]\s*(.+?)\s*-/', $text, $matches)) {
                    return '[#] '.trim($matches[1]);
                }

                return 'Lain-lain';
            });

            foreach ($groupedByMintag as $mintag => $itemsMintag) {
                $mintagData = [
                    'title' => $mintag,
                    'total' => 0,
                    'rekening' => [],
                ];

                // Group by kode_akun (rekening)
                $groupedByRekening = $itemsMintag->groupBy('kode_akun');

                foreach ($groupedByRekening as $kodeRekening => $itemsRekening) {
                    $firstItem = $itemsRekening->first();

                    $rekeningData = [
                        'kode_akun' => $kodeRekening,
                        'nama_akun' => $firstItem->nama_akun,
                        'total' => 0,
                        'items' => [],
                    ];

                    // Add items
                    foreach ($itemsRekening as $item) {
                        $totalHarga = ($item->volume ?? 0) * ($item->harga_satuan ?? 0);

                        $rekeningData['items'][] = [
                            'id' => $item->id,
                            'nama_komponen' => $item->ket_bl_teks ?? $item->spek,
                            'volume' => $item->volume ?? 0,
                            'satuan' => $item->satuan ?? '',
                            'harga_satuan' => $item->harga_satuan ?? 0,
                            'total_harga' => $totalHarga,
                        ];

                        $rekeningData['total'] += $totalHarga;
                        $mintagData['total'] += $totalHarga;
                        $hashtagData['total'] += $totalHarga;
                    }

                    $mintagData['rekening'][$kodeRekening] = $rekeningData;
                }

                $hashtagData['mintag'][$mintag] = $mintagData;
            }

            $result[$hashtag] = $hashtagData;
        }

        return $result;
    }
}
