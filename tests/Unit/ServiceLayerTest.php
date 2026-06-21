<?php

use App\Models\JadwalAkademik;
use Illuminate\Foundation\Testing\RefreshDatabase;
uses(Tests\TestCase::class, RefreshDatabase::class);
use App\Models\Reservasi;
use App\Models\Ruangan;
use App\Models\User;
use App\Services\ReservasiService;
use App\Services\RuanganService;
use App\Services\JadwalAkademikService;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

describe('cekStatusKetersediaan', function () {
    beforeEach(function () {
        $this->service = app(ReservasiService::class);
        $this->ruangan = Ruangan::factory()->create(['status' => 'tersedia']);
        $this->tanggal = Carbon::now()->next(Carbon::MONDAY)->format('Y-m-d');
    });

    test('mengembalikan status maintenance jika ruangan sedang maintenance (Path 1)', function () {
        $this->ruangan->update(['status' => 'maintenance']);
        $status = $this->service->cekStatusKetersediaan($this->ruangan, $this->tanggal, '08:00:00', '10:00:00');
        expect($status)->toBe('maintenance');
    });

    test('mengembalikan status digunakan_kuliah jika bentrok dengan jadwal akademik (Path 2)', function () {
        JadwalAkademik::factory()->create([
            'ruangan_id' => $this->ruangan->id,
            'hari' => 'senin',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
        ]);
        $status = $this->service->cekStatusKetersediaan($this->ruangan, $this->tanggal, '09:00:00', '11:00:00');
        expect($status)->toBe('digunakan_kuliah');
    });

    test('mengembalikan status sudah_direservasi jika bentrok dengan reservasi yang sudah ada (Path 3)', function () {
        Reservasi::factory()->disetujui()->create([
            'ruangan_id' => $this->ruangan->id,
            'tanggal_reservasi' => $this->tanggal,
            'jam_mulai' => '13:00:00',
            'jam_selesai' => '15:00:00',
        ]);
        $status = $this->service->cekStatusKetersediaan($this->ruangan, $this->tanggal, '14:00:00', '16:00:00');
        expect($status)->toBe('sudah_direservasi');
    });

    test('mengembalikan status tersedia jika ruangan benar-benar kosong (Path 4)', function () {
        $status = $this->service->cekStatusKetersediaan($this->ruangan, $this->tanggal, '08:00:00', '10:00:00');
        expect($status)->toBe('tersedia');
    });

    test('mengabaikan id sendiri saat update dan mengembalikan status tersedia (Path 5)', function () {
        $reservasi = Reservasi::factory()->disetujui()->create([
            'ruangan_id' => $this->ruangan->id,
            'tanggal_reservasi' => $this->tanggal,
            'jam_mulai' => '13:00:00',
            'jam_selesai' => '15:00:00',
        ]);
        $status = $this->service->cekStatusKetersediaan($this->ruangan, $this->tanggal, '13:00:00', '15:00:00', $reservasi->id);
        expect($status)->toBe('tersedia');
    });
});

describe('create() Reservasi', function () {
    beforeEach(function () {
        $this->service = app(ReservasiService::class);
        $this->ruangan = Ruangan::factory()->create(['status' => 'tersedia']);
        $this->user = User::factory()->create();
        $this->tanggal = Carbon::now()->next(Carbon::MONDAY)->format('Y-m-d');
    });

    test('berhasil membuat reservasi jika ruangan tersedia', function () {
        $data = [
            'ruangan_id' => $this->ruangan->id,
            'tanggal_reservasi' => $this->tanggal,
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
            'keperluan' => 'Rapat BEM',
        ];

        $reservasi = $this->service->create($data, $this->user->id);

        expect($reservasi->status)->toBe('menunggu_ssc')
            ->and($reservasi->user_id)->toBe($this->user->id)
            ->and($reservasi->keperluan)->toBe('Rapat BEM');
    });

    test('gagal membuat reservasi jika ruangan digunakan kuliah', function () {
        JadwalAkademik::factory()->create([
            'ruangan_id' => $this->ruangan->id,
            'hari' => 'senin',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
        ]);

        $data = [
            'ruangan_id' => $this->ruangan->id,
            'tanggal_reservasi' => $this->tanggal,
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
            'keperluan' => 'Rapat BEM',
        ];

        $this->service->create($data, $this->user->id);
    })->throws(ValidationException::class, 'Ruangan sedang digunakan untuk kuliah pada waktu tersebut.');

    test('gagal membuat reservasi jika ruangan sudah direservasi', function () {
        Reservasi::factory()->disetujui()->create([
            'ruangan_id' => $this->ruangan->id,
            'tanggal_reservasi' => $this->tanggal,
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
        ]);

        $data = [
            'ruangan_id' => $this->ruangan->id,
            'tanggal_reservasi' => $this->tanggal,
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
            'keperluan' => 'Rapat BEM',
        ];

        $this->service->create($data, $this->user->id);
    })->throws(ValidationException::class, 'Ruangan sudah direservasi oleh pengguna lain pada waktu tersebut.');

    test('gagal membuat reservasi jika ruangan maintenance', function () {
        $this->ruangan->update(['status' => 'maintenance']);

        $data = [
            'ruangan_id' => $this->ruangan->id,
            'tanggal_reservasi' => $this->tanggal,
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
            'keperluan' => 'Rapat BEM',
        ];

        $this->service->create($data, $this->user->id);
    })->throws(ValidationException::class, 'Ruangan sedang dalam status maintenance.');
});

