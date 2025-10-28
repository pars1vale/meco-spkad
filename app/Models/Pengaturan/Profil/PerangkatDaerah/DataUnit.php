<?php

namespace App\Models\Pengaturan\Profil\PerangkatDaerah;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class DataUnit extends Model
{
     use HasFactory;

    protected $table = 'data_unit';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id_setup_unit',
        'id_unit',
        'is_skpd',
        'kode_skpd',
        'kunci_skpd',
        'nama_skpd',
        'posisi',
        'status',
        'id_skpd',
        'bidur_1',
        'bidur_2',
        'bidur_3',
        'idinduk',
        'ispendapatan',
        'isskpd',
        'kode_skpd_1',
        'kode_skpd_2',
        'kodeunit',
        'komisi',
        'namabendahara',
        'namakepala',
        'namaunit',
        'nipbendahara',
        'nipkepala',
        'pangkatkepala',
        'setupunit',
        'statuskepala',
        'mapping',
        'id_kecamatan',
        'id_strategi',
        'is_dpa_khusus',
        'is_ppkd',
        'set_input',
        'update_at',
        'tahun_anggaran',
        'active',
    ];
}
