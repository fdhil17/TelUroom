<?php

use App\Models\JadwalAkademik;
use App\Models\LogRuangan;
use App\Models\Reservasi;
use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
uses(Tests\TestCase::class, RefreshDatabase::class);

describe('Relasi Antar Model Database', function () {
    test('User memiliki banyak reservasi dan log ruangan', function () {
        $user = User::factory()->create();
        $ruangan = Ruangan::factory()->create();
        
        $reservasi = Reservasi::factory()->create(['user_id' => $user->id, 'ruangan_id' => $ruangan->id]);
        LogRuangan::create(['user_id' => $user->id, 'ruangan_id' => $ruangan->id, 'reservasi_id' => $reservasi->id, 'tanggal' => '2023-01-01', 'jam_mulai' => '08:00', 'jam_selesai' => '10:00', 'keperluan' => 'test', 'nim' => '123', 'prodi' => 'IF', 'status' => 'disetujui']);

        expect($user->reservasis)->toHaveCount(1)
            ->and($user->reservasis->first()->id)->toBe($reservasi->id);
    });

    test('User memiliki banyak jadwal akademik sebagai pembuat', function () {
        $user = User::factory()->create();
        $ruangan = Ruangan::factory()->create();
        
        $jadwal = JadwalAkademik::factory()->create(['created_by' => $user->id, 'ruangan_id' => $ruangan->id]);

        expect($user->jadwalAkademiks)->toHaveCount(1)
            ->and($user->jadwalAkademiks->first()->id)->toBe($jadwal->id);
    });

    test('Ruangan memiliki banyak reservasi, log ruangan, dan jadwal akademik', function () {
        $user = User::factory()->create();
        $ruangan = Ruangan::factory()->create();
        
        $reservasi = Reservasi::factory()->create(['user_id' => $user->id, 'ruangan_id' => $ruangan->id]);
        LogRuangan::create(['user_id' => $user->id, 'ruangan_id' => $ruangan->id, 'reservasi_id' => $reservasi->id, 'tanggal' => '2023-01-01', 'jam_mulai' => '08:00', 'jam_selesai' => '10:00', 'keperluan' => 'test', 'nim' => '123', 'prodi' => 'IF', 'status' => 'disetujui']);
        JadwalAkademik::factory()->create(['created_by' => $user->id, 'ruangan_id' => $ruangan->id]);

        expect($ruangan->reservasis)->toHaveCount(1)
            ->and($ruangan->jadwalAkademiks)->toHaveCount(1);
    });

    test('Reservasi dimiliki oleh User, Ruangan, sscApprover, logistikApprover, dan memiliki banyak log ruangan', function () {
        $user = User::factory()->create();
        $ruangan = Ruangan::factory()->create();
        $ssc = User::factory()->create(['role' => 'ssc']);
        $logistik = User::factory()->create(['role' => 'logistik']);
        
        $reservasi = Reservasi::factory()->create([
            'user_id' => $user->id, 
            'ruangan_id' => $ruangan->id,
            'approved_by_ssc' => $ssc->id,
            'approved_by_logistik' => $logistik->id,
        ]);
        
        $log = LogRuangan::create(['user_id' => $user->id, 'ruangan_id' => $ruangan->id, 'reservasi_id' => $reservasi->id, 'tanggal' => '2023-01-01', 'jam_mulai' => '08:00', 'jam_selesai' => '10:00', 'keperluan' => 'test', 'nim' => '123', 'prodi' => 'IF', 'status' => 'disetujui']);

        expect($reservasi->user->id)->toBe($user->id)
            ->and($reservasi->ruangan->id)->toBe($ruangan->id)
            ->and($reservasi->sscApprover->id)->toBe($ssc->id)
            ->and($reservasi->logistikApprover->id)->toBe($logistik->id);
    });

    test('JadwalAkademik dimiliki oleh Ruangan dan Pembuat (Creator)', function () {
        $user = User::factory()->create();
        $ruangan = Ruangan::factory()->create();
        
        $jadwal = JadwalAkademik::factory()->create(['created_by' => $user->id, 'ruangan_id' => $ruangan->id]);

        expect($jadwal->ruangan->id)->toBe($ruangan->id)
            ->and($jadwal->creator->id)->toBe($user->id);
    });

    test('LogRuangan dimiliki oleh Reservasi, Ruangan, dan User', function () {
        $user = User::factory()->create();
        $ruangan = Ruangan::factory()->create();
        $reservasi = Reservasi::factory()->create(['user_id' => $user->id, 'ruangan_id' => $ruangan->id]);
        
        $log = LogRuangan::create(['user_id' => $user->id, 'ruangan_id' => $ruangan->id, 'reservasi_id' => $reservasi->id, 'tanggal' => '2023-01-01', 'jam_mulai' => '08:00', 'jam_selesai' => '10:00', 'keperluan' => 'test', 'nim' => '123', 'prodi' => 'IF', 'status' => 'disetujui']);

        expect($log->user->id)->toBe($user->id)
            ->and($log->ruangan->id)->toBe($ruangan->id)
            ->and($log->reservasi->id)->toBe($reservasi->id);
    });
});
