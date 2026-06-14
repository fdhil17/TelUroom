<?php

namespace App\Services;

use App\Models\JadwalAkademik;
use App\Models\LogRuangan;
use App\Models\Reservasi;
use App\Models\Ruangan;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReservasiService
{
    public function cekStatusKetersediaan(Ruangan $ruangan, string $tanggal, string $jamMulai, string $jamSelesai): string
    {
        if ($ruangan->status === 'maintenance') {
            return 'maintenance';
        }

        $hari = $this->getHariFromTanggal($tanggal);

        $bentrokAkademik = JadwalAkademik::where('ruangan_id', $ruangan->id)
            ->where('hari', $hari)
            ->where(function ($q) use ($jamMulai, $jamSelesai) {
                $q->where('jam_mulai', '<', $jamSelesai)
                  ->where('jam_selesai', '>', $jamMulai);
            })
            ->exists();

        if ($bentrokAkademik) {
            return 'digunakan_kuliah';
        }

        $bentrokReservasi = Reservasi::where('ruangan_id', $ruangan->id)
            ->where('tanggal_reservasi', $tanggal)
            ->whereNotIn('status', ['ditolak_ssc', 'ditolak_logistik'])
            ->where(function ($q) use ($jamMulai, $jamSelesai) {
                $q->where('jam_mulai', '<', $jamSelesai)
                  ->where('jam_selesai', '>', $jamMulai);
            })
            ->exists();

        if ($bentrokReservasi) {
            return 'sudah_direservasi';
        }

        return 'tersedia';
    }

    public function create(array $data, int $userId): Reservasi
    {
        $ruangan = Ruangan::findOrFail($data['ruangan_id']);

        $status = $this->cekStatusKetersediaan(
            $ruangan,
            $data['tanggal_reservasi'],
            $data['jam_mulai'],
            $data['jam_selesai']
        );

        if ($status !== 'tersedia') {
            $pesan = match ($status) {
                'digunakan_kuliah' => 'Ruangan sedang digunakan untuk kuliah pada waktu tersebut.',
                'sudah_direservasi' => 'Ruangan sudah direservasi oleh pengguna lain pada waktu tersebut.',
                'maintenance' => 'Ruangan sedang dalam status maintenance.',
                default => 'Ruangan tidak tersedia.',
            };

            throw ValidationException::withMessages([
                'ruangan_id' => $pesan,
            ]);
        }

        $data['user_id'] = $userId;
        $data['status'] = 'menunggu_ssc';

        return Reservasi::create($data);
    }

    public function getRiwayatByUser(int $userId): LengthAwarePaginator
    {
        return Reservasi::with('ruangan')
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->paginate(10);
    }

    public function getAllForSsc(?string $status = null): LengthAwarePaginator
    {
        $query = Reservasi::with('user', 'ruangan');

        if ($status) {
            $query->where('status', $status);
        } else {
            $query->whereIn('status', ['menunggu_ssc', 'menunggu_logistik', 'disetujui', 'ditolak_ssc', 'ditolak_logistik']);
        }

        return $query->orderByDesc('created_at')->paginate(10)->withQueryString();
    }

    public function approveBySsc(Reservasi $reservasi, int $sscUserId, ?string $catatan = null): Reservasi
    {
        if ($reservasi->status !== 'menunggu_ssc') {
            throw ValidationException::withMessages([
                'status' => 'Reservasi ini tidak dalam status menunggu persetujuan SSC.',
            ]);
        }

        $reservasi->update([
            'status' => 'menunggu_logistik',
            'catatan_ssc' => $catatan,
            'approved_by_ssc' => $sscUserId,
        ]);

        return $reservasi;
    }

    public function rejectBySsc(Reservasi $reservasi, int $sscUserId, string $catatan): Reservasi
    {
        if ($reservasi->status !== 'menunggu_ssc') {
            throw ValidationException::withMessages([
                'status' => 'Reservasi ini tidak dalam status menunggu persetujuan SSC.',
            ]);
        }

        $reservasi->update([
            'status' => 'ditolak_ssc',
            'catatan_ssc' => $catatan,
            'approved_by_ssc' => $sscUserId,
        ]);

        // Buat log
        LogRuangan::create([
            'reservasi_id' => $reservasi->id,
            'ruangan_id' => $reservasi->ruangan_id,
            'user_id' => $reservasi->user_id,
            'tanggal' => $reservasi->tanggal_reservasi,
            'jam_mulai' => $reservasi->jam_mulai,
            'jam_selesai' => $reservasi->jam_selesai,
            'keperluan' => $reservasi->keperluan,
            'nim' => $reservasi->user->nim,
            'prodi' => $reservasi->user->prodi,
            'status' => 'ditolak_ssc',
        ]);

        return $reservasi;
    }

    public function getAllForLogistik(?string $status = null): LengthAwarePaginator
    {
        $query = Reservasi::with('user', 'ruangan');

        if ($status) {
            $query->where('status', $status);
        } else {
            $query->whereIn('status', ['menunggu_logistik', 'disetujui', 'ditolak_logistik']);
        }

        return $query->orderByDesc('created_at')->paginate(10)->withQueryString();
    }

    public function approveByLogistik(Reservasi $reservasi, int $logistikUserId, ?string $catatan = null): Reservasi
    {
        if ($reservasi->status !== 'menunggu_logistik') {
            throw ValidationException::withMessages([
                'status' => 'Reservasi ini tidak dalam status menunggu persetujuan Logistik.',
            ]);
        }

        $qrContent = route('verifikasi.show', $reservasi->id);
        $fileName = 'qrcodes/reservasi-' . $reservasi->id . '-' . time() . '.png';
        QrCode::format('png')->size(300)->generate($qrContent, storage_path('app/public/' . $fileName));

        $reservasi->update([
            'status' => 'disetujui',
            'catatan_logistik' => $catatan,
            'approved_by_logistik' => $logistikUserId,
            'qr_code' => $fileName,
        ]);

        // Buat log
        LogRuangan::create([
            'reservasi_id' => $reservasi->id,
            'ruangan_id' => $reservasi->ruangan_id,
            'user_id' => $reservasi->user_id,
            'tanggal' => $reservasi->tanggal_reservasi,
            'jam_mulai' => $reservasi->jam_mulai,
            'jam_selesai' => $reservasi->jam_selesai,
            'keperluan' => $reservasi->keperluan,
            'nim' => $reservasi->user->nim,
            'prodi' => $reservasi->user->prodi,
            'status' => 'disetujui',
        ]);

        return $reservasi;
    }

    public function rejectByLogistik(Reservasi $reservasi, int $logistikUserId, string $catatan): Reservasi
    {
        if ($reservasi->status !== 'menunggu_logistik') {
            throw ValidationException::withMessages([
                'status' => 'Reservasi ini tidak dalam status menunggu persetujuan Logistik.',
            ]);
        }

        $reservasi->update([
            'status' => 'ditolak_logistik',
            'catatan_logistik' => $catatan,
            'approved_by_logistik' => $logistikUserId,
        ]);

        // Buat log
        LogRuangan::create([
            'reservasi_id' => $reservasi->id,
            'ruangan_id' => $reservasi->ruangan_id,
            'user_id' => $reservasi->user_id,
            'tanggal' => $reservasi->tanggal_reservasi,
            'jam_mulai' => $reservasi->jam_mulai,
            'jam_selesai' => $reservasi->jam_selesai,
            'keperluan' => $reservasi->keperluan,
            'nim' => $reservasi->user->nim,
            'prodi' => $reservasi->user->prodi,
            'status' => 'ditolak_logistik',
        ]);

        return $reservasi;
    }

    public function getStatistikByUser(int $userId): array
    {
        return [
            'total' => Reservasi::where('user_id', $userId)->count(),
            'menunggu_ssc' => Reservasi::where('user_id', $userId)->where('status', 'menunggu_ssc')->count(),
            'menunggu_logistik' => Reservasi::where('user_id', $userId)->where('status', 'menunggu_logistik')->count(),
            'disetujui' => Reservasi::where('user_id', $userId)->where('status', 'disetujui')->count(),
            'ditolak' => Reservasi::where('user_id', $userId)->whereIn('status', ['ditolak_ssc', 'ditolak_logistik'])->count(),
        ];
    }

    public function getStatistikSsc(): array
    {
        return [
            'menunggu_ssc' => Reservasi::where('status', 'menunggu_ssc')->count(),
            'menunggu_logistik' => Reservasi::where('status', 'menunggu_logistik')->count(),
            'disetujui' => Reservasi::where('status', 'disetujui')->count(),
            'ditolak_ssc' => Reservasi::where('status', 'ditolak_ssc')->count(),
            'total' => Reservasi::count(),
        ];
    }

    public function getStatistikLogistik(): array
    {
        return [
            'menunggu_logistik' => Reservasi::where('status', 'menunggu_logistik')->count(),
            'disetujui' => Reservasi::where('status', 'disetujui')->count(),
            'ditolak_logistik' => Reservasi::where('status', 'ditolak_logistik')->count(),
            'total' => Reservasi::count(),
        ];
    }

    public function getReservasiTerbaruForLogistik(int $limit = 5)
    {
        return Reservasi::with('user', 'ruangan')
            ->whereIn('status', ['menunggu_logistik', 'disetujui', 'ditolak_logistik'])
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function getReservasiTerbaruForSsc(int $limit = 5)
    {
        return Reservasi::with('user', 'ruangan')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function getCalendarEvents(?int $ruanganId = null): array
    {
        $events = [];

        $jadwalQuery = JadwalAkademik::with('ruangan');
        if ($ruanganId) {
            $jadwalQuery->where('ruangan_id', $ruanganId);
        }

        $hariToDow = [
            'minggu' => 0,
            'senin' => 1,
            'selasa' => 2,
            'rabu' => 3,
            'kamis' => 4,
            'jumat' => 5,
            'sabtu' => 6,
        ];

        foreach ($jadwalQuery->get() as $jadwal) {
            $events[] = [
                'title' => 'Kuliah: ' . $jadwal->mata_kuliah . ' (' . $jadwal->ruangan->kode_ruangan . ')',
                'daysOfWeek' => [$hariToDow[$jadwal->hari]],
                'startTime' => substr($jadwal->jam_mulai, 0, 5),
                'endTime' => substr($jadwal->jam_selesai, 0, 5),
                'color' => '#0d6efd',
                'extendedProps' => [
                    'type' => 'akademik',
                    'dosen' => $jadwal->dosen,
                    'ruangan' => $jadwal->ruangan->kode_ruangan,
                ],
            ];
        }

        $reservasiQuery = Reservasi::with('user', 'ruangan')->where('status', 'disetujui');
        if ($ruanganId) {
            $reservasiQuery->where('ruangan_id', $ruanganId);
        }

        foreach ($reservasiQuery->get() as $reservasi) {
            $tanggal = $reservasi->tanggal_reservasi->format('Y-m-d');

            $events[] = [
                'title' => 'Reservasi: ' . $reservasi->user->name . ' (' . $reservasi->ruangan->kode_ruangan . ')',
                'start' => $tanggal . 'T' . substr($reservasi->jam_mulai, 0, 5),
                'end' => $tanggal . 'T' . substr($reservasi->jam_selesai, 0, 5),
                'color' => '#198754',
                'extendedProps' => [
                    'type' => 'reservasi',
                    'mahasiswa' => $reservasi->user->name,
                    'keperluan' => $reservasi->keperluan,
                    'ruangan' => $reservasi->ruangan->kode_ruangan,
                ],
            ];
        }

        return $events;
    }

    protected function getHariFromTanggal(string $tanggal): string
    {
        $mapping = [
            'Monday' => 'senin',
            'Tuesday' => 'selasa',
            'Wednesday' => 'rabu',
            'Thursday' => 'kamis',
            'Friday' => 'jumat',
            'Saturday' => 'sabtu',
            'Sunday' => 'minggu',
        ];

        return $mapping[Carbon::parse($tanggal)->format('l')];
    }
}
