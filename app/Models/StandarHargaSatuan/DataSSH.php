<?php

namespace App\Models\StandarHargaSatuan;

use Illuminate\Database\Eloquent\Model;

class DataSSH extends Model
{
    protected $table = 'data_ssh';

    protected $primaryKey = 'id_standar_harga';

    public $incrementing = false; // Karena primary key bukan auto increment

    public $timestamps = true;

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'id_standar_harga',
        'id_unik',
        'id_kel_standar_harga',
        'kode_kel_standar_harga',
        'nama_kel_standar_harga',
        'tipe_standar_harga',
        'kode_standar_harga',
        'nama_standar_harga',
        'satuan',
        'harga',
        'spek',
        'nilai_tkdn',
        'ket_teks',
        'tahun',
        'id_daerah',
        'is_pdn',
        'is_locked',
    ];

    protected $casts = [
        'id_standar_harga' => 'integer',
        'id_kel_standar_harga' => 'integer',
        'harga' => 'decimal:2',
        'nilai_tkdn' => 'decimal:2',
        'tahun' => 'integer',
        'id_daerah' => 'integer',
        'is_pdn' => 'boolean',
        'is_locked' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship dengan Kelompok Satuan Harga
    public function kelompokSatuanHarga()
    {
        return $this->belongsTo(KelompokSatuanHarga::class, 'id_kel_standar_harga', 'id_kategori');
    }

    // Relationship dengan Rekening Belanja
    public function rekeningBelanja()
    {
        return $this->hasMany(DataSSHRekBelanja::class, 'id_standar_harga', 'id_standar_harga');
    }

    // Relationship dengan Rekening Belanja yang aktif
    public function rekeningBelanjaAktif()
    {
        return $this->hasMany(DataSSHRekBelanja::class, 'id_standar_harga', 'id_standar_harga')
            ->where('active', 1);
    }

    // Scopes untuk pencarian
    public function scopeByKode($query, $kode)
    {
        return $query->where('kode_standar_harga', 'like', "%{$kode}%");
    }

    public function scopeByNama($query, $nama)
    {
        return $query->where('nama_standar_harga', 'like', "%{$nama}%");
    }

    public function scopeByTipe($query, $tipe)
    {
        return $query->where('tipe_standar_harga', $tipe);
    }

    public function scopeByTahun($query, $tahun)
    {
        return $query->where('tahun', $tahun);
    }

    public function scopeByDaerah($query, $idDaerah)
    {
        return $query->where('id_daerah', $idDaerah);
    }

    public function scopeByKelompok($query, $idKelompok)
    {
        return $query->where('id_kel_standar_harga', $idKelompok);
    }

    public function scopeUnlocked($query)
    {
        return $query->where('is_locked', 0);
    }

    public function scopeLocked($query)
    {
        return $query->where('is_locked', 1);
    }

    public function scopePdn($query)
    {
        return $query->where('is_pdn', 1);
    }

    // Accessor untuk status lock
    public function getStatusLockTextAttribute()
    {
        return $this->is_locked ? 'Terkunci' : 'Tidak Terkunci';
    }

    // Accessor untuk status PDN
    public function getStatusPdnTextAttribute()
    {
        return $this->is_pdn ? 'Ya' : 'Tidak';
    }

    // Helper method untuk toggle lock
    public function toggleLock()
    {
        $this->is_locked = ! $this->is_locked;

        return $this->save();
    }

    // Helper untuk generate ID unik
    public static function generateIdUnik($tahun, $tipe, $idDaerah)
    {
        // Format: TAHUN-TIPE-DAERAH-RANDOM
        // Contoh: 2025-SSH-001-ABC123
        $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 6));

        return sprintf('%s-%s-%03d-%s', $tahun, $tipe, $idDaerah, $random);
    }

    // Helper untuk generate ID Standar Harga
    public static function getNextIdStandarHarga()
    {
        $maxId = self::max('id_standar_harga');

        return $maxId ? $maxId + 1 : 1;
    }
}
