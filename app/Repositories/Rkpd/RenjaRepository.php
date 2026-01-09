<?php

namespace App\Repositories\Rkpd;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

/**
 * Repository untuk Data Access RENJA
 */
class RenjaRepository
{
    protected const TAHUN_ANGGARAN_DEFAULT = 2025;
    protected const ID_DAERAH = 604;
    protected const URUSAN_X_ID = 20; // Urusan yang bisa diakses semua SKPD

    // ==================== RENJA / SUB KEGIATAN ====================

    public function getAll()
    {
        return DB::table('data_sub_keg_bl')
            ->where('tahun_anggaran', self::TAHUN_ANGGARAN_DEFAULT)
            ->where('active', 1)
            ->get();
    }

    public function getSubKegiatanBelanjaById(int $id)
    {
        return DB::table('data_sub_keg_bl')
            ->where('id', $id)
            ->where('tahun_anggaran', self::TAHUN_ANGGARAN_DEFAULT)
            ->where('active', 1)
            ->first();
    }

    public function getSubKegiatanWithUnit(int $id)
    {
        return DB::table('data_sub_keg_bl as dskb')
            ->select('dskb.*', 'du.nama_skpd as nama_unit')
            ->leftJoin('data_unit as du', function ($join) {
                $join->on('dskb.id_skpd', '=', 'du.id_skpd')
                    ->where('du.tahun_anggaran', '=', self::TAHUN_ANGGARAN_DEFAULT);
            })
            ->where('dskb.id_sub_bl', $id)
            ->where('dskb.tahun_anggaran', self::TAHUN_ANGGARAN_DEFAULT)
            ->where('dskb.active', 1)
            ->first();
    }

    /**
     * Get sub kegiatan dengan indikator berdasarkan SKPD
     * Menggabungkan sub kegiatan milik SKPD + urusan X
     */
    public function getSubKegiatanWithIndikatorBySkpd(int $idSkpd, int $tahunAnggaran): Collection
    {
        // Query 1: Sub kegiatan milik SKPD
        $query1 = DB::table('data_unit as du')
            ->join('bidang_urusan as bu', function ($join) {
                $join->whereRaw('bu.id IN (du.bidur_1, du.bidur_2, du.bidur_3)');
            })
            ->join('program as p', 'p.id_bidang_urusan', '=', 'bu.id')
            ->join('kegiatan as k', 'k.id_program', '=', 'p.id')
            ->join('sub_kegiatan as sk', 'sk.id_kegiatan', '=', 'k.id')
            ->leftJoin('data_master_indikator_subgiat as dmis', function ($join) use ($tahunAnggaran) {
                $join->on('dmis.id_sub_keg', '=', 'sk.id')
                    ->where('dmis.tahun_anggaran', '=', $tahunAnggaran)
                    ->where('dmis.active', '=', 1);
            })
            ->select(
                'du.id_skpd',
                'du.kode_skpd',
                'du.nama_skpd',
                'du.bidur_1',
                'du.bidur_2',
                'du.bidur_3',
                'bu.id as id_bidang_urusan',
                'bu.kode_bidang_urusan',
                'bu.nama_bidang_urusan',
                'p.id as id_program',
                'p.kode_program',
                'p.nama_program',
                'k.id as id_kegiatan',
                'k.kode_kegiatan',
                'k.nama_kegiatan',
                'sk.id as id_sub_kegiatan',
                'sk.kode_sub_kegiatan',
                'sk.nama_sub_kegiatan',
                'dmis.id as id_indikator',
                'dmis.indikator',
                'dmis.satuan'
            )
            ->where('du.id_skpd', $idSkpd)
            ->where('du.tahun_anggaran', $tahunAnggaran)
            ->where('bu.id', '>', 0);

        // Query 2: Sub kegiatan dari urusan X
        $query2 = DB::table('data_unit as du')
            ->join('bidang_urusan as bu', 'bu.id_urusan', '=', DB::raw(self::URUSAN_X_ID))
            ->join('program as p', 'p.id_bidang_urusan', '=', 'bu.id')
            ->join('kegiatan as k', 'k.id_program', '=', 'p.id')
            ->join('sub_kegiatan as sk', 'sk.id_kegiatan', '=', 'k.id')
            ->leftJoin('data_master_indikator_subgiat as dmis', function ($join) use ($tahunAnggaran) {
                $join->on('dmis.id_sub_keg', '=', 'sk.id')
                    ->where('dmis.tahun_anggaran', '=', $tahunAnggaran)
                    ->where('dmis.active', '=', 1);
            })
            ->select(
                'du.id_skpd',
                'du.kode_skpd',
                'du.nama_skpd',
                'du.bidur_1',
                'du.bidur_2',
                'du.bidur_3',
                'bu.id as id_bidang_urusan',
                'bu.kode_bidang_urusan',
                'bu.nama_bidang_urusan',
                'p.id as id_program',
                'p.kode_program',
                'p.nama_program',
                'k.id as id_kegiatan',
                'k.kode_kegiatan',
                'k.nama_kegiatan',
                'sk.id as id_sub_kegiatan',
                'sk.kode_sub_kegiatan',
                'sk.nama_sub_kegiatan',
                'dmis.id as id_indikator',
                'dmis.indikator',
                'dmis.satuan'
            )
            ->where('du.id_skpd', $idSkpd)
            ->where('du.tahun_anggaran', $tahunAnggaran);

        return $query1
            ->unionAll($query2)
            ->orderBy('kode_bidang_urusan')
            ->orderBy('kode_sub_kegiatan')
            ->get();
    }

