<?php

namespace App\Models\Pendapatan;

use App\Models\Pengaturan\Profil\PerangkatDaerah\DataUnit;
use App\Models\Referensi\Akun;
use Illuminate\Database\Eloquent\Model;

class Pendapatan extends Model
{
    protected $table = 'data_pendapatan';

    protected $primaryKey = 'id';

    public $incrementing = true;

    public $timestamps = false;

    protected $fillable = [
        'created_user',
        'createddate',
        'createdtime',
        'updated_user',
        'updateddate',
        'updatedtime',
        'id_pendapatan',
        'id_skpd',
        'id_akun',
        'id_jadwal_murni',
        'program_koordinator',
        'skpd_koordinator',
        'urusan_koordinator',
        'kode_akun',
        'nama_akun',
        'rekening',
        'nilaimurni',
        'total',
        'pagu_fmis',
        'koefisien',
        'kua_murni',
        'kua_pak',
        'rkpd_murni',
        'rkpd_pak',
        'satuan',
        'volume',
        'keterangan',
        'uraian',
        'user1',
        'user2',
        'active',
        'update_at',
        'tahun_anggaran',
    ];

    protected $casts = [
        'nilaimurni' => 'double',
        'total' => 'double',
        'pagu_fmis' => 'double',
        'active' => 'integer',
        'tahun_anggaran' => 'integer',
        'update_at' => 'datetime',
    ];

    /**
     * Relasi ke Akun
     */
    public function akun()
    {
        return $this->belongsTo(Akun::class, 'id_akun', 'id');
    }

    /**
     * Relasi ke DataUnit (SKPD)
     */
    public function skpd()
    {
        return $this->belongsTo(DataUnit::class, 'id_skpd', 'id');
    }

    /**
     * Scope: hanya data aktif
     */
    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    /**
     * Scope: filter berdasarkan SKPD
     */
    public function scopeBySkpd($query, $idSkpd)
    {
        return $query->where('id_skpd', $idSkpd);
    }

    /**
     * Scope: filter berdasarkan tahun anggaran
     */
    public function scopeByTahun($query, $tahun)
    {
        return $query->where('tahun_anggaran', $tahun);
    }

    /**
     * Accessor: nama rekening lengkap (kode + nama dari akun)
     */
    public function getRekeningLengkapAttribute(): string
    {
        if ($this->akun) {
            return $this->akun->kode_akun.' - '.$this->akun->nama_akun;
        }

        return $this->rekening ?? '-';
    }

    /**
     * Accessor: format nilaimurni
     */
    public function getNilaiMurniFormattedAttribute(): string
    {
        return 'Rp '.number_format($this->nilaimurni ?? 0, 0, ',', '.');
    }

    /**
     * Accessor: format total
     */
    public function getTotalFormattedAttribute(): string
    {
        return 'Rp '.number_format($this->total ?? 0, 0, ',', '.');
    }
}
