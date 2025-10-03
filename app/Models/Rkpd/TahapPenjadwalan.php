<?php

namespace App\Models\Rkpd;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahapPenjadwalan extends Model
{
    use HasFactory;

    protected $table = 'tahap_penjadwalan';
    protected $primaryKey = 'id_tahap';

    protected $fillable = [
        'nama_tahap',
    ];

    // Relasi: Tahap punya banyak sub tahap
    public function subTahaps()
    {
        return $this->hasMany(SubTahapPenjadwalan::class, 'id_tahap', 'id_tahap');
    }
}
