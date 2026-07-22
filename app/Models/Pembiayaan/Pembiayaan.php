<?php

namespace App\Models\Pembiayaan;

use App\Models\Pengaturan\Profil\PerangkatDaerah\DataUnit;
use App\Models\Referensi\Akun;
use Illuminate\Database\Eloquent\Model;

class Pembiayaan extends Model
{
    protected $table = 'data_pembiayaan';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'created_user',
        'createddate',
        'createdtime',
        'id_pembiayaan',
        'keterangan',
        'kode_akun',
        'nama_akun',
        'nilaimurni',
        'program_koordinator',
        'rekening',
        'skpd_koordinator',
        'total',
        'pagu_fmis',
        'updated_user',
        'updateddate',
        'updatedtime',
        'uraian',
        'urusan_koordinator',
        'type',
        'user1',
        'user2',
        'id_skpd',
        'id_akun',
        'id_jadwal_murni',
        'koefisien',
        'kua_murni',
        'kua_pak',
        'rkpd_murni',
        'rkpd_pak',
        'satuan',
        'volume',
        'active',
        'update_at',
        'tahun_anggaran',
    ];

    public function akun()
    {
        return $this->belongsTo(Akun::class, 'id_akun', 'id_akun');
    }

    public function skpd()
    {
        return $this->belongsTo(DataUnit::class, 'id_skpd', 'id_skpd');
    }

    public function scopePengeluaran($query)
    {
        return $query->where('type', 'pengeluaran')->where('active', 1);
    }

    public function scopeTahun($query, $tahun)
    {
        return $query->where('tahun_anggaran', $tahun);
    }
}
