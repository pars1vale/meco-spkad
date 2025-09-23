<?php

namespace App\Models\Referensi;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use App\Models\StandarHargaSatuan\DataSsh;
use App\Models\StandarHargaSatuan\DataSshRekeningBelanja;

class Akun extends Model
{
    protected $table = 'akun';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'id',
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

    // Method untuk mendapatkan ID berikutnya
    public static function getNextId()
    {
        $maxId = DB::table('akun')->max('id') ?? 0;
        return $maxId + 1;
    }

    // Override create method untuk otomatis set ID
    public static function create(array $attributes = [])
    {
        if (!isset($attributes['id'])) {
            $attributes['id'] = self::getNextId();
        }

        return static::query()->create($attributes);
    }

    // Relasi One-to-Many ke DataSshRekeningBelanja
    public function sshRekeningBelanja(): HasMany
    {
        return $this->hasMany(DataSshRekeningBelanja::class, 'id_akun', 'id');
    }

    // Relasi Many-to-Many ke DataSsh melalui DataSshRekeningBelanja
    public function dataSsh()
    {
        return $this->belongsToMany(
            dataSsh::class,
            'table_data_ssh_rekening_belanja',
            'id_akun',
            'id_ssh'
        )->withPivot('active', 'created_at', 'updated_at')
            ->withTimestamps()
            ->wherePivot('active', 1);
    }

    // Scope untuk akun belanja
    public function scopeBelanja($query)
    {
        return $query->where('is_belanja', 1);
    }

    // Scope untuk akun pendapatan
    public function scopePendapatan($query)
    {
        return $query->where('is_pendapatan', 1);
    }

    // Scope untuk akun pembiayaan
    public function scopePembiayaan($query)
    {
        return $query->where('is_pembiayaan', 1);
    }

    // Scope untuk pencarian berdasarkan kode
    public function scopeByKode($query, $kode)
    {
        return $query->where('kode_akun', 'like', "%{$kode}%");
    }

    // Scope untuk pencarian berdasarkan nama
    public function scopeByNama($query, $nama)
    {
        return $query->where('nama_akun', 'like', "%{$nama}%");
    }
}