describe('approveBySsc', function () {
    beforeEach(function () {
        $this->service = app(ReservasiService::class);
        $this->ruangan = Ruangan::factory()->create(['status' => 'tersedia']);
        $this->user = User::factory()->create();
        $this->tanggal = Carbon::now()->next(Carbon::MONDAY)->format('Y-m-d');
    });

    test('menghasilkan error jika status reservasi bukan menunggu_ssc (Path 1)', function () {
        $reservasi = Reservasi::factory()->disetujui()->create();
        $this->service->approveBySsc($reservasi, $this->user->id);
    })->throws(ValidationException::class, 'Reservasi ini tidak dalam status menunggu persetujuan SSC.');

    test('berhasil menyetujui dan mengubah status menjadi menunggu_logistik (Path 2)', function () {
        $reservasi = Reservasi::factory()->create([
            'status' => 'menunggu_ssc',
            'ruangan_id' => $this->ruangan->id,
            'tanggal_reservasi' => $this->tanggal,
        ]);

        $result = $this->service->approveBySsc($reservasi, $this->user->id, 'OK dari SSC');

        expect($result->status)->toBe('menunggu_logistik')
            ->and($result->approved_by_ssc)->toBe($this->user->id)
            ->and($result->catatan_ssc)->toBe('OK dari SSC');
    });

    test('menghasilkan error jika tiba-tiba ada jadwal kuliah (Path 3)', function () {
        $reservasi = Reservasi::factory()->create([
            'status' => 'menunggu_ssc',
            'ruangan_id' => $this->ruangan->id,
            'tanggal_reservasi' => $this->tanggal,
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
        ]);

        JadwalAkademik::factory()->create([
            'ruangan_id' => $this->ruangan->id,
            'hari' => 'senin',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
        ]);

        $this->service->approveBySsc($reservasi, $this->user->id);
    })->throws(ValidationException::class, 'Persetujuan gagal. Ruangan telah dialokasikan untuk jadwal kuliah.');

    test('menghasilkan error jika tiba-tiba reservasi lain disetujui lebih dulu (Path 4)', function () {
        $reservasi1 = Reservasi::factory()->create([
            'status' => 'menunggu_ssc',
            'ruangan_id' => $this->ruangan->id,
            'tanggal_reservasi' => $this->tanggal,
            'jam_mulai' => '13:00:00',
            'jam_selesai' => '15:00:00',
        ]);

        Reservasi::factory()->disetujui()->create([
            'ruangan_id' => $this->ruangan->id,
            'tanggal_reservasi' => $this->tanggal,
            'jam_mulai' => '13:00:00',
            'jam_selesai' => '15:00:00',
        ]);

        $this->service->approveBySsc($reservasi1, $this->user->id);
    })->throws(ValidationException::class, 'Persetujuan gagal. Ruangan telah disetujui atau sedang dalam proses untuk peminjaman lain.');
});