    /**
     * Get detail sub kegiatan dengan urusan untuk insert
     */
    public function getSubKegiatanDetailById(int $idSkpd, int $idSubKegiatan, int $tahunAnggaran)
    {
        $query1 = DB::table('data_unit as du')
            ->join('bidang_urusan as bu', function ($join) {
                $join->whereRaw('bu.id IN (du.bidur_1, du.bidur_2, du.bidur_3)');
            })
            ->join('program as p', 'p.id_bidang_urusan', '=', 'bu.id')
            ->join('kegiatan as k', 'k.id_program', '=', 'p.id')
            ->join('sub_kegiatan as sk', 'sk.id_kegiatan', '=', 'k.id')
            ->leftJoin('urusan as u', 'u.id', '=', 'bu.id_urusan')
            ->select(
                'du.id_skpd',
                'du.kode_skpd',
                'du.nama_skpd',
                'u.id as id_urusan',
                'u.kode_urusan',
                'u.nama_urusan',
                'bu.id as id_bidang_urusan',
                'bu.kode_bidang_urusan',
                'bu.nama_bidang_urusan',
                'p.id as id_program',
                'p.kode_program',
                'p.nama_program',
                'k.id as id_kegiatan',
                'k.kode_kegiatan',
                'k.nama_kegiatan',
                'sk.id as id_sub_kegiatan',
                'sk.kode_sub_kegiatan',
                'sk.nama_sub_kegiatan'
            )
            ->where('du.id_skpd', $idSkpd)
            ->where('sk.id', $idSubKegiatan)
            ->where('du.tahun_anggaran', $tahunAnggaran)
            ->where('bu.id', '>', 0);

        $query2 = DB::table('data_unit as du')
            ->join('bidang_urusan as bu', 'bu.id_urusan', '=', DB::raw(self::URUSAN_X_ID))
            ->join('program as p', 'p.id_bidang_urusan', '=', 'bu.id')
            ->join('kegiatan as k', 'k.id_program', '=', 'p.id')
            ->join('sub_kegiatan as sk', 'sk.id_kegiatan', '=', 'k.id')
            ->leftJoin('urusan as u', 'u.id', '=', 'bu.id_urusan')
            ->select(
                'du.id_skpd',
                'du.kode_skpd',
                'du.nama_skpd',
                'u.id as id_urusan',
                'u.kode_urusan',
                'u.nama_urusan',
                'bu.id as id_bidang_urusan',
                'bu.kode_bidang_urusan',
                'bu.nama_bidang_urusan',
                'p.id as id_program',
                'p.kode_program',
                'p.nama_program',
                'k.id as id_kegiatan',
                'k.kode_kegiatan',
                'k.nama_kegiatan',
                'sk.id as id_sub_kegiatan',
                'sk.kode_sub_kegiatan',
                'sk.nama_sub_kegiatan'
            )
            ->where('du.id_skpd', $idSkpd)
            ->where('sk.id', $idSubKegiatan)
            ->where('du.tahun_anggaran', $tahunAnggaran);

        return $query1->unionAll($query2)->first();
    }

