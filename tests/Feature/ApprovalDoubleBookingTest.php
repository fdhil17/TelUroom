<?php

use App\Models\JadwalAkademik;
use App\Models\Reservasi;
use App\Models\Ruangan;
use App\Models\User;
use function Pest\Laravel\actingAs;

test('blocks SSC from approving if academic schedule suddenly conflicts', function () {
    $ssc = User::factory()->create(['role' => 'ssc']);
    $ruangan = Ruangan::factory()->create(['status' => 'aktif']);
    
    // 1. Mahasiswa buat reservasi (status menunggu_ssc)
    $reservasi = Reservasi::factory()->create([
        'ruangan_id' => $ruangan->id,
        'status' => 'menunggu_ssc',
        'tanggal_reservasi' => now()->addDays(2)->format('Y-m-d'),
        'jam_mulai' => '08:00',
        'jam_selesai' => '10:00',
    ]);

    // 2. Logistik tiba-tiba menambahkan Jadwal Kuliah di jam yang sama
    $hariMapping = [
        'Monday' => 'senin', 'Tuesday' => 'selasa', 'Wednesday' => 'rabu',
        'Thursday' => 'kamis', 'Friday' => 'jumat', 'Saturday' => 'sabtu', 'Sunday' => 'minggu'
    ];
    $hariReservasi = $hariMapping[now()->addDays(2)->format('l')];

    JadwalAkademik::factory()->create([
        'ruangan_id' => $ruangan->id,
        'hari' => $hariReservasi,
        'jam_mulai' => '08:00',
        'jam_selesai' => '10:00',
    ]);

    // 3. SSC mencoba approve
    $response = actingAs($ssc)->post(route('ssc.approval.process', $reservasi->id), [
        'action' => 'approve'
    ]);

    // 4. Pastikan gagal dengan pesan error validasi action
    $response->assertInvalid([
        'action' => 'Persetujuan gagal. Ruangan telah dialokasikan untuk jadwal kuliah.',
    ]);

    // Pastikan status tidak berubah
    expect($reservasi->fresh()->status)->toBe('menunggu_ssc');
});

test('blocks Logistik from approving if another reservation gets approved first', function () {
    $logistik = User::factory()->create(['role' => 'logistik']);
    $ruangan = Ruangan::factory()->create(['status' => 'aktif']);
    
    // 1. Mahasiswa A punya reservasi menunggu logistik
    $reservasiA = Reservasi::factory()->create([
        'ruangan_id' => $ruangan->id,
        'status' => 'menunggu_logistik',
        'tanggal_reservasi' => now()->addDays(3)->format('Y-m-d'),
        'jam_mulai' => '13:00',
        'jam_selesai' => '15:00',
    ]);

    // 2. Tiba-tiba Mahasiswa B yang mengajukan jam yang sama disetujui (simulasi race condition)
    Reservasi::factory()->create([
        'ruangan_id' => $ruangan->id,
        'status' => 'disetujui',
        'tanggal_reservasi' => now()->addDays(3)->format('Y-m-d'),
        'jam_mulai' => '13:00',
        'jam_selesai' => '15:00',
    ]);

    // 3. Logistik mencoba approve Mahasiswa A
    $response = actingAs($logistik)->post(route('logistik.approval.process', $reservasiA->id), [
        'action' => 'approve'
    ]);

    // 4. Pastikan gagal
    $response->assertInvalid([
        'action' => 'Persetujuan gagal. Ruangan telah disetujui atau sedang dalam proses untuk peminjaman lain.',
    ]);

    // Pastikan status tidak berubah
    expect($reservasiA->fresh()->status)->toBe('menunggu_logistik');
});