describe('rejectBySsc()', function () {
    beforeEach(function () {
        $this->service = app(ReservasiService::class);
        $this->ruangan = Ruangan::factory()->create(['status' => 'tersedia']);
        $this->sscUser = User::factory()->create(['role' => 'ssc']);
    });

    test('berhasil menolak reservasi dan membuat log ruangan', function () {
        $reservasi = Reservasi::factory()->create([
            'status' => 'menunggu_ssc',
            'ruangan_id' => $this->ruangan->id,
        ]);

        $result = $this->service->rejectBySsc($reservasi, $this->sscUser->id, 'Ruangan penuh');

        expect($result->status)->toBe('ditolak_ssc')
            ->and($result->catatan_ssc)->toBe('Ruangan penuh')
            ->and($result->approved_by_ssc)->toBe($this->sscUser->id);

        $this->assertDatabaseHas('log_ruangans', [
            'reservasi_id' => $reservasi->id,
            'status' => 'ditolak_ssc',
        ]);
    });

    test('gagal menolak reservasi jika status bukan menunggu_ssc', function () {
        $reservasi = Reservasi::factory()->disetujui()->create();
        $this->service->rejectBySsc($reservasi, $this->sscUser->id, 'Ruangan penuh');
    })->throws(ValidationException::class, 'Reservasi ini tidak dalam status menunggu persetujuan SSC.');
});

describe('approveByLogistik()', function () {
    beforeEach(function () {
        $this->service = app(ReservasiService::class);
        $this->ruangan = Ruangan::factory()->create(['status' => 'tersedia']);
        $this->logistikUser = User::factory()->create(['role' => 'logistik']);
        $this->tanggal = Carbon::now()->next(Carbon::MONDAY)->format('Y-m-d');
    });

    test('gagal menyetujui jika status bukan menunggu_logistik', function () {
        $reservasi = Reservasi::factory()->create(['status' => 'menunggu_ssc']);
        $this->service->approveByLogistik($reservasi, $this->logistikUser->id);
    })->throws(ValidationException::class, 'Reservasi ini tidak dalam status menunggu persetujuan Logistik.');

    test('gagal menyetujui jika tiba-tiba bentrok dengan jadwal akademik', function () {
        $reservasi = Reservasi::factory()->create([
            'status' => 'menunggu_logistik',
            'ruangan_id' => $this->ruangan->id,
            'tanggal_reservasi' => $this->tanggal,
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
        ]);

        JadwalAkademik::factory()->create([
            'ruangan_id' => $this->ruangan->id,
            'hari' => 'senin',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
        ]);

        $this->service->approveByLogistik($reservasi, $this->logistikUser->id);
    })->throws(ValidationException::class, 'Persetujuan gagal. Ruangan telah dialokasikan untuk jadwal kuliah.');

    test('gagal menyetujui jika tiba-tiba reservasi lain disetujui', function () {
        $reservasi = Reservasi::factory()->create([
            'status' => 'menunggu_logistik',
            'ruangan_id' => $this->ruangan->id,
            'tanggal_reservasi' => $this->tanggal,
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
        ]);

        Reservasi::factory()->disetujui()->create([
            'ruangan_id' => $this->ruangan->id,
            'tanggal_reservasi' => $this->tanggal,
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
        ]);

        $this->service->approveByLogistik($reservasi, $this->logistikUser->id);
    })->throws(ValidationException::class, 'Persetujuan gagal. Ruangan telah disetujui atau sedang dalam proses untuk peminjaman lain.');

    test('berhasil menyetujui, membuat QR, dan log', function () {
        $reservasi = Reservasi::factory()->create([
            'status' => 'menunggu_logistik',
            'ruangan_id' => $this->ruangan->id,
            'tanggal_reservasi' => $this->tanggal,
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
        ]);

        $result = $this->service->approveByLogistik($reservasi, $this->logistikUser->id, 'OK dari Logistik');

        expect($result->status)->toBe('disetujui')
            ->and($result->approved_by_logistik)->toBe($this->logistikUser->id)
            ->and($result->catatan_logistik)->toBe('OK dari Logistik')
            ->and($result->qr_code)->not->toBeNull();

        $this->assertDatabaseHas('log_ruangans', [
            'reservasi_id' => $reservasi->id,
            'status' => 'disetujui',
        ]);
    });
});

describe('rejectByLogistik()', function () {
    beforeEach(function () {
        $this->service = app(ReservasiService::class);
        $this->ruangan = Ruangan::factory()->create(['status' => 'tersedia']);
        $this->logistikUser = User::factory()->create(['role' => 'logistik']);
    });

    test('berhasil menolak reservasi logistik dan membuat log', function () {
        $reservasi = Reservasi::factory()->create([
            'status' => 'menunggu_logistik',
            'ruangan_id' => $this->ruangan->id,
        ]);

        $result = $this->service->rejectByLogistik($reservasi, $this->logistikUser->id, 'Ruangan rusak');

        expect($result->status)->toBe('ditolak_logistik')
            ->and($result->catatan_logistik)->toBe('Ruangan rusak')
            ->and($result->approved_by_logistik)->toBe($this->logistikUser->id);

        $this->assertDatabaseHas('log_ruangans', [
            'reservasi_id' => $reservasi->id,
            'status' => 'ditolak_logistik',
        ]);
    });

    test('gagal menolak reservasi jika status bukan menunggu_logistik', function () {
        $reservasi = Reservasi::factory()->create(['status' => 'menunggu_ssc']);
        $this->service->rejectByLogistik($reservasi, $this->logistikUser->id, 'Ruangan rusak');
    })->throws(ValidationException::class, 'Reservasi ini tidak dalam status menunggu persetujuan Logistik.');
});

