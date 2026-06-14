<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'kode_ruangan',
        'nama_ruangan',
        'lantai',
        'kapasitas',
        'status',
    ];

    public function jadwalAkademiks()
    {
        return $this->hasMany(JadwalAkademik::class);
    }

    public function reservasis()
    {
        return $this->hasMany(Reservasi::class);
    }
}
