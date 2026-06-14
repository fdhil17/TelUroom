<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalAkademik extends Model
{
    use HasFactory;

    protected $fillable = [
        'ruangan_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'mata_kuliah',
        'dosen',
        'created_by',
    ];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
