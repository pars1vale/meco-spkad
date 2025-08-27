<?php

namespace App\Models\Referensi;

use Illuminate\Database\Eloquent\Model;

class SubKegiatan extends Model
{
    protected $table = 'sub_kegiatan';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'kode',
        'id_kegiatan',
        'nama_program',
        'time_stamp'
    ];

    // Relasi Many-to-One ke Kegiatan
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'id_kegiatan', 'id');
    }
}