    public function createSubKegiatanBelanja(array $data): int
    {
        $subKegiatan = $data['sub_kegiatan'];
        $dataUnit = $data['data_unit'];
        $codes = $data['codes'];

        return DB::table('data_sub_keg_bl')->insertGetId([
            'id_sub_skpd' => $dataUnit->id_setup_unit ?? 0,
            'id_lokasi' => null,
            'id_label_kokab' => null,
            'nama_dana' => null,
            'no_sub_giat' => $subKegiatan->kode_sub_kegiatan,
            'kode_giat' => $subKegiatan->kode_kegiatan,
            'id_program' => $subKegiatan->id_program,
            'nama_lokasi' => (string) self::ID_DAERAH,
            'waktu_akhir' => $data['waktu_akhir'],
            'pagu_n_lalu' => 0,
            'id_urusan' => $subKegiatan->id_urusan,
            'id_unik_sub_bl' => $codes['id_unik_sub_bl'],
            'id_sub_giat' => $subKegiatan->id_sub_kegiatan,
            'label_prov' => null,
            'kode_program' => $subKegiatan->kode_program,
            'kode_sub_giat' => $subKegiatan->kode_sub_kegiatan,
            'no_program' => $subKegiatan->kode_program,
            'kode_urusan' => $subKegiatan->kode_urusan,
            'kode_bidang_urusan' => $subKegiatan->kode_bidang_urusan,
            'nama_program' => $subKegiatan->nama_program,
            'target_4' => null,
            'target_5' => null,
            'id_bidang_urusan' => $subKegiatan->id_bidang_urusan,
            'nama_bidang_urusan' => $subKegiatan->nama_bidang_urusan,
            'target_3' => null,
            'no_giat' => $subKegiatan->kode_kegiatan,
            'id_label_prov' => 0,
            'waktu_awal' => $data['waktu_awal'],
            'pagumurni' => $data['total_pagu'],
            'pagu' => $data['total_pagu'],
            'pagu_simda' => 0,
            'output_sub_giat' => null,
            'sasaran' => null,
            'indikator' => null,
            'id_dana' => null,
            'nama_sub_giat' => $subKegiatan->nama_sub_kegiatan,
            'pagu_n_depan' => $data['pagu_n_depan'],
            'satuan' => null,
            'id_rpjmd' => 0,
            'id_giat' => $subKegiatan->id_kegiatan,
            'id_label_pusat' => 0,
            'nama_giat' => $subKegiatan->nama_kegiatan,
            'kode_skpd' => $subKegiatan->kode_skpd,
            'nama_skpd' => $subKegiatan->nama_skpd,
            'kode_sub_skpd' => $dataUnit->kode_skpd ?? '',
            'id_skpd' => $subKegiatan->id_skpd,
            'id_sub_bl' => null,
            'nama_sub_skpd' => $dataUnit->nama_skpd ?? '',
            'target_1' => null,
            'nama_urusan' => $subKegiatan->nama_urusan,
            'target_2' => null,
            'label_kokab' => null,
            'label_pusat' => null,
            'pagu_keg' => $data['total_pagu'],
            'pagu_fmis' => 0,
            'id_bl' => null,
            'kode_bl' => $codes['kode_bl'],
            'kode_sbl' => $codes['kode_sbl'],
            'active' => 1,
            'update_at' => now(),
            'tahun_anggaran' => $data['tahun_anggaran']
        ]);
    }

    // ==================== SUMBER DANA ====================

    public function getSumberDana()
    {
        return DB::table('sumber_dana')->get();
    }

    public function getSumberDanaBySubKegiatan(int $idSubKeg)
    {
        return DB::table('data_dana_sub_keg')
            ->where('idsubbl', $idSubKeg)
            ->where('tahun_anggaran', self::TAHUN_ANGGARAN_DEFAULT)
            ->where('active', 1)
            ->get();
    }

