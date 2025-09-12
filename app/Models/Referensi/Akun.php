<?php

namespace App\Models\Referensi;

use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'akun';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'kode_akun',
        'nama_akun',
        'keterangan_akun',
        'pendapatan',
        'belanja',
        'pembiayaan',
        'time_stamp',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'id' => 'integer',
        'time_stamp' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    /**
     * Get next available ID
     */
    public static function getNextId()
    {
        $maxId = self::max('id') ?? 0;
        return $maxId + 1;
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'kode_akun';
    }
}
