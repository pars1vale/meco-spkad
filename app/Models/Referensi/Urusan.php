<?php

namespace App\Models\Referensi;

use Illuminate\Database\Eloquent\Model;

class Urusan extends Model
{
    protected $table = 'urusan';
    public $timestamps = true;
    protected $fillable = ['id', 'kode_urusan', 'nama_urusan'];
}
