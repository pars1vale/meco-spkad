<?php

namespace App\Models\StandarHargaSatuan;

use Illuminate\Database\Eloquent\Model;
use App\Models\Referensi\Akun;

class StandarHarga extends Model
{
    protected $table = 'standar_harga';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'kode_standar_harga',
        'tipe_standar_harga',
        'id_kelompok_standar_harga',
        'id_satuan',
        'nama_standar_harga',
        'spesifikasi',
        'harga',
        'nilai_tkdn',
        'is_pdn'
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'nilai_tkdn' => 'decimal:2',
        'is_pdn' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function kelompokStandarHarga()
    {
        return $this->belongsTo(KelompokBarang::class, 'id_kelompok_standar_harga');
    }

    public function satuan()
    {
        return $this->belongsTo(DataSatuan::class, 'id_satuan');
    }

    public function rekeningBelanja()
    {
        return $this->belongsToMany(
            Akun::class,
            'standar_harga_rekening_belanja',
            'id_standar_harga',
            'id_akun'
        )->withTimestamps();
    }

    // Scopes
    public function scopeByKode($query, $kode)
    {
        return $query->where('kode_standar_harga', 'like', "%{$kode}%");
    }

    public function scopeByNama($query, $nama)
    {
        return $query->where('nama_standar_harga', 'like', "%{$nama}%");
    }

    public function scopeByKelompok($query, $kelompokId)
    {
        return $query->where('id_kelompok_standar_harga', $kelompokId);
    }
}
