<?php

namespace App\Models\Referensi;

use Illuminate\Database\Eloquent\Model;

class Urusan extends Model
{
    protected $table = 'urusan';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'kode',
        'nama_urusan',
        'time_stamp'
    ];

    // Relasi One-to-Many ke BidangUrusan
    public function bidangUrusan()
    {
        return $this->hasMany(BidangUrusan::class, 'id_urusan', 'id');
    }
}
