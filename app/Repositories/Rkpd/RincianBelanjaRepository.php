<?php

namespace App\Repositories\Rkpd;

use Illuminate\Support\Facades\DB;

/**
 * Repository untuk Data Access Rincian Belanja
 */
class RincianBelanjaRepository
{
    protected const TAHUN_ANGGARAN_DEFAULT = 2025;

    protected const ID_DAERAH = 604;

    /**
     * Get sub kegiatan belanja by ID dengan relasi
     */
    public function getSubKegiatanBelanjaById(int $id)
    {
        return DB::table('data_sub_keg_bl')
            ->where('id', $id)
            ->where('tahun_anggaran', self::TAHUN_ANGGARAN_DEFAULT)
            ->where('active', 1)
            ->first();
    }

    /**
     * Get sub kegiatan belanja by ID Sub Bl (untuk compatibility dengan route)
     */
    public function getSubKegiatanBelanjaByIdSubBl(int $idSubBl)
    {
        return DB::table('data_sub_keg_bl')
            ->where('id_sub_bl', $idSubBl)
            ->where('tahun_anggaran', self::TAHUN_ANGGARAN_DEFAULT)
            ->where('active', 1)
            ->first();
    }

    /**
     * Get sumber dana by sub kegiatan
     */
    public function getSumberDanaBySubKegiatan(int $idSubBl, string $kodeSbl)
    {
        return DB::table('data_dana_sub_keg')
            ->where('idsubbl', $idSubBl)
            ->where('kode_sbl', $kodeSbl)
            ->where('tahun_anggaran', self::TAHUN_ANGGARAN_DEFAULT)
            ->where('active', 1)
            ->get();
    }

    /**
     * Get indikator by sub kegiatan
     */
    public function getIndikatorBySubKegiatan(int $idSubBl, string $kodeSbl)
    {
        return DB::table('data_sub_keg_indikator')
            ->where('idsubbl', $idSubBl)
            ->where('kode_sbl', $kodeSbl)
            ->where('tahun_anggaran', self::TAHUN_ANGGARAN_DEFAULT)
            ->where('active', 1)
            ->get();
    }

    /**
     * Get rincian belanja by sub kegiatan
     */
    public function getRincianBelanjaBySubKegiatan(int $idSubBl)
    {
        return DB::table('data_rka')
            ->where('idsubbl', $idSubBl)
            ->where('tahun_anggaran', self::TAHUN_ANGGARAN_DEFAULT)
            ->where('active', 1)
            ->orderBy('kode_akun')
            ->orderBy('is_paket')
            ->orderBy('idsubtitle')
            ->orderBy('id')
            ->get();
    }

    /**
     * Get paket belanja list
     */
    public function getPaketBelanjaList(string $kodeSbl, int $tipePaket)
    {
        return DB::table('data_rka')
            ->select(
                DB::raw('MIN(id) as id'),
                'subtitle_teks as uraian_paket',
                'is_paket',
                'kode_akun',
                'nama_akun',
                'jenis_bl',
                'idsubtitle'
            )
            ->where('kode_sbl', $kodeSbl)
            ->where('tahun_anggaran', self::TAHUN_ANGGARAN_DEFAULT)
            ->where('active', 1)
            ->where('is_paket', $tipePaket)
            ->whereNotNull('subtitle_teks')
            ->where('subtitle_teks', '!=', '')
            ->where(function ($q) {
                $q->whereNull('ket_bl_teks')
                    ->orWhere('ket_bl_teks', '!=', '--- PAKET/KELOMPOK ---');
            })
            ->groupBy('subtitle_teks', 'is_paket', 'kode_akun', 'nama_akun', 'jenis_bl', 'idsubtitle')
            ->orderBy('subtitle_teks', 'ASC')
            ->get();
    }

    /**
     * Get paket belanja by ID
     */
    public function getPaketBelanjaById(int $id)
    {
        return DB::table('data_rka')
            ->where('id', $id)
            ->first();
    }

    /**
     * Create paket belanja
     */
    public function createPaketBelanja(array $data): int
    {
        $subKegiatan = $data['sub_kegiatan'];
        $akun = $data['akun'];
        $sumberDana = $data['sumber_dana'];

        return DB::table('data_rka')->insertGetId([
            'id_rinci_sub_bl' => $subKegiatan->id,
            'kode_sbl' => $subKegiatan->kode_sbl,
            'kode_bl' => $subKegiatan->kode_bl,
            'tahun_anggaran' => $subKegiatan->tahun_anggaran ?? self::TAHUN_ANGGARAN_DEFAULT,
            'jenis_bl' => $data['jenis_bl'],
            'kode_akun' => $akun->kode_akun,
            'nama_akun' => $akun->nama_akun,
            'is_paket' => $data['tipe_paket'],
            'subtitle_teks' => $data['uraian_paket'],
            'idsubtitle' => null,
            'ket_bl_teks' => '--- PAKET/KELOMPOK ---',
            'spek' => $data['uraian_paket'],
            'volume' => 0,
            'satuan' => 'Paket',
            'harga_satuan' => 0,
            'total_harga' => 0,
            'rincian' => 0,
            'rincian_murni' => 0,
            'id_dana' => $sumberDana->iddana ?? null,
            'nama_dana' => $sumberDana->namadana ?? null,
            'kode_dana' => $sumberDana->kodedana ?? null,
            'created_user' => auth()->id() ?? null,
            'createddate' => date('Y-m-d'),
            'createdtime' => date('H:i:s'),
            'active' => 1,
            'is_locked' => 0,
            'update_at' => now(),
            'id_daerah' => self::ID_DAERAH,
            'id_standar_nfs' => 0,
            'idbl' => null,
            'idsubbl' => $subKegiatan->id,
            'harga_satuan_murni' => 0,
            'volume_murni' => 0,
            'totalpajak' => 0,
            'pajak' => 0,
            'pajak_murni' => 0,
        ]);
    }

