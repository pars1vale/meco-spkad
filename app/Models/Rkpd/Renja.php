<?php

namespace App\Models\Rkpd;

use Illuminate\Database\Eloquent\Model;

class Renja extends Model
{

    /**
     * Nama tabel di database.
     */
    protected $table = 'data_sub_keg_bl';

    /**
     * Primary key tabel.
     */
    protected $primaryKey = 'id';

    /**
     * Apakah primary key auto increment?
     */
    public $incrementing = false;

    /**
     * Tipe data primary key.
     */
    protected $keyType = 'int';

    /**
     * Menonaktifkan timestamps default (created_at dan updated_at).
     */
    public $timestamps = false;

    /**
     * Kolom yang dapat diisi (mass assignable).
     */
    protected $fillable = [
        'id',
        'id_sub_skpd',
        'id_lokasi',
        'id_label_kokab',
        'nama_dana',
        'no_sub_giat',
        'kode_giat',
        'id_program',
        'nama_lokasi',
        'waktu_akhir',
        'pagu_n_lalu',
        'id_urusan',
        'id_unik_sub_bl',
        'id_sub_giat',
        'label_prov',
        'kode_program',
        'kode_sub_giat',
        'no_program',
        'kode_urusan',
        'kode_bidang_urusan',
        'nama_program',
        'target_4',
        'target_5',
        'id_bidang_urusan',
        'nama_bidang_urusan',
        'target_3',
        'no_giat',
        'id_label_prov',
        'waktu_awal',
        'pagumurni',
        'pagu',
        'pagu_simda',
        'output_sub_giat',
        'sasaran',
        'indikator',
        'id_dana',
        'nama_sub_giat',
        'pagu_n_depan',
        'satuan',
        'id_rpjmd',
        'id_giat',
        'id_label_pusat',
        'nama_giat',
        'kode_skpd',
        'nama_skpd',
        'kode_sub_skpd',
        'id_skpd',
        'id_sub_bl',
        'nama_sub_skpd',
        'target_1',
        'nama_urusan',
        'target_2',
        'label_kokab',
        'label_pusat',
        'pagu_keg',
        'pagu_fmis',
        'id_bl',
        'kode_bl',
        'kode_sbl',
        'active',
        'update_at',
        'tahun_anggaran',
    ];
}
