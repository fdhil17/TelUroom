<?php

use App\Models\Ruangan;
use App\Models\User;
use function Pest\Laravel\actingAs;

test('blocks past time reservation for today', function () {
    $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
    $ruangan = Ruangan::factory()->create(['status' => 'aktif']);

    $now = now();
    $pastTime = $now->copy()->subHours(1)->format('H:i');

    $response = actingAs($mahasiswa)->post(route('mahasiswa.reservasi.store'), [
        'ruangan_id' => $ruangan->id,
        'tanggal_reservasi' => $now->format('Y-m-d'),
        'jam_mulai' => $pastTime,
        'jam_selesai' => $now->copy()->addHours(1)->format('H:i'),
        'keperluan' => 'Rapat BEM',
    ]);

    $response->assertInvalid([
        'jam_mulai' => 'Jam mulai peminjaman untuk hari ini tidak boleh kurang dari atau sama dengan waktu saat ini.',
    ]);
});

test('allows future time reservation for today', function () {
    $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
    $ruangan = Ruangan::factory()->create(['status' => 'aktif']);

    $now = now();
    $futureTimeStart = $now->copy()->addHours(1)->format('H:i');
    $futureTimeEnd = $now->copy()->addHours(2)->format('H:i');

    $response = actingAs($mahasiswa)->post(route('mahasiswa.reservasi.store'), [
        'ruangan_id' => $ruangan->id,
        'tanggal_reservasi' => $now->format('Y-m-d'),
        'jam_mulai' => $futureTimeStart,
        'jam_selesai' => $futureTimeEnd,
        'keperluan' => 'Rapat BEM',
    ]);

    // Asumsi redirect ke halaman index dengan success
    $response->assertValid('jam_mulai');
});

test('allows any time reservation for future dates', function () {
    $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
    $ruangan = Ruangan::factory()->create(['status' => 'aktif']);

    $tomorrow = now()->addDay()->format('Y-m-d');
    $pastTimeForTomorrow = now()->copy()->subHours(1)->format('H:i'); // waktu lampau hari ini, tapi besok

    $response = actingAs($mahasiswa)->post(route('mahasiswa.reservasi.store'), [
        'ruangan_id' => $ruangan->id,
        'tanggal_reservasi' => $tomorrow,
        'jam_mulai' => $pastTimeForTomorrow,
        'jam_selesai' => now()->copy()->addHours(2)->format('H:i'),
        'keperluan' => 'Belajar Kelompok',
    ]);

    $response->assertValid('jam_mulai');
});
