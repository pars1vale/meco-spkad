<?php

namespace App\Models\Referensi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Kegiatan extends Model
{
    protected $table = 'kegiatan';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = false; // Non-auto incrementing ID

    protected $fillable = [
        'id',
        'kode_kegiatan',
        'id_program',
        'nama_kegiatan',
        'time_stamp'
    ];

    // Method untuk mendapatkan ID berikutnya
    public static function getNextId()
    {
        $maxId = DB::table('kegiatan')->max('id') ?? 0;
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
