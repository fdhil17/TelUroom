<?php

namespace App\Services;

use App\Models\JadwalAkademik;
use App\Models\Reservasi;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class JadwalAkademikService
{
    public function getAll(?int $ruanganId = null, ?string $hari = null): LengthAwarePaginator
    {
        $query = JadwalAkademik::with('ruangan', 'creator');

        if ($ruanganId) {
            $query->where('ruangan_id', $ruanganId);
        }

        if ($hari) {
            $query->where('hari', $hari);
        }

        return $query->orderBy('hari')->orderBy('jam_mulai')->paginate(10)->withQueryString();
    }

    public function create(array $data, int $userId): JadwalAkademik
    {
        $this->validateNoConflict($data);

        $data['created_by'] = $userId;

        return JadwalAkademik::create($data);
    }

    public function update(JadwalAkademik $jadwal, array $data): JadwalAkademik
    {
        $this->validateNoConflict($data, $jadwal->id);

        $jadwal->update($data);

        return $jadwal;
    }

    public function delete(JadwalAkademik $jadwal): bool
    {
        return $jadwal->delete();
    }

    protected function validateNoConflict(array $data, ?int $exceptId = null): void
    {
        $query = JadwalAkademik::where('ruangan_id', $data['ruangan_id'])
            ->where('hari', $data['hari'])
            ->where(function ($q) use ($data) {
                $q->where('jam_mulai', '<', $data['jam_selesai'])
                  ->where('jam_selesai', '>', $data['jam_mulai']);
            });

        if ($exceptId) {
            $query->where('id', '!=', $exceptId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'jam_mulai' => 'Jadwal bentrok dengan jadwal akademik lain di ruangan dan hari yang sama.',
            ]);
        }

        $hariMapping = [
            'senin' => 'Monday',
            'selasa' => 'Tuesday',
            'rabu' => 'Wednesday',
            'kamis' => 'Thursday',
            'jumat' => 'Friday',
            'sabtu' => 'Saturday',
            'minggu' => 'Sunday',
        ];

        $bentrokReservasi = Reservasi::where('ruangan_id', $data['ruangan_id'])
            ->where('status', 'disetujui')
            ->whereRaw('DAYNAME(tanggal_reservasi) = ?', [$hariMapping[$data['hari']]])
            ->where(function ($q) use ($data) {
                $q->where('jam_mulai', '<', $data['jam_selesai'])
                  ->where('jam_selesai', '>', $data['jam_mulai']);
            })
            ->exists();

        if ($bentrokReservasi) {
            throw ValidationException::withMessages([
                'jam_mulai' => 'Jadwal bentrok dengan reservasi mahasiswa yang sudah disetujui di ruangan dan hari yang sama.',
            ]);
        }
    }
}
