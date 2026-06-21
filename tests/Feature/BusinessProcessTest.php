<?php

use App\Models\Reservasi;
use App\Models\Ruangan;
use App\Models\User;
use App\Models\JadwalAkademik;
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

describe('Reservasi Validation', function () {
    beforeEach(function () {
        $this->mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $this->ruangan = Ruangan::factory()->create(['status' => 'tersedia']);
    });

    test('mencegah reservasi dengan waktu lampau untuk hari ini', function () {
        Carbon::setTestNow(Carbon::create(2023, 1, 1, 10, 0, 0));
        $now = now();
        $pastTime = $now->copy()->subHours(1)->format('H:i');

        $response = $this->actingAs($this->mahasiswa)->post(route('mahasiswa.reservasi.store'), [
            'ruangan_id' => $this->ruangan->id,
            'tanggal_reservasi' => $now->format('Y-m-d'),
            'jam_mulai' => $pastTime,
            'jam_selesai' => $now->copy()->addHours(1)->format('H:i'),
            'keperluan' => 'Rapat BEM',
        ]);

        $response->assertInvalid([
            'jam_mulai' => 'Jam mulai peminjaman untuk hari ini tidak boleh kurang dari atau sama dengan waktu saat ini.',
        ]);
        Carbon::setTestNow();
    });

    test('mengizinkan reservasi dengan waktu yang akan datang untuk hari ini', function () {
        Carbon::setTestNow(Carbon::create(2023, 1, 1, 10, 0, 0));
        $now = now();
        $futureTimeStart = $now->copy()->addHours(1)->format('H:i');
        $futureTimeEnd = $now->copy()->addHours(2)->format('H:i');

        $response = $this->actingAs($this->mahasiswa)->post(route('mahasiswa.reservasi.store'), [
            'ruangan_id' => $this->ruangan->id,
            'tanggal_reservasi' => $now->format('Y-m-d'),
            'jam_mulai' => $futureTimeStart,
            'jam_selesai' => $futureTimeEnd,
            'keperluan' => 'Rapat BEM',
        ]);

        $response->assertValid('jam_mulai');
        Carbon::setTestNow();
    });

    test('mengizinkan reservasi pada jam berapapun untuk hari-hari berikutnya', function () {
        $tomorrow = now()->addDay()->format('Y-m-d');
        $pastTimeForTomorrow = now()->copy()->subHours(1)->format('H:i'); 

        $response = $this->actingAs($this->mahasiswa)->post(route('mahasiswa.reservasi.store'), [
            'ruangan_id' => $this->ruangan->id,
            'tanggal_reservasi' => $tomorrow,
            'jam_mulai' => $pastTimeForTomorrow,
            'jam_selesai' => now()->copy()->addHours(2)->format('H:i'),
            'keperluan' => 'Belajar Kelompok',
        ]);

        $response->assertValid('jam_mulai');
    });
});

