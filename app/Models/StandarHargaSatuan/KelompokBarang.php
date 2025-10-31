<?php

namespace App\Models\StandarHargaSatuan;

use Illuminate\Database\Eloquent\Model;

class KelompokBarang extends Model
{
    protected $table = 'kelompok_standar_harga';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'kode_kelompok_standar_harga',
        'nama_kelompok_standar_harga',
        'tipe_kelompok'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship dengan Standar Harga
    public function standarHarga()
    {
        return $this->hasMany(StandarHarga::class, 'id_kelompok_standar_harga');
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

    // Scope untuk filter berdasarkan tipe
    public function scopeByTipe($query, $tipe)
    {
        return $query->where('tipe_kelompok', $tipe);
    }
}
