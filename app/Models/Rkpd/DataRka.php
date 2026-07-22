<?php

namespace App\Models\Rkpd;

use Illuminate\Database\Eloquent\Model;

class DataRka extends Model
{
    protected $table = 'data_rka';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'created_user',
        'createddate',
        'createdtime',
        'harga_satuan',
        'harga_satuan_murni',
        'id_daerah',
        'id_rinci_sub_bl',
        'id_standar_nfs',
        'is_locked',
        'jenis_bl',
        'ket_bl_teks',
        'substeks',
        'id_dana',
        'nama_dana',
        'is_paket',
        'kode_dana',
        'subtitle_teks',
        'kode_akun',
        'koefisien',
        'koefisien_murni',
        'lokus_akun_teks',
        'nama_akun',
        'nama_komponen',
        'spek_komponen',
        'satuan',
        'spek',
        'sat1',
        'sat2',
        'sat3',
        'sat4',
        'volum1',
        'volum2',
        'volum3',
        'volum4',
        'volume',
        'volume_murni',
        'subs_bl_teks',
        'total_harga',
        'rincian',
        'rincian_murni',
        'totalpajak',
        'pajak',
        'pajak_murni',
        'updated_user',
        'updateddate',
        'updatedtime',
        'user1',
        'user2',
        'active',
        'akun_locked',
        'ssh_locked',
        'update_at',
        'tahun_anggaran',
        'idbl',
        'idsubbl',
        'kode_bl',
        'kode_sbl',
        'id_prop_penerima',
        'id_camat_penerima',
        'id_kokab_penerima',
        'id_lurah_penerima',
        'id_penerima',
        'idkomponen',
        'idketerangan',
        'idsubtitle',
    ];

    protected $casts = [
        'harga_satuan' => 'double',
        'harga_satuan_murni' => 'double',
        'total_harga' => 'double',
        'rincian' => 'double',
        'rincian_murni' => 'double',
        'totalpajak' => 'double',
        'pajak' => 'double',
        'pajak_murni' => 'double',
        'idkomponen' => 'double',
        'id_standar_nfs' => 'integer',
        'is_locked' => 'integer',
        'is_paket' => 'integer',
        'active' => 'integer',
        'akun_locked' => 'integer',
        'ssh_locked' => 'integer',
        'update_at' => 'datetime',
    ];

    protected $attributes = [
        'active' => 1,
        'tahun_anggaran' => 2021,
    ];
}
