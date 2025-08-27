<?php

namespace App\Models\Referensi;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table = 'program';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'kode',
        'id_bidang_urusan',
        'nama_program',
        'time_stamp'
    ];

    // Relasi Many-to-One ke BidangUrusan
    public function bidangUrusan()
    {
        return $this->belongsTo(BidangUrusan::class, 'id_bidang_urusan', 'id');
    }

    // Relasi One-to-Many ke Kegiatan
    public function kegiatan()
    {
        return $this->hasMany(Kegiatan::class, 'id_program', 'id');
    }
}
