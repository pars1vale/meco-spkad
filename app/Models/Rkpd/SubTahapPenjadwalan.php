<?php

namespace App\Models\Rkpd;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubTahapPenjadwalan extends Model
{
    use HasFactory;

    protected $table = 'sub_tahap_penjadwalan';
    protected $primaryKey = 'id_sub_tahap';

    protected $fillable = [
        'id_tahap',
        'nama_sub_tahap',
    ];

    // Relasi: Sub Tahap milik satu Tahap
    public function tahap()
    {
        return $this->belongsTo(TahapPenjadwalan::class, 'id_tahap', 'id_tahap');
    }

    // Relasi: Sub Tahap punya banyak Jadwal RKPD
    public function jadwalRkpds()
    {
        return $this->hasMany(JadwalRkpd::class, 'id_sub_tahap', 'id_sub_tahap');
    }
}
