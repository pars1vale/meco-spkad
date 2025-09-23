<?php

namespace App\Models\StandarHargaSatuan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Referensi\Akun;

class DataSshRekeningBelanja extends Model
{
    protected $table = 'table_data_ssh_rekening_belanja';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'id_ssh',
        'id_akun',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi Many-to-One ke DataSsh
    public function ssh(): BelongsTo
    {
        return $this->belongsTo(DataSsh::class, 'id_ssh', 'id');
    }

    // Relasi Many-to-One ke Akun
    public function akun(): BelongsTo
    {
        return $this->belongsTo(Akun::class, 'id_akun', 'id');
    }

    // Scope untuk data aktif
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    // Scope untuk filter berdasarkan SSH
    public function scopeBySsh($query, $sshId)
    {
        return $query->where('id_ssh', $sshId);
    }

    // Scope untuk filter berdasarkan akun
    public function scopeByAkun($query, $akunId)
    {
        return $query->where('id_akun', $akunId);
    }
}
