<?php

namespace App\Models\Referensi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Urusan extends Model
{
    protected $table = 'urusan';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id',
        'kode_urusan',
        'nama_urusan',
        'time_stamp'
    ];

    // Method untuk mendapatkan ID berikutnya
    public static function getNextId()
    {
        $maxId = DB::table('urusan')->max('id') ?? 0;
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

    // Relasi One-to-Many ke BidangUrusan
    public function bidangUrusan()
    {
        return $this->hasMany(BidangUrusan::class, 'id_urusan', 'id');
    }
}
