<?php

use App\Models\Reservasi;
use App\Models\Ruangan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
uses(Tests\TestCase::class, RefreshDatabase::class);

describe('Mahasiswa Reservasi (EP & Boundary Value)', function () {
    beforeEach(function () {
        $this->mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $this->ruangan = Ruangan::factory()->create(['status' => 'tersedia']);
    });

    test('memungkinkan mahasiswa untuk melihat ruangan yang tersedia', function () {
        $response = $this->actingAs($this->mahasiswa)
            ->get(route('mahasiswa.ruangan.index'));

        $response->assertStatus(200);
        $response->assertViewHas('ruangans');
    });

    test('mencegah peminjaman dengan tanggal yang tidak valid (Tanggal Lampau - EP Tidak Valid)', function () {
        $kemarin = Carbon::now()->subDay()->format('Y-m-d');

        $response = $this->actingAs($this->mahasiswa)
            ->post(route('mahasiswa.reservasi.store'), [
                'ruangan_id' => $this->ruangan->id,
                'tanggal_reservasi' => $kemarin,
                'jam_mulai' => '10:00',
                'jam_selesai' => '12:00',
                'keperluan' => 'Rapat himpunan',
            ]);

        $response->assertSessionHasErrors(['tanggal_reservasi']);
    });

    test('mencegah peminjaman dengan durasi waktu yang tidak valid (Mulai >= Selesai - BVA Tidak Valid)', function () {
        $besok = Carbon::now()->addDay()->format('Y-m-d');

        $response = $this->actingAs($this->mahasiswa)
            ->post(route('mahasiswa.reservasi.store'), [
                'ruangan_id' => $this->ruangan->id,
                'tanggal_reservasi' => $besok,
                'jam_mulai' => '10:00',
                'jam_selesai' => '09:59',
                'keperluan' => 'Rapat',
            ]);

        $response->assertSessionHasErrors(['jam_selesai']);
    });

    test('berhasil membuat reservasi dan mengatur status ke menunggu_ssc (EP Valid & Initial State)', function () {
        $besok = Carbon::now()->addDay()->format('Y-m-d');

        $response = $this->actingAs($this->mahasiswa)
            ->post(route('mahasiswa.reservasi.store'), [
                'ruangan_id' => $this->ruangan->id,
                'tanggal_reservasi' => $besok,
                'jam_mulai' => '10:00',
                'jam_selesai' => '12:00',
                'keperluan' => 'Belajar kelompok',
            ]);

        $response->assertRedirect(route('mahasiswa.reservasi.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reservasis', [
            'ruangan_id' => $this->ruangan->id,
            'user_id' => $this->mahasiswa->id,
            'keperluan' => 'Belajar kelompok',
            'status' => 'menunggu_ssc',
        ]);
    });
});

describe('Logistik Ruangan Controller', function () {
    beforeEach(function () {
        $this->logistik = User::factory()->create(['role' => 'logistik']);
        $this->ruangan = Ruangan::factory()->create();
    });

    test('index menampilkan daftar ruangan', function () {
        $response = $this->actingAs($this->logistik)
            ->get(route('logistik.ruangan.index'));

        $response->assertStatus(200);
        $response->assertViewIs('logistik.ruangan.index');
        $response->assertViewHas('ruangans');
    });

    test('create menampilkan form tambah ruangan', function () {
        $response = $this->actingAs($this->logistik)
            ->get(route('logistik.ruangan.create'));

        $response->assertStatus(200);
        $response->assertViewIs('logistik.ruangan.create');
    });

    test('store berhasil menambahkan ruangan (Happy Path)', function () {
        $response = $this->actingAs($this->logistik)
            ->post(route('logistik.ruangan.store'), [
                'kode_ruangan' => 'KU101',
                'nama_ruangan' => 'Ruang Kuliah Umum',
                'kapasitas' => 100,
                'lantai' => 1,
                'fasilitas' => 'AC',
                'status' => 'tersedia',
            ]);

        $response->assertRedirect(route('logistik.ruangan.index'));
        $response->assertSessionHas('success');
    });

    test('edit menampilkan form edit ruangan', function () {
        $ruangan = Ruangan::factory()->create();

        $response = $this->actingAs($this->logistik)
            ->get(route('logistik.ruangan.edit', $ruangan->id));

        $response->assertStatus(200);
        $response->assertViewIs('logistik.ruangan.edit');
        $response->assertViewHas('ruangan');
    });

    test('update berhasil mengubah data ruangan (Happy Path)', function () {
        $ruangan = Ruangan::factory()->create();

        $response = $this->actingAs($this->logistik)
            ->put(route('logistik.ruangan.update', $ruangan->id), [
                'kode_ruangan' => $ruangan->kode_ruangan,
                'nama_ruangan' => 'Baru',
                'kapasitas' => 50,
                'lantai' => 2,
                'fasilitas' => 'Proyektor',
                'status' => 'tersedia',
            ]);

        $response->assertRedirect(route('logistik.ruangan.index'));
        $response->assertSessionHas('success');
    });

    test('destroy berhasil menghapus ruangan jika tidak ada reservasi (Happy Path)', function () {
        $ruangan = Ruangan::factory()->create();

        $response = $this->actingAs($this->logistik)
            ->delete(route('logistik.ruangan.destroy', $ruangan->id));

        $response->assertRedirect(route('logistik.ruangan.index'));
        $response->assertSessionHas('success');
    });

    test('mencegah menghapus ruangan jika masih ada reservasi aktif yang melekat (Validation Error)', function () {
        $ruangan = Ruangan::factory()->create();
        
        $reservasi = Reservasi::factory()->create([
            'ruangan_id' => $ruangan->id,
            'status' => 'menunggu_ssc',
        ]);

        $response = $this->actingAs($this->logistik)
            ->delete(route('logistik.ruangan.destroy', $ruangan->id));

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('ruangans', ['id' => $ruangan->id]); // Memastikan ruang tidak terhapus
    });
});

describe('SSC Approval Controller', function () {
    beforeEach(function () {
        $this->ssc = User::factory()->create(['role' => 'ssc']);
        $this->ruangan = Ruangan::factory()->create(['status' => 'tersedia']);
    });

    test('index menampilkan daftar pengajuan untuk SSC', function () {
        $response = $this->actingAs($this->ssc)
            ->get(route('ssc.approval.index'));

        $response->assertStatus(200);
        $response->assertViewIs('ssc.approval.index');
        $response->assertViewHas('reservasis');
    });

    test('show menampilkan detail reservasi untuk SSC', function () {
        $reservasi = Reservasi::factory()->create([
            'ruangan_id' => $this->ruangan->id,
            'status' => 'menunggu_ssc',
        ]);

        $response = $this->actingAs($this->ssc)
            ->get(route('ssc.approval.show', $reservasi->id));

        $response->assertStatus(200);
        $response->assertViewIs('ssc.approval.show');
        $response->assertViewHas('reservasi');
    });
});

describe('Logistik Approval Controller', function () {
    beforeEach(function () {
        $this->logistik = User::factory()->create(['role' => 'logistik']);
        $this->ruangan = Ruangan::factory()->create(['status' => 'tersedia']);
    });

    test('index menampilkan daftar pengajuan untuk Logistik', function () {
        $response = $this->actingAs($this->logistik)
            ->get(route('logistik.approval.index'));

        $response->assertStatus(200);
        $response->assertViewIs('logistik.approval.index');
        $response->assertViewHas('reservasis');
    });

    test('show menampilkan detail reservasi untuk Logistik', function () {
        $reservasi = Reservasi::factory()->create([
            'ruangan_id' => $this->ruangan->id,
            'status' => 'menunggu_logistik',
        ]);

        $response = $this->actingAs($this->logistik)
            ->get(route('logistik.approval.show', $reservasi->id));

        $response->assertStatus(200);
        $response->assertViewIs('logistik.approval.show');
        $response->assertViewHas('reservasi');
    });
});

describe('Transisi Status Persetujuan', function () {
    beforeEach(function () {
        $this->mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $this->ssc = User::factory()->create(['role' => 'ssc']);
        $this->logistik = User::factory()->create(['role' => 'logistik']);
        $this->ruangan = Ruangan::factory()->create(['status' => 'tersedia']);
        
        $this->reservasi = Reservasi::factory()->create([
            'user_id' => $this->mahasiswa->id,
            'ruangan_id' => $this->ruangan->id,
            'status' => 'menunggu_ssc',
        ]);
    });

    test('mengubah status dari menunggu_ssc menjadi ditolak_ssc saat ditolak SSC', function () {
        $response = $this->actingAs($this->ssc)
            ->post(route('ssc.approval.process', $this->reservasi->id), [
                'action' => 'reject',
                'catatan_ssc' => 'Maaf penuh',
            ]);

        $this->assertDatabaseHas('reservasis', [
            'id' => $this->reservasi->id,
            'status' => 'ditolak_ssc',
        ]);

        $this->assertDatabaseHas('log_ruangans', [
            'reservasi_id' => $this->reservasi->id,
            'status' => 'ditolak_ssc',
        ]);
    });

    test('mengubah status dari menunggu_ssc menjadi menunggu_logistik saat disetujui SSC', function () {
        $response = $this->actingAs($this->ssc)
            ->post(route('ssc.approval.process', $this->reservasi->id), [
                'action' => 'approve',
            ]);

        $this->assertDatabaseHas('reservasis', [
            'id' => $this->reservasi->id,
            'status' => 'menunggu_logistik',
            'approved_by_ssc' => $this->ssc->id,
        ]);
    });

    test('mengubah status dari menunggu_logistik menjadi disetujui dan membuat QR saat disetujui Logistik', function () {
        $this->reservasi->update(['status' => 'menunggu_logistik']);

        $response = $this->actingAs($this->logistik)
            ->post(route('logistik.approval.process', $this->reservasi->id), [
                'action' => 'approve',
            ]);

        $this->assertDatabaseHas('reservasis', [
            'id' => $this->reservasi->id,
            'status' => 'disetujui',
            'approved_by_logistik' => $this->logistik->id,
        ]);

        $reservasiUpdated = Reservasi::find($this->reservasi->id);
        expect($reservasiUpdated->qr_code)->not->toBeNull();

        $this->assertDatabaseHas('log_ruangans', [
            'reservasi_id' => $this->reservasi->id,
            'status' => 'disetujui',
        ]);
    });

    test('mencegah logistik menyetujui reservasi yang masih berstatus menunggu_ssc (Transisi Salah)', function () {
        $response = $this->actingAs($this->logistik)
            ->post(route('logistik.approval.process', $this->reservasi->id), [
                'action' => 'approve',
            ]);

        $response->assertSessionHasErrors(['status']);

        $this->assertDatabaseHas('reservasis', [
            'id' => $this->reservasi->id,
            'status' => 'menunggu_ssc', // Status tetap
        ]);
    });
});
