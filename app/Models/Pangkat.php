<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pangkat extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'pangkat';

    // Kolom yang bisa diisi (mass assignable)
    protected $fillable = [
        'nama',
    ];

    // Laravel otomatis mengelola created_at & updated_at
    public $timestamps = true;
}
