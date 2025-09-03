<?php

namespace App\Models\Referensi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class BidangUrusan extends Model
{
    protected $table = 'bidang_urusan';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $incrementing = false; // Non-auto incrementing ID

    protected $fillable = [
        'id',
        'kode_bidang_urusan',
        'id_urusan',
        'nama_bidang_urusan',
        'time_stamp'
    ];

    // Method untuk mendapatkan ID berikutnya
    public static function getNextId()
    {
        $maxId = DB::table('bidang_urusan')->max('id') ?? 0;
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
