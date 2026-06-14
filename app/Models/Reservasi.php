<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ruangan_id',
        'tanggal_reservasi',
        'jam_mulai',
        'jam_selesai',
        'keperluan',
        'status',
        'catatan_ssc',
        'catatan_logistik',
        'approved_by_ssc',
        'approved_by_logistik',
        'qr_code',
    ];

    protected $casts = [
        'tanggal_reservasi' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class);
    }

    public function sscApprover()
    {
        return $this->belongsTo(User::class, 'approved_by_ssc');
    }

    public function logistikApprover()
    {
        return $this->belongsTo(User::class, 'approved_by_logistik');
    }

    // Helper status check
    public function isMenungguSsc(): bool
    {
        return $this->status === 'menunggu_ssc';
    }

    public function isMenungguLogistik(): bool
    {
        return $this->status === 'menunggu_logistik';
    }

    public function isDisetujui(): bool
    {
        return $this->status === 'disetujui';
    }
}
