<?php

namespace App\Models\StandarHargaSatuan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Referensi\Akun;

class DataSsh extends Model
{
    protected $table = 'table_data_ssh';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'id_kelompok_standar_harga',
        'id_satuan',
        'kode_standar_harga',
        'nama_standar_harga',
        'spesifikasi',
        'harga',
        'tkdn',
        'is_active'
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'tkdn' => 'decimal:2',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi Many-to-One ke DataKelompokStandarHarga
    public function kelompokStandarHarga(): BelongsTo
    {
        return $this->belongsTo(DataKelompokStandarHarga::class, 'id_kelompok_standar_harga', 'id');
    }

    // Relasi Many-to-One ke DataSatuan
    public function satuan(): BelongsTo
    {
        return $this->belongsTo(DataSatuan::class, 'id_satuan', 'id');
    }

    // Relasi One-to-Many ke DataSshRekeningBelanja
    public function sshRekeningBelanja(): HasMany
    {
        return $this->hasMany(DataSshRekeningBelanja::class, 'id_ssh', 'id');
    }

    // Relasi Many-to-Many ke Akun melalui DataSshRekeningBelanja
    public function akun()
    {
        return $this->belongsToMany(
            Akun::class,
            'table_data_ssh_rekening_belanja',
            'id_ssh',
            'id_akun'
        )->withPivot('active', 'created_at', 'updated_at')
            ->withTimestamps()
            ->wherePivot('active', 1);
    }

    // Scope untuk data aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    // Scope untuk pencarian berdasarkan kode
    public function scopeByKode($query, $kode)
    {
        return $query->where('kode_standar_harga', 'like', "%{$kode}%");
    }

    // Scope untuk pencarian berdasarkan nama
    public function scopeByNama($query, $nama)
    {
        return $query->where('nama_standar_harga', 'like', "%{$nama}%");
    }

    // Scope untuk filter berdasarkan kelompok
    public function scopeByKelompok($query, $kelompokId)
    {
        return $query->where('id_kelompok_standar_harga', $kelompokId);
    }

    // Accessor untuk format harga
    public function getFormattedHargaAttribute()
    {
        return number_format($this->harga, 2, ',', '.');
    }

    // Accessor untuk format TKDN
    public function getFormattedTkdnAttribute()
    {
        return $this->tkdn ? $this->tkdn . '%' : '-';
    }
}
