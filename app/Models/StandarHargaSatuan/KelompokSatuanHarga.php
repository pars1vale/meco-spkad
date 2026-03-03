<?php

namespace App\Models\StandarHargaSatuan;

use Illuminate\Database\Eloquent\Model;

class KelompokSatuanHarga extends Model
{
    protected $table = 'data_kelompok_satuan_harga';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'id_kategori',
        'kode_kategori',
        'uraian_kategori',
        'tipe_kelompok',
        'active',
        'tahun_anggaran',
    ];

    protected $casts = [
        'active' => 'boolean',
        'tahun_anggaran' => 'integer',
        'id_kategori' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship dengan Data SSH (jika ada)
    // public function dataSSH()
    // {
    //     return $this->hasMany(DataSSH::class, 'id_kategori', 'id_kategori');
    // }

    // Scopes untuk pencarian
    public function scopeByKode($query, $kode)
    {
        return $query->where('kode_kategori', 'like', "%{$kode}%");
    }

    public function scopeByUraian($query, $uraian)
    {
        return $query->where('uraian_kategori', 'like', "%{$uraian}%");
    }

    public function scopeByTipe($query, $tipe)
    {
        return $query->where('tipe_kelompok', $tipe);
    }

    public function scopeByTahun($query, $tahun)
    {
        return $query->where('tahun_anggaran', $tahun);
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('active', 0);
    }

    // Accessor untuk status
    public function getStatusTextAttribute()
    {
        return $this->active ? 'Aktif' : 'Tidak Aktif';
    }

    // Helper method untuk toggle active
    public function toggleActive()
    {
        $this->active = ! $this->active;

        return $this->save();
    }
}