describe('RuanganService', function () {
    beforeEach(function () {
        $this->service = app(RuanganService::class);
    });

    test('getAll() mengembalikan semua ruangan dengan filter', function () {
        Ruangan::factory()->create(['kode_ruangan' => 'A101', 'lantai' => 1]);
        Ruangan::factory()->create(['kode_ruangan' => 'A102', 'lantai' => 1]);
        Ruangan::factory()->create(['kode_ruangan' => 'B201', 'lantai' => 2]);
        
        $all = $this->service->getAll();
        expect($all->total())->toBe(3);

        $search = $this->service->getAll('A10');
        expect($search->total())->toBe(2);

        $filterLantai = $this->service->getAll(null, 2);
        expect($filterLantai->total())->toBe(1);

        $searchAndLantai = $this->service->getAll('B2', 2);
        expect($searchAndLantai->total())->toBe(1);
    });

    test('create() berhasil membuat ruangan baru', function () {
        $data = [
            'kode_ruangan' => 'C301',
            'nama_ruangan' => 'Lab Komputer',
            'kapasitas' => 40,
            'lantai' => 3,
            'fasilitas' => 'AC, Proyektor, PC',
            'status' => 'tersedia',
        ];

        $ruangan = $this->service->create($data);

        expect($ruangan->kode_ruangan)->toBe('C301')
            ->and($ruangan->nama_ruangan)->toBe('Lab Komputer')
            ->and($ruangan->lantai)->toBe(3);

        $this->assertDatabaseHas('ruangans', ['kode_ruangan' => 'C301']);
    });

    test('update() berhasil memperbarui ruangan', function () {
        $ruangan = Ruangan::factory()->create(['kapasitas' => 20]);

        $updated = $this->service->update($ruangan, ['kapasitas' => 50, 'status' => 'maintenance']);

        expect($updated->kapasitas)->toBe(50)
            ->and($updated->status)->toBe('maintenance');
    });

    test('delete() berhasil menghapus ruangan', function () {
        $ruangan = Ruangan::factory()->create();
        
        $result = $this->service->delete($ruangan);

        expect($result)->toBeTrue();
        $this->assertDatabaseMissing('ruangans', ['id' => $ruangan->id]);
    });

    test('getStatistik() mengembalikan array statistik yang akurat', function () {
        Ruangan::factory(2)->create(['status' => 'tersedia']);
        Ruangan::factory(1)->create(['status' => 'digunakan_kuliah']);
        Ruangan::factory(1)->create(['status' => 'sudah_direservasi']);
        Ruangan::factory(1)->create(['status' => 'maintenance']);

        $stat = $this->service->getStatistik();

        expect($stat['total'])->toBeGreaterThanOrEqual(5)
            ->and($stat['tersedia'])->toBeGreaterThanOrEqual(2)
            ->and($stat['digunakan_kuliah'])->toBeGreaterThanOrEqual(1)
            ->and($stat['sudah_direservasi'])->toBeGreaterThanOrEqual(1)
            ->and($stat['maintenance'])->toBeGreaterThanOrEqual(1);
    });
});

