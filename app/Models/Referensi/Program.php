<?php

namespace App\Models\Referensi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Program extends Model
{
    protected $table = 'program';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = false; // Non-auto incrementing ID

    protected $fillable = [
        'id',
        'kode_program',
        'id_bidang_urusan',
        'nama_program',
        'time_stamp'
    ];

    // Method untuk mendapatkan ID berikutnya
    public static function getNextId()
    {
        $maxId = DB::table('program')->max('id') ?? 0;
        return $maxId + 1;
    }

    // Override create method untuk otomatis set ID
    public static function create(array $attributes = [])
    {
        if (!isset($attributes['id'])) {
            $attributes['id'] = self::getNextId();
        }

        return static::query()->create($attributes);
    }

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
