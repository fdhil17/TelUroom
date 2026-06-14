<?php

namespace App\Services;

use App\Models\Ruangan;
use Illuminate\Pagination\LengthAwarePaginator;

class RuanganService
{
    public function getAll(?string $search = null, ?int $lantai = null): LengthAwarePaginator
    {
        $query = Ruangan::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_ruangan', 'like', "%{$search}%")
                  ->orWhere('nama_ruangan', 'like', "%{$search}%");
            });
        }

        if ($lantai) {
            $query->where('lantai', $lantai);
        }

        return $query->orderBy('lantai')->orderBy('kode_ruangan')->paginate(10)->withQueryString();
    }

    public function create(array $data): Ruangan
    {
        return Ruangan::create($data);
    }

    public function update(Ruangan $ruangan, array $data): Ruangan
    {
        $ruangan->update($data);
        return $ruangan;
    }

    public function delete(Ruangan $ruangan): bool
    {
        return $ruangan->delete();
    }

    public function getStatistik(): array
    {
        return [
            'total' => Ruangan::count(),
            'tersedia' => Ruangan::where('status', 'tersedia')->count(),
            'digunakan_kuliah' => Ruangan::where('status', 'digunakan_kuliah')->count(),
            'sudah_direservasi' => Ruangan::where('status', 'sudah_direservasi')->count(),
            'maintenance' => Ruangan::where('status', 'maintenance')->count(),
        ];
    }
}
