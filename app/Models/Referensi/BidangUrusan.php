<?php

namespace App\Models\Referensi;

use Illuminate\Database\Eloquent\Model;

class BidangUrusan extends Model
{
    protected $table = 'bidang_urusan';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'kode',
        'id_urusan',
        'nama_bidang_urusan',
        'time_stamp'
    ];

    // Relasi Many-to-One ke Urusan
    public function urusan()
    {
        return $this->belongsTo(Urusan::class, 'id_urusan', 'id');
    }

    // Relasi One-to-Many ke Program
    public function program()
    {
        return $this->hasMany(Program::class, 'id_bidang_urusan', 'id');
    }
}
