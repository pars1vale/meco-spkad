<?php

namespace App\Models\StandarHargaSatuan;

use App\Models\Referensi\Akun;
use Illuminate\Database\Eloquent\Model;

class DataSSHRekBelanja extends Model
{
    protected $table = 'data_ssh_rek_belanja';

    protected $primaryKey = 'id';

    public $timestamps = false; // Karena menggunakan update_at custom

    protected $fillable = [
        'id_akun',
        'kode_akun',
        'nama_akun',
        'id_standar_harga',
        'active',
        'update_at',
        'tahun_anggaran',
    ];

    protected $casts = [
        'id_akun' => 'integer',
        'id_standar_harga' => 'integer',
        'active' => 'boolean',
        'tahun_anggaran' => 'integer',
        'update_at' => 'datetime',
    ];

    // Relationship dengan Data SSH
    public function dataSSH()
    {
        return $this->belongsTo(DataSSH::class, 'id_standar_harga', 'id_standar_harga');
    }

    // Relationship dengan Akun (optional, jika perlu)
    public function akun()
    {
        return $this->belongsTo(Akun::class, 'id_akun', 'id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('active', 0);
    }

    public function scopeByStandarHarga($query, $idStandarHarga)
    {
        return $query->where('id_standar_harga', $idStandarHarga);
    }

    public function scopeByTahun($query, $tahun)
    {
        return $query->where('tahun_anggaran', $tahun);
    }

    // Helper method untuk toggle active
    public function toggleActive()
    {
        $this->active = ! $this->active;
        $this->update_at = now();

        return $this->save();
    }

    // Boot method untuk auto-set update_at
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->update_at = now();
        });

        static::updating(function ($model) {
            $model->update_at = now();
        });
    }
}