    public function getFirstSumberDana(int $idSubKeg)
    {
        return DB::table('data_dana_sub_keg')
            ->where('idsubbl', $idSubKeg)
            ->where('active', 1)
            ->first();
    }

    public function createDanaSubKegiatan(array $data): void
    {
        $sumberDanaInfo = DB::table('sumber_dana')
            ->where('id', $data['id_sumber_dana'])
            ->first();

        if ($sumberDanaInfo) {
            DB::table('data_dana_sub_keg')->insert([
                'namadana' => $sumberDanaInfo->nama_dana,
                'kodedana' => $sumberDanaInfo->kode_dana,
                'iddana' => $data['id_sumber_dana'],
                'iddanasubbl' => null,
                'pagudana' => $data['pagu'],
                'kode_sbl' => $data['kode_sbl'],
                'idsubbl' => $data['idsubbl'],
                'is_locked' => 0,
                'active' => 1,
                'update_at' => now(),
                'tahun_anggaran' => self::TAHUN_ANGGARAN_DEFAULT
            ]);
        }
    }

    // ==================== INDIKATOR ====================

    public function getIndikatorBySubKegiatan(int $idSubKeg)
    {
        return DB::table('data_sub_keg_indikator')
            ->where('idsubbl', $idSubKeg)
            ->where('tahun_anggaran', self::TAHUN_ANGGARAN_DEFAULT)
            ->where('active', 1)
            ->get();
    }

    public function countIndikator(string $kodeSbl): int
    {
        return DB::table('data_sub_keg_indikator')
            ->where('kode_sbl', $kodeSbl)
            ->where('active', 1)
            ->count();
    }

    public function createIndikatorSubKegiatan(array $data): void
    {
        DB::table('data_sub_keg_indikator')->insert([
            'outputteks' => $data['output_teks'],
            'targetoutput' => $data['target_output'],
            'satuanoutput' => $data['satuan_output'],
            'idoutputbl' => $data['id_output_bl'],
            'targetoutputteks' => $data['target_output'],
            'kode_sbl' => $data['kode_sbl'],
            'idsubbl' => $data['idsubbl'],
            'bobot_kinerja' => '1',
            'active' => 1,
            'update_at' => now(),
            'tahun_anggaran' => self::TAHUN_ANGGARAN_DEFAULT
        ]);
    }

    // ==================== DATA UNIT / SKPD ====================

    public function getDataUnit(int $tahunAnggaran)
    {
        return DB::table('data_unit')
            ->where('tahun_anggaran', $tahunAnggaran)
            ->orderBy('nama_skpd')
            ->get();
    }

    public function getDataUnitById(int $idSkpd, int $tahunAnggaran)
    {
        return DB::table('data_unit')
            ->where('id_skpd', $idSkpd)
            ->where('tahun_anggaran', $tahunAnggaran)
            ->first();
    }

    // ==================== REFERENSI ====================

    public function getDataDaerah()
    {
        return DB::table('data_daerah')->get();
    }

    public function getDataKecamatan()
    {
        return DB::table('data_kecamatan')->get();
    }

    public function getDataKelurahan()
    {
        return DB::table('data_kelurahan')->get();
    }

    public function getDataBulan()
    {
        return DB::table('data_bulan')->get();
    }

    // ==================== DATATABLE ====================

