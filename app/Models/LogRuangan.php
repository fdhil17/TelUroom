<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogRuangan extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservasi_id',
        'ruangan_id',
        'user_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'keperluan',
        'nim',
        'prodi',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class);
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
