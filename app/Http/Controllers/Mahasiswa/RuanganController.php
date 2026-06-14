<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function index(Request $request)
    {
        $query = Ruangan::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('kode_ruangan', 'like', "%{$search}%")
                  ->orWhere('nama_ruangan', 'like', "%{$search}%");
            });
        }

        if ($lantai = $request->input('lantai')) {
            $query->where('lantai', $lantai);
        }

        $ruangans = $query->orderBy('lantai')->orderBy('kode_ruangan')->paginate(12)->withQueryString();

        return view('mahasiswa.ruangan.index', [
            'ruangans' => $ruangans,
            'search' => $search,
            'lantai' => $lantai,
        ]);
    }

    public function show(Ruangan $ruangan)
    {
        $jadwalAkademiks = $ruangan->jadwalAkademiks()->orderBy('hari')->orderBy('jam_mulai')->get();

        return view('mahasiswa.ruangan.show', [
            'ruangan' => $ruangan,
            'jadwalAkademiks' => $jadwalAkademiks,
        ]);
    }
}
