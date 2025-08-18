<?php

namespace App\Models\Referensi;

use Illuminate\Database\Eloquent\Model;

class BidangUrusan extends Model
{
    protected $table = 'data_prog_keg';
    public $timestamps = false;

    protected $fillable = [
        'nama_urusan',
        'kode_bidang_urusan',
        'nama_bidang_urusan'
    ];
}
