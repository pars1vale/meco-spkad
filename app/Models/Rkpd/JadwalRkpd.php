<?php

namespace App\Models\Rkpd;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalRkpd extends Model
{
    use HasFactory;

    protected $table = 'jadwal_rkpd';
    protected $primaryKey = 'id_jadwal';

    protected $fillable = [
        'id_unik',
        'tahun',
        'id_daerah',
        'id_sub_tahap',
        'waktu_mulai',
        'waktu_selesai',
        'is_perubahan',
        'id_jadwal_murni',
        'is_pembahasan',
        'id_jadwal_pembahasan',
        'is_locked',
        'is_public',
        'is_rinci_bl',
        'id_sub_rkpd',
        'no_registrasi',
        'no_perda',
        'tgl_perda',
        'no_perkada',
        'tgl_perkada',
        'tgl_rka',
        'tandai_jadwal',
        'id_jadwal_rpjmd',
        'rkpd_murni',
        'rkpd_pak',
        'created_user',
        'updated_user',
    ];

    // Relasi: Jadwal RKPD milik Sub Tahap
    public function subTahap()
    {
        return $this->belongsTo(SubTahapPenjadwalan::class, 'id_sub_tahap', 'id_sub_tahap');
    }

    // Relasi: Jadwal RKPD (versi perubahan) → ke jadwal murninya
    public function jadwalMurni()
    {
        return $this->belongsTo(JadwalRkpd::class, 'id_jadwal_murni', 'id_jadwal');
    }

    // Relasi: Jadwal RKPD punya versi perubahan
    public function perubahan()
    {
        return $this->hasMany(JadwalRkpd::class, 'id_jadwal_murni', 'id_jadwal');
    }

    // Relasi: Jadwal RKPD bisa punya detail penganggaran
    public function penganggaran()
    {
        return $this->hasOne(JadwalPenganggaran::class, 'id_jadwal', 'id_jadwal');
    }
}
