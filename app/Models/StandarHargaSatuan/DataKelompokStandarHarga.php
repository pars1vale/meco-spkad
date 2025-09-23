<?php

namespace App\Models\StandarHargaSatuan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DataKelompokStandarHarga extends Model
{
    protected $table = 'table_data_kelompok_standar_harga';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'kode_kelompok_standar_harga',
        'nama_kelompok_standar_harga'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi One-to-Many ke DataSsh
    public function dataSsh(): HasMany
    {
        return $this->hasMany(DataSsh::class, 'id_kelompok_standar_harga', 'id');
    }

    // Scope untuk pencarian berdasarkan kode
    public function scopeByKode($query, $kode)
    {
        return $query->where('kode_kelompok_standar_harga', 'like', "%{$kode}%");
    }

    // Scope untuk pencarian berdasarkan nama
    public function scopeByNama($query, $nama)
    {
        return $query->where('nama_kelompok_standar_harga', 'like', "%{$nama}%");
    }
}