    /**
     * Create rincian belanja
     */
    public function createRincianBelanja(array $data): int
    {
        $subKegiatan = $data['sub_kegiatan'];
        $akun = $data['akun'];
        $sumberDana = $data['sumber_dana'];
        $paket = $data['paket'];

        return DB::table('data_rka')->insertGetId([
            'id_rinci_sub_bl' => $subKegiatan->id,
            'kode_sbl' => $subKegiatan->kode_sbl,
            'kode_bl' => $subKegiatan->kode_bl,
            'tahun_anggaran' => $subKegiatan->tahun_anggaran ?? self::TAHUN_ANGGARAN_DEFAULT,
            'jenis_bl' => $data['jenis_bl'],
            'kode_akun' => $akun->kode_akun,
            'nama_akun' => $akun->nama_akun,
            'is_paket' => $paket['tipe'],
            'idsubtitle' => $paket['id'],
            'subtitle_teks' => $paket['nama'],
            'ket_bl_teks' => $data['uraian'],
            'spek' => $data['uraian'],
            'volume' => $data['volume'],
            'volume_murni' => $data['volume'],
            'satuan' => $data['satuan'],
            'harga_satuan' => $data['harga_satuan'],
            'harga_satuan_murni' => $data['harga_satuan'],
            'total_harga' => $data['total_harga'],
            'rincian' => $data['total_harga'],
            'rincian_murni' => $data['total_harga'],
            'volum1' => $data['volume'],
            'sat1' => $data['satuan'],
            'koefisien' => $data['volume'],
            'koefisien_murni' => $data['volume'],
            'id_dana' => $sumberDana->iddana ?? null,
            'nama_dana' => $sumberDana->namadana ?? null,
            'kode_dana' => $sumberDana->kodedana ?? null,
            'created_user' => auth()->id() ?? null,
            'createddate' => date('Y-m-d'),
            'createdtime' => date('H:i:s'),
            'updated_user' => auth()->id() ?? null,
            'updateddate' => date('Y-m-d'),
            'updatedtime' => date('H:i:s'),
            'active' => 1,
            'is_locked' => 0,
            'akun_locked' => 0,
            'ssh_locked' => 0,
            'id_daerah' => self::ID_DAERAH,
            'id_standar_nfs' => 0,
            'idbl' => null,
            'idsubbl' => $subKegiatan->id,
            'totalpajak' => 0,
            'pajak' => 0,
            'pajak_murni' => 0,
            'update_at' => now(),
        ]);
    }

    /**
     * Get rincian belanja by ID
     */
    public function getRincianBelanjaById(int $id)
    {
        return DB::table('data_rka')
            ->where('id', $id)
            ->where('active', 1)
            ->first();
    }

    /**
     * Update rincian belanja
     */
    public function updateRincianBelanja(int $id, array $data): bool
    {
        return DB::table('data_rka')
            ->where('id', $id)
            ->update([
                'ket_bl_teks' => $data['uraian'],
                'spek' => $data['uraian'],
                'volume' => $data['volume'],
                'satuan' => $data['satuan'],
                'harga_satuan' => $data['harga_satuan'],
                'total_harga' => $data['total_harga'],
                'rincian' => $data['total_harga'],
                'volum1' => $data['volume'],
                'sat1' => $data['satuan'],
                'koefisien' => $data['volume'],
                'updated_user' => auth()->id() ?? null,
                'updateddate' => date('Y-m-d'),
                'updatedtime' => date('H:i:s'),
                'update_at' => now(),
            ]) > 0;
    }

    /**
     * Soft delete rincian belanja
     */
    public function softDeleteRincianBelanja(int $id): bool
    {
        return DB::table('data_rka')
            ->where('id', $id)
            ->update([
                'active' => 0,
                'updated_user' => auth()->id() ?? null,
                'updateddate' => date('Y-m-d'),
                'updatedtime' => date('H:i:s'),
                'update_at' => now(),
            ]) > 0;
    }

    /**
     * Get sumber dana by ID
     */
    public function getSumberDanaById(int $id)
    {
        return DB::table('data_dana_sub_keg')
            ->where('id', $id)
            ->first();
    }

    /**
     * Calculate total rincian by sub kegiatan
     */
    public function calculateTotalRincian(int $idSubBl): float
    {
        return DB::table('data_rka')
            ->where('idsubbl', $idSubBl)
            ->where('active', 1)
            ->whereNotNull('idsubtitle')
            ->sum(DB::raw('volume * harga_satuan'));
    }

    /**
     * Update pagu sub kegiatan after rincian changes
     */
    public function updatePaguSubKegiatan(int $idSubBl, float $totalPagu): bool
    {
        return DB::table('data_sub_keg_bl')
            ->where('id', $idSubBl)
            ->update([
                'pagu' => $totalPagu,
                'update_at' => now(),
            ]) > 0;
    }
}
