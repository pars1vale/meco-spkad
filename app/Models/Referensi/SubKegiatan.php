<?php

namespace App\Models\Referensi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SubKegiatan extends Model
{
    protected $table = 'sub_kegiatan';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = false; // Non-auto incrementing ID

    protected $fillable = [
        'id',
        'kode_sub_kegiatan',
        'id_kegiatan',
        'nama_sub_kegiatan',
        'time_stamp'
    ];

    // Method untuk mendapatkan ID berikutnya
    public static function getNextId()
    {
        $maxId = DB::table('sub_kegiatan')->max('id') ?? 0;
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

    // Relasi Many-to-One ke Kegiatan
    public function kegiatan()
    {
        return $this->belongsTo(Kegiatan::class, 'id_kegiatan', 'id');
    }
}