    public function getDataTableQuery(?string $searchValue)
    {
        $query = DB::table('data_sub_keg_bl as dskb')
            ->leftJoin('data_dana_sub_keg as ddsk', 'dskb.id', '=', 'ddsk.idsubbl')
            ->select(
                'dskb.id',
                'dskb.id_sub_bl',  
                'dskb.kode_sbl',
                'dskb.kode_skpd',
                'dskb.nama_skpd',
                'dskb.kode_urusan',
                'dskb.nama_urusan',
                'dskb.kode_bidang_urusan',
                'dskb.nama_bidang_urusan',
                'dskb.kode_program',
                'dskb.nama_program',
                'dskb.kode_giat',
                'dskb.nama_giat',
                'dskb.kode_sub_giat',
                'dskb.nama_sub_giat',
                'dskb.pagu',
                'dskb.pagumurni',
                'dskb.active',
                DB::raw('COUNT(DISTINCT ddsk.iddana) as jumlah_sumber_dana'),
                DB::raw('GROUP_CONCAT(DISTINCT ddsk.namadana SEPARATOR ", ") as sumber_dana_list')
            )
            ->where('dskb.tahun_anggaran', self::TAHUN_ANGGARAN_DEFAULT)
            ->where('dskb.active', 1)
            ->groupBy(
                'dskb.id',
                'dskb.id_sub_bl',
                'dskb.kode_sbl',
                'dskb.kode_skpd',
                'dskb.nama_skpd',
                'dskb.kode_urusan',
                'dskb.nama_urusan',
                'dskb.kode_bidang_urusan',
                'dskb.nama_bidang_urusan',
                'dskb.kode_program',
                'dskb.nama_program',
                'dskb.kode_giat',
                'dskb.nama_giat',
                'dskb.kode_sub_giat',
                'dskb.nama_sub_giat',
                'dskb.pagu',
                'dskb.pagumurni',
                'dskb.active'
            )
            ->orderBy('dskb.kode_skpd')
            ->orderBy('dskb.kode_urusan')
            ->orderBy('dskb.kode_program')
            ->orderBy('dskb.kode_giat')
            ->orderBy('dskb.kode_sub_giat');

        if ($searchValue) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('dskb.nama_sub_giat', 'like', "%{$searchValue}%")
                    ->orWhere('dskb.kode_sub_giat', 'like', "%{$searchValue}%")
                    ->orWhere('dskb.nama_skpd', 'like', "%{$searchValue}%")
                    ->orWhere('dskb.kode_sbl', 'like', "%{$searchValue}%");
            });
        }

        return $query;
    }

    public function getTotalRecords(): int
    {
        return DB::table('data_sub_keg_bl')
            ->where('tahun_anggaran', self::TAHUN_ANGGARAN_DEFAULT)
            ->where('active', 1)
            ->count();
    }

    public function getFilteredCount(?string $searchValue): int
    {
        if (!$searchValue) {
            return $this->getTotalRecords();
        }

        return DB::table('data_sub_keg_bl')
            ->where('tahun_anggaran', self::TAHUN_ANGGARAN_DEFAULT)
            ->where('active', 1)
            ->where(function ($q) use ($searchValue) {
                $q->where('nama_sub_giat', 'like', "%{$searchValue}%")
                    ->orWhere('kode_sub_giat', 'like', "%{$searchValue}%")
                    ->orWhere('nama_skpd', 'like', "%{$searchValue}%")
                    ->orWhere('kode_sbl', 'like', "%{$searchValue}%");
            })
            ->count();
    }

    // ==================== RKA / RINCIAN BELANJA ====================

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

    public function getPaketBelanjaList(int $idRinciSubBl, int $tipePaket, ?string $jenisBl = null)
    {
        $query = DB::table('data_rka')
            ->select(
                'id',
                'subtitle_teks as uraian_paket',
                'is_paket',
                'kode_akun',
                'nama_akun',
                'jenis_bl',
                'createddate',
                'createdtime'
            )
            ->where('id_rinci_sub_bl', $idRinciSubBl)
            ->where('tahun_anggaran', self::TAHUN_ANGGARAN_DEFAULT)
            ->where('active', 1)
            ->where('is_paket', $tipePaket)
            ->whereNotNull('subtitle_teks')
            ->where('subtitle_teks', '!=', '');

        if ($jenisBl) {
            $query->where('jenis_bl', $jenisBl);
        }

        return $query
            ->orderBy('id', 'desc')
            ->distinct()
            ->get()
            ->unique('subtitle_teks')
            ->values();
    }

    public function getPaketBelanjaById(int $id)
    {
        return DB::table('data_rka')
            ->where('id', $id)
            ->first();
    }

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
            'pajak_murni' => 0
        ]);
    }

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
            'update_at' => now()
        ]);
    }
}