describe('Mahasiswa Controller', function () {
    beforeEach(function () {
        $this->mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $this->ruangan = Ruangan::factory()->create(['status' => 'tersedia']);
    });

    test('ruangan.show menampilkan detail ruangan dan jadwal', function () {
        $response = $this->actingAs($this->mahasiswa)
            ->get(route('mahasiswa.ruangan.show', $this->ruangan->id));

        $response->assertStatus(200);
        $response->assertViewIs('mahasiswa.ruangan.show');
        $response->assertViewHas('ruangan');
        $response->assertViewHas('jadwalAkademiks');
    });

    test('reservasi.index menampilkan riwayat reservasi', function () {
        $response = $this->actingAs($this->mahasiswa)
            ->get(route('mahasiswa.reservasi.index'));

        $response->assertStatus(200);
        $response->assertViewIs('mahasiswa.reservasi.index');
        $response->assertViewHas('reservasis');
    });

    test('reservasi.create menampilkan form peminjaman', function () {
        $response = $this->actingAs($this->mahasiswa)
            ->get(route('mahasiswa.reservasi.create', ['ruangan_id' => $this->ruangan->id]));

        $response->assertStatus(200);
        $response->assertViewIs('mahasiswa.reservasi.create');
        $response->assertViewHas('ruangans');
        $response->assertViewHas('selectedRuanganId', (string)$this->ruangan->id);
    });

    test('reservasi.show menampilkan detail reservasi milik sendiri', function () {
        $reservasi = Reservasi::factory()->create([
            'user_id' => $this->mahasiswa->id,
            'ruangan_id' => $this->ruangan->id,
        ]);

        $response = $this->actingAs($this->mahasiswa)
            ->get(route('mahasiswa.reservasi.show', $reservasi->id));

        $response->assertStatus(200);
        $response->assertViewIs('mahasiswa.reservasi.show');
        $response->assertViewHas('reservasi');
    });

    test('reservasi.show mencegah akses ke reservasi orang lain (403)', function () {
        $mahasiswaLain = User::factory()->create(['role' => 'mahasiswa']);
        $reservasiLain = Reservasi::factory()->create([
            'user_id' => $mahasiswaLain->id,
        ]);

        $response = $this->actingAs($this->mahasiswa)
            ->get(route('mahasiswa.reservasi.show', $reservasiLain->id));

        $response->assertStatus(403);
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

    test('destroy berhasil menghapus ruangan (Happy Path)', function () {
        $ruangan = Ruangan::factory()->create();

        $response = $this->actingAs($this->logistik)
            ->delete(route('logistik.ruangan.destroy', $ruangan->id));

        $response->assertRedirect(route('logistik.ruangan.index'));
        $response->assertSessionHas('success');
    });
});

describe('Logistik Jadwal Akademik Controller', function () {
    beforeEach(function () {
        $this->logistik = User::factory()->create(['role' => 'logistik']);
        $this->ruangan = Ruangan::factory()->create();
    });

    test('index menampilkan daftar jadwal', function () {
        $response = $this->actingAs($this->logistik)
            ->get(route('logistik.jadwal.index'));

        $response->assertStatus(200);
        $response->assertViewIs('logistik.jadwal.index');
        $response->assertViewHas('jadwals');
    });

    test('create menampilkan form tambah jadwal', function () {
        $response = $this->actingAs($this->logistik)
            ->get(route('logistik.jadwal.create'));

        $response->assertStatus(200);
        $response->assertViewIs('logistik.jadwal.create');
        $response->assertViewHas('ruangans');
    });

    test('store berhasil menambahkan jadwal (Happy Path)', function () {
        $response = $this->actingAs($this->logistik)
            ->post(route('logistik.jadwal.store'), [
                'ruangan_id' => $this->ruangan->id,
                'hari' => 'senin',
                'jam_mulai' => '08:00',
                'jam_selesai' => '10:00',
                'mata_kuliah' => 'Sistem Operasi',
                'dosen' => 'Dr. A',
                'kelas' => 'IF-45',
            ]);

        $response->assertRedirect(route('logistik.jadwal.index'));
        $response->assertSessionHas('success');
    });

    test('edit menampilkan form edit jadwal', function () {
        $jadwal = JadwalAkademik::factory()->create(['ruangan_id' => $this->ruangan->id]);

        $response = $this->actingAs($this->logistik)
            ->get(route('logistik.jadwal.edit', $jadwal->id));

        $response->assertStatus(200);
        $response->assertViewIs('logistik.jadwal.edit');
        $response->assertViewHas('jadwal');
    });

    test('update berhasil memperbarui jadwal (Happy Path)', function () {
        $jadwal = JadwalAkademik::factory()->create(['ruangan_id' => $this->ruangan->id]);

        $response = $this->actingAs($this->logistik)
            ->put(route('logistik.jadwal.update', $jadwal->id), [
                'ruangan_id' => $this->ruangan->id,
                'hari' => 'selasa',
                'jam_mulai' => '10:00',
                'jam_selesai' => '12:00',
                'mata_kuliah' => 'Basis Data',
                'dosen' => 'Dr. B',
                'kelas' => 'IF-45',
            ]);

        $response->assertRedirect(route('logistik.jadwal.index'));
        $response->assertSessionHas('success');
    });

    test('destroy berhasil menghapus jadwal (Happy Path)', function () {
        $jadwal = JadwalAkademik::factory()->create(['ruangan_id' => $this->ruangan->id]);

        $response = $this->actingAs($this->logistik)
            ->delete(route('logistik.jadwal.destroy', $jadwal->id));

        $response->assertRedirect(route('logistik.jadwal.index'));
        $response->assertSessionHas('success');
    });
});

describe('Log Ruangan Controller', function () {
    beforeEach(function () {
        $this->logistik = User::factory()->create(['role' => 'logistik']);
    });

    test('logistik dapat mengakses halaman log ruangan', function () {
        $response = $this->actingAs($this->logistik)
            ->get(route('log.index'));

        $response->assertStatus(200);
        $response->assertViewIs('log.index');
        $response->assertViewHas('logs');
        $response->assertViewHas('ruangans');
    });

    test('halaman log ruangan mengaplikasikan filter dengan benar', function () {
        $ruangan1 = \App\Models\Ruangan::factory()->create();
        $ruangan2 = \App\Models\Ruangan::factory()->create();
        $user = \App\Models\User::factory()->create();
        $reservasi1 = \App\Models\Reservasi::factory()->create(['ruangan_id' => $ruangan1->id, 'user_id' => $user->id]);
        $reservasi2 = \App\Models\Reservasi::factory()->create(['ruangan_id' => $ruangan2->id, 'user_id' => $user->id]);

        \App\Models\LogRuangan::create([
            'reservasi_id' => $reservasi1->id, 'ruangan_id' => $ruangan1->id, 'user_id' => $user->id,
            'tanggal' => '2023-05-10', 'jam_mulai' => '08:00', 'jam_selesai' => '10:00',
            'keperluan' => 'rapat', 'nim' => '123', 'prodi' => 'IF', 'status' => 'disetujui'
        ]);

        \App\Models\LogRuangan::create([
            'reservasi_id' => $reservasi2->id, 'ruangan_id' => $ruangan2->id, 'user_id' => $user->id,
            'tanggal' => '2023-06-10', 'jam_mulai' => '08:00', 'jam_selesai' => '10:00',
            'keperluan' => 'kuliah', 'nim' => '123', 'prodi' => 'IF', 'status' => 'ditolak_logistik'
        ]);

        $response = $this->actingAs($this->logistik)
            ->get(route('log.index', [
                'ruangan_id' => $ruangan1->id,
                'status' => 'disetujui',
                'tanggal_dari' => '2023-05-01',
                'tanggal_sampai' => '2023-05-31',
            ]));

        $response->assertStatus(200);
        $response->assertViewHas('logs');
        expect($response->original->getData()['logs']->count())->toBe(1);
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
                'catatan_ssc' => 'Boleh',
            ]);

        $response->assertRedirect(route('ssc.approval.index'));
        $response->assertSessionHas('success');

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

        $response->assertRedirect(route('logistik.approval.index'));

        $this->assertDatabaseHas('reservasis', [
            'id' => $this->reservasi->id,
            'status' => 'disetujui',
            'approved_by_logistik' => $this->logistik->id,
        ]);
        
        $this->assertDatabaseMissing('reservasis', [
            'id' => $this->reservasi->id,
            'qr_code' => null,
        ]);

        $this->assertDatabaseHas('log_ruangans', [
            'reservasi_id' => $this->reservasi->id,
            'status' => 'disetujui',
        ]);
    });

    test('mencegah logistik menyetujui reservasi yang masih berstatus menunggu_ssc (Transisi Status Tidak Valid)', function () {
        $response = $this->actingAs($this->logistik)
            ->post(route('logistik.approval.process', $this->reservasi->id), [
                'action' => 'approve',
            ]);

        $response->assertSessionHasErrors('status');
        
        $this->assertDatabaseHas('reservasis', [
            'id' => $this->reservasi->id,
            'status' => 'menunggu_ssc',
        ]);
    });
});
