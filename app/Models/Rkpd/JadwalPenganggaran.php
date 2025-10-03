<?php

namespace App\Models\Rkpd;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalPenganggaran extends Model
{
    use HasFactory;

    protected $table = 'jadwal_penganggaran';
    protected $primaryKey = 'id_jadwal';
    public $incrementing = false; // karena PK = FK ke jadwal_rkpd

    protected $fillable = [
        'id_jadwal',
        'kua_murni',
        'kua_pak',
        'rollback_jadwal',
        'rollback_teks',
        'geser_khusus',
    ];

    // Relasi: Penganggaran milik satu jadwal RKPD
    public function jadwal()
    {
        return $this->belongsTo(JadwalRkpd::class, 'id_jadwal', 'id_jadwal');
    }
}
