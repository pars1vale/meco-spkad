<?php

namespace App\Models\StandarHargaSatuan;

use Illuminate\Database\Eloquent\Model;

class DataSatuan extends Model
{
    protected $table = 'data_satuan';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'nama_satuan'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Scope untuk pencarian
    public function scopeByNama($query, $nama)
    {
        return $query->where('nama_satuan', 'like', "%{$nama}%");
    }
}
