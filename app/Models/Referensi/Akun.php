<?php

namespace App\Models\Referensi;

use Illuminate\Database\Eloquent\Model;
use App\Models\StandarHargaSatuan\StandarHarga;
use Illuminate\Support\Facades\DB;

class Akun extends Model
{
    protected $table = 'akun';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false; // Karena kita handle ID secara manual

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

    /**
     * Boot method untuk model
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate ID sebelum create
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = self::getNextId();
            }

            // Auto-sync text fields from boolean flags
            $model->syncTextFields();
        });

        // Auto-sync text fields sebelum update
        static::updating(function ($model) {
            $model->syncTextFields();
        });
    }

    /**
     * Mendapatkan ID berikutnya secara manual
     */
    public static function getNextId(): int
    {
        $maxId = DB::table('akun')->max('id');
        return $maxId ? $maxId + 1 : 1;
    }

    /**
     * Sinkronisasi field teks berdasarkan flag boolean
     * FIXED: Menggunakan 'Ya'/'Tidak' secara konsisten
     */
    protected function syncTextFields(): void
    {
        $this->pendapatan = $this->is_pendapatan ? 'Ya' : 'Tidak';
        $this->belanja = $this->is_belanja ? 'Ya' : 'Tidak';
        $this->pembiayaan = $this->is_pembiayaan ? 'Ya' : 'Tidak';
    }

    /**
     * Accessor untuk tipe akun
     */
    public function getTipeAkunAttribute(): string
    {
        if ($this->is_pendapatan) return 'Pendapatan';
        if ($this->is_belanja) return 'Belanja';
        if ($this->is_pembiayaan) return 'Pembiayaan';
        return 'Tidak Ditentukan';
    }

    /**
     * Accessor untuk warna badge berdasarkan tipe akun
     */
    public function getBadgeColorAttribute(): string
    {
        if ($this->is_pendapatan) return 'success';
        if ($this->is_belanja) return 'warning';
        if ($this->is_pembiayaan) return 'info';
        return 'secondary';
    }

    /**
     * Relationship dengan Standar Harga
     */
    public function standarHarga()
    {
        return $this->belongsToMany(
            StandarHarga::class,
            'standar_harga_rekening_belanja',
            'id_akun',
            'id_standar_harga'
        )->withTimestamps();
    }

    /**
     * Scope: Filter by kode akun
     */
    public function scopeByKode($query, $kode)
    {
        return $query->where('kode_akun', 'like', "%{$kode}%");
    }

    /**
     * Scope: Filter by nama akun
     */
    public function scopeByNama($query, $nama)
    {
        return $query->where('nama_akun', 'like', "%{$nama}%");
    }

    /**
     * Scope: Filter akun belanja
     */
    public function scopeBelanja($query)
    {
        return $query->where('is_belanja', 1);
    }

    /**
     * Scope: Filter akun pendapatan
     */
    public function scopePendapatan($query)
    {
        return $query->where('is_pendapatan', 1);
    }

    /**
     * Scope: Filter akun pembiayaan
     */
    public function scopePembiayaan($query)
    {
        return $query->where('is_pembiayaan', 1);
    }

    /**
     * Scope: Filter akun aktif (memiliki minimal satu tipe)
     */
    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $q->where('is_pendapatan', 1)
                ->orWhere('is_belanja', 1)
                ->orWhere('is_pembiayaan', 1);
        });
    }

    /**
     * Check apakah akun memiliki tipe tertentu
     */
    public function isTipe(string $tipe): bool
    {
        $tipe = strtolower($tipe);

        switch ($tipe) {
            case 'pendapatan':
                return (bool) $this->is_pendapatan;
            case 'belanja':
                return (bool) $this->is_belanja;
            case 'pembiayaan':
                return (bool) $this->is_pembiayaan;
            default:
                return false;
        }
    }

    /**
     * Get all tipe akun yang aktif
     */
    public function getActiveTipes(): array
    {
        $tipes = [];

        if ($this->is_pendapatan) $tipes[] = 'Pendapatan';
        if ($this->is_belanja) $tipes[] = 'Belanja';
        if ($this->is_pembiayaan) $tipes[] = 'Pembiayaan';

        return $tipes;
    }
}