describe('JadwalAkademikService', function () {
    beforeEach(function () {
        $this->service = app(JadwalAkademikService::class);
        $this->ruangan = Ruangan::factory()->create();
        $this->user = User::factory()->create();
    });

    test('getAll() mengembalikan jadwal akademik dengan filter', function () {
        JadwalAkademik::factory()->create(['ruangan_id' => $this->ruangan->id, 'hari' => 'senin']);
        JadwalAkademik::factory()->create(['ruangan_id' => $this->ruangan->id, 'hari' => 'selasa']);
        
        $ruanganLain = Ruangan::factory()->create();
        JadwalAkademik::factory()->create(['ruangan_id' => $ruanganLain->id, 'hari' => 'senin']);

        expect($this->service->getAll()->total())->toBe(3);
        expect($this->service->getAll($this->ruangan->id)->total())->toBe(2);
        expect($this->service->getAll(null, 'senin')->total())->toBe(2);
        expect($this->service->getAll($this->ruangan->id, 'senin')->total())->toBe(1);
    });

    test('create() berhasil membuat jadwal akademik jika tidak bentrok', function () {
        $data = [
            'ruangan_id' => $this->ruangan->id,
            'hari' => 'rabu',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
            'mata_kuliah' => 'Matematika Diskrit',
            'dosen' => 'Dr. Budi',
            'kelas' => 'IF-45-01',
        ];

        $jadwal = $this->service->create($data, $this->user->id);

        expect($jadwal->mata_kuliah)->toBe('Matematika Diskrit')
            ->and($jadwal->created_by)->toBe($this->user->id);
    });

    test('create() gagal jika bentrok dengan jadwal akademik lain', function () {
        JadwalAkademik::factory()->create([
            'ruangan_id' => $this->ruangan->id,
            'hari' => 'rabu',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
        ]);

        $data = [
            'ruangan_id' => $this->ruangan->id,
            'hari' => 'rabu',
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '11:00:00',
            'mata_kuliah' => 'Algoritma',
            'dosen' => 'Dr. Andi',
            'kelas' => 'IF-45-02',
        ];

        $this->service->create($data, $this->user->id);
    })->throws(ValidationException::class, 'Jadwal bentrok dengan jadwal akademik lain di ruangan dan hari yang sama.');

    test('create() gagal jika bentrok dengan reservasi yang sudah disetujui', function () {
        Reservasi::factory()->disetujui()->create([
            'ruangan_id' => $this->ruangan->id,
            'tanggal_reservasi' => \Carbon\Carbon::now()->next(\Carbon\Carbon::WEDNESDAY)->format('Y-m-d'),
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
        ]);

        $data = [
            'ruangan_id' => $this->ruangan->id,
            'hari' => 'rabu',
            'jam_mulai' => '08:30:00',
            'jam_selesai' => '09:30:00',
            'mata_kuliah' => 'Algoritma',
            'dosen' => 'Dr. Andi',
            'kelas' => 'IF-45-02',
        ];

        $this->service->create($data, $this->user->id);
    })->throws(ValidationException::class, 'Jadwal bentrok dengan reservasi mahasiswa yang sudah disetujui di ruangan dan hari yang sama.');

    test('update() berhasil memperbarui jadwal', function () {
        $jadwal = JadwalAkademik::factory()->create([
            'ruangan_id' => $this->ruangan->id,
            'hari' => 'kamis',
            'jam_mulai' => '13:00:00',
            'jam_selesai' => '15:00:00',
        ]);

        $updated = $this->service->update($jadwal, [
            'ruangan_id' => $this->ruangan->id,
            'hari' => 'kamis',
            'jam_mulai' => '13:00:00',
            'jam_selesai' => '15:00:00',
            'mata_kuliah' => 'Fisika',
            'dosen' => 'Dr. Cici',
            'kelas' => 'IF-45-03',
        ]);

        expect($updated->mata_kuliah)->toBe('Fisika');
    });

    test('update() gagal jika bentrok dengan jadwal lain', function () {
        JadwalAkademik::factory()->create([
            'ruangan_id' => $this->ruangan->id,
            'hari' => 'kamis',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
        ]);

        $jadwal2 = JadwalAkademik::factory()->create([
            'ruangan_id' => $this->ruangan->id,
            'hari' => 'kamis',
            'jam_mulai' => '13:00:00',
            'jam_selesai' => '15:00:00',
        ]);

        $this->service->update($jadwal2, [
            'ruangan_id' => $this->ruangan->id,
            'hari' => 'kamis',
            'jam_mulai' => '09:00:00',
            'jam_selesai' => '11:00:00',
            'mata_kuliah' => 'Fisika',
            'dosen' => 'Dr. Cici',
            'kelas' => 'IF-45-03',
        ]);
    })->throws(ValidationException::class, 'Jadwal bentrok dengan jadwal akademik lain di ruangan dan hari yang sama.');

    test('delete() berhasil menghapus jadwal', function () {
        $jadwal = JadwalAkademik::factory()->create();
        $result = $this->service->delete($jadwal);

        expect($result)->toBeTrue();
        $this->assertDatabaseMissing('jadwal_akademiks', ['id' => $jadwal->id]);
    });
});
