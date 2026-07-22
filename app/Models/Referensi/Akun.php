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
    public $incrementing = true;

    protected $fillable = [
        'id_akun',
        'belanja',
        'is_bagi_hasil',
        'is_bankeu_khusus',
        'is_bankeu_umum',
        'is_barjas',
        'is_bl',
        'is_bos',
        'is_btt',
        'is_bunga',
        'is_gaji_asn',
        'is_hibah_brg',
        'is_hibah_uang',
        'is_locked',
        'is_modal_tanah',
        'is_pembiayaan',
        'is_pendapatan',
        'is_sosial_brg',
        'is_sosial_uang',
        'is_subsidi',
        'kode_akun',
        'nama_akun',
        'set_input',
        'set_lokus',
        'ket_akun',
        'kode_akun_lama',
        'kode_akun_revisi',
        'kunci_tahun',
        'level',
        'mulai_tahun',
        'pembiayaan',
        'pendapatan',
        'set_kab_kota',
        'set_prov',
        'status',
        'active',
        'update_at',
        'tahun_anggaran'
    ];

    protected $casts = [
        'is_bagi_hasil' => 'boolean',
        'is_bankeu_khusus' => 'boolean',
        'is_bankeu_umum' => 'boolean',
        'is_barjas' => 'boolean',
        'is_bl' => 'boolean',
        'is_bos' => 'boolean',
        'is_btt' => 'boolean',
        'is_bunga' => 'boolean',
        'is_gaji_asn' => 'boolean',
        'is_hibah_brg' => 'boolean',
        'is_hibah_uang' => 'boolean',
        'is_locked' => 'boolean',
        'is_modal_tanah' => 'boolean',
        'is_pembiayaan' => 'boolean',
        'is_pendapatan' => 'boolean',
        'is_sosial_brg' => 'boolean',
        'is_sosial_uang' => 'boolean',
        'is_subsidi' => 'boolean',
        'set_input' => 'boolean',
        'set_lokus' => 'boolean',
        'kunci_tahun' => 'boolean',
        'set_kab_kota' => 'boolean',
        'set_prov' => 'boolean',
        'active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'update_at' => 'datetime',
        'tahun_anggaran' => 'integer',
        'level' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        // Auto-sync text fields dari boolean flags
        static::creating(function ($model) {
            $model->syncTextFields();
        });

        // Auto-sync text fields sebelum update
        static::updating(function ($model) {
            $model->syncTextFields();
        });
    }

    protected function syncTextFields(): void
    {
        $this->pendapatan = $this->is_pendapatan ? 'ya' : 'tidak';
        $this->belanja = $this->is_bl ? 'ya' : 'tidak'; // Menggunakan is_bl
        $this->pembiayaan = $this->is_pembiayaan ? 'ya' : 'tidak';
    }

    public function getTipeAkunAttribute(): string
    {
        if ($this->is_pendapatan) return 'Pendapatan';
        if ($this->is_bl) return 'Belanja';
        if ($this->is_pembiayaan) return 'Pembiayaan';
        return 'Tidak Ditentukan';
    }

    public function getBadgeColorAttribute(): string
    {
        if ($this->is_pendapatan) return 'success';
        if ($this->is_bl) return 'warning';
        if ($this->is_pembiayaan) return 'info';
        return 'secondary';
    }

    public function getKeteranganAkunAttribute(): ?string
    {
        return $this->ket_akun;
    }

    public function setKeteranganAkunAttribute($value): void
    {
        $this->attributes['ket_akun'] = $value;
    }

    public function standarHarga()
    {
        return $this->belongsToMany(
            StandarHarga::class,
            'standar_harga_rekening_belanja',
            'id_akun',
            'id_standar_harga'
        )->withTimestamps();
    }

    public function scopeByKode($query, $kode)
    {
        return $query->where('kode_akun', 'like', "%{$kode}%");
    }

    public function scopeByNama($query, $nama)
    {
        return $query->where('nama_akun', 'like', "%{$nama}%");
    }

    public function scopeBelanja($query)
    {
        return $query->where('is_bl', 1);
    }

    public function scopePendapatan($query)
    {
        return $query->where('is_pendapatan', 1);
    }

    public function scopePembiayaan($query)
    {
        return $query->where('is_pembiayaan', 1);
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeByTahun($query, $tahun)
    {
        return $query->where('tahun_anggaran', $tahun);
    }

    public function scopeLocked($query)
    {
        return $query->where('is_locked', 1);
    }

    public function scopeBos($query)
    {
        return $query->where('is_bos', 1);
    }

    public function scopeGajiAsn($query)
    {
        return $query->where('is_gaji_asn', 1);
    }

    public function scopeBarjas($query)
    {
        return $query->where('is_barjas', 1);
    }

    public function scopeHibah($query)
    {
        return $query->where(function ($q) {
            $q->where('is_hibah_uang', 1)
                ->orWhere('is_hibah_brg', 1);
        });
    }

    public function scopeBansos($query)
    {
        return $query->where(function ($q) {
            $q->where('is_sosial_uang', 1)
                ->orWhere('is_sosial_brg', 1);
        });
    }

    public function scopeSearch($query, $search)
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('kode_akun', 'like', "%{$search}%")
                ->orWhere('nama_akun', 'like', "%{$search}%");
        });
    }

    public function isTipe(string $tipe): bool
    {
        $tipe = strtolower($tipe);

        switch ($tipe) {
            case 'pendapatan':
                return (bool) $this->is_pendapatan;
            case 'belanja':
                return (bool) $this->is_bl;
            case 'pembiayaan':
                return (bool) $this->is_pembiayaan;
            default:
                return false;
        }
    }

    public function getActiveTipes(): array
    {
        $tipes = [];

        if ($this->is_pendapatan) $tipes[] = 'Pendapatan';
        if ($this->is_bl) $tipes[] = 'Belanja';
        if ($this->is_pembiayaan) $tipes[] = 'Pembiayaan';

        return $tipes;
    }

    public function isProvinsi(): bool
    {
        return (bool) $this->set_prov;
    }

    public function isKabupatenKota(): bool
    {
        return (bool) $this->set_kab_kota;
    }

    public function isHibah(): bool
    {
        return (bool) $this->is_hibah_uang || (bool) $this->is_hibah_brg;
    }

    public function isBansos(): bool
    {
        return (bool) $this->is_sosial_uang || (bool) $this->is_sosial_brg;
    }

    public function getKategoriKhusus(): array
    {
        $kategori = [];

        if ($this->is_bos) $kategori[] = 'BOS';
        if ($this->is_gaji_asn) $kategori[] = 'Gaji ASN';
        if ($this->is_barjas) $kategori[] = 'Barang & Jasa';
        if ($this->is_btt) $kategori[] = 'BTT';
        if ($this->is_hibah_uang) $kategori[] = 'Hibah Uang';
        if ($this->is_hibah_brg) $kategori[] = 'Hibah Barang';
        if ($this->is_sosial_uang) $kategori[] = 'Bansos Uang';
        if ($this->is_sosial_brg) $kategori[] = 'Bansos Barang';
        if ($this->is_subsidi) $kategori[] = 'Subsidi';
        if ($this->is_bagi_hasil) $kategori[] = 'Bagi Hasil';
        if ($this->is_bunga) $kategori[] = 'Bunga';
        if ($this->is_modal_tanah) $kategori[] = 'Modal Tanah';

        return $kategori;
    }

    public function getFormattedKodeAkunAttribute(): string
    {
        if (empty($this->kode_akun)) {
            return '-';
        }
        return $this->kode_akun;
    }

    public function canEdit(): bool
    {
        return !$this->is_locked && $this->active;
    }

    public function canDelete(): bool
    {
        return !$this->is_locked && $this->active;
    }
}
