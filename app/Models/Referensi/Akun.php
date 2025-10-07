<?php

namespace App\Models\Referensi;

use Illuminate\Database\Eloquent\Model;
use App\Models\StandarHargaSatuan\StandarHarga;

class Akun extends Model
{
    protected $table = 'akun';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'kode_akun',
        'nama_akun',
        'keterangan_akun',
        'is_pendapatan',
        'is_belanja',
        'is_pembiayaan',
        'pendapatan',
        'belanja',
        'pembiayaan'
    ];

    protected $casts = [
        'is_pendapatan' => 'boolean',
        'is_belanja' => 'boolean',
        'is_pembiayaan' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship dengan Standar Harga
    public function standarHarga()
    {
        return $this->belongsToMany(
            StandarHarga::class,
            'standar_harga_rekening_belanja',
            'id_akun',
            'id_standar_harga'
        )->withTimestamps();
    }

    // Scopes
    public function scopeByKode($query, $kode)
    {
        return $query->where('kode_akun', 'like', "%{$kode}%");
    }

    public function scopeByNama($query, $nama)
    {
        return $query->where('nama_akun', 'like', "%{$nama}%");
    }

    public function scopeBelanja($query)
    {
        return $query->where('is_belanja', 1);
    }

    public function scopePendapatan($query)
    {
        return $query->where('is_pendapatan', 1);
    }

    public function scopePembiayaan($query)
    {
        return $query->where('is_pembiayaan', 1);
    }
}
