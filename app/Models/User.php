<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'nim',
        'prodi',
        'email',
        'password',
        'role',
        'locale',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationship: Reservasi yang dibuat oleh mahasiswa
    public function reservasis()
    {
        return $this->hasMany(Reservasi::class);
    }

    // Relationship: Reservasi yang di-approve oleh SSC
    public function approvedSscReservasis()
    {
        return $this->hasMany(Reservasi::class, 'approved_by_ssc');
    }

    // Relationship: Reservasi yang di-approve oleh Logistik
    public function approvedLogistikReservasis()
    {
        return $this->hasMany(Reservasi::class, 'approved_by_logistik');
    }

    // Relationship: Jadwal akademik yang dibuat oleh logistik
    public function jadwalAkademiks()
    {
        return $this->hasMany(JadwalAkademik::class, 'created_by');
    }

    // Helper Role Check
    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }

    public function isSSC(): bool
    {
        return $this->role === 'ssc';
    }

    public function isLogistik(): bool
    {
        return $this->role === 'logistik';
    }
}
