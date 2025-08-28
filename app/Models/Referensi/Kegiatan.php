<?php

namespace App\Models\Referensi;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $table = 'kegiatan';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'kode',
        'id_program',
        'nama_program',
        'time_stamp'
    ];

    // Relasi Many-to-One ke Program
    public function program()
    {
        return $this->belongsTo(Program::class, 'id_program', 'id');
    }

    // Relasi One-to-Many ke SubKegiatan
    public function subKegiatan()
    {
        return $this->hasMany(SubKegiatan::class, 'id_kegiatan', 'id');
    }
}
