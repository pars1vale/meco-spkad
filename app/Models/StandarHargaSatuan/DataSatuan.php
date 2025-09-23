<?php

namespace App\Models\StandarHargaSatuan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DataSatuan extends Model
{
    protected $table = 'table_data_satuan';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'nama_satuan'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi One-to-Many ke DataSsh
    public function dataSsh(): HasMany
    {
        return $this->hasMany(DataSsh::class, 'id_satuan', 'id');
    }

    // Scope untuk pencarian
    public function scopeByNama($query, $nama)
    {
        return $query->where('nama_satuan', 'like', "%{$nama}%");
    }
}
