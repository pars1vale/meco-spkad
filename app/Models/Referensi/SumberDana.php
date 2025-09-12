<?php

namespace App\Models\Referensi;

use Illuminate\Database\Eloquent\Model;

class SumberDana extends Model
{
    protected $table = 'sumber_dana';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'kode_dana',
        'nama_dana',
        'sumber_dana',
        'time_stamp',
        'updated_at'
    ];

    protected $casts = [
        'time_stamp' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public static function getNextId()
    {
        $maxId = self::max('id');
        return $maxId ? $maxId + 1 : 1;
    }

    public function scopeByKode($query, $kode)
    {
        return $query->where('kode_dana', 'like', '%' . $kode . '%');
    }

    public function scopeByNama($query, $nama)
    {
        return $query->where('nama_dana', 'like', '%' . $nama . '%');
    }

    public function getFormattedTimeStampAttribute()
    {
        return $this->time_stamp ? $this->time_stamp->format('d/m/Y H:i') : '-';
    }

    public function getFormattedUpdatedAtAttribute()
    {
        return $this->updated_at ? $this->updated_at->format('d/m/Y H:i') : '-';
    }

    public function getShortSumberDanaAttribute($length = 100)
    {
        if (!$this->sumber_dana) {
            return '-';
        }

        return strlen($this->sumber_dana) > $length
            ? substr($this->sumber_dana, 0, $length) . '...'
            : $this->sumber_dana;
    }

    public static function isKodeUnique($kode, $excludeId = null)
    {
        $query = self::where('kode_dana', $kode);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->doesntExist();
    }

    protected static function boot()
    {
        parent::boot();

        // Auto set timestamps on creating
        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = self::getNextId();
            }

            if (!$model->time_stamp) {
                $model->time_stamp = now();
            }

            if (!$model->updated_at) {
                $model->updated_at = now();
            }
        });

        // Auto set updated_at on updating
        static::updating(function ($model) {
            $model->updated_at = now();
        });
    }
}
