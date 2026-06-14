<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReservasiRequest;
use App\Models\Reservasi;
use App\Models\Ruangan;
use App\Services\ReservasiService;
use Illuminate\Http\Request;

class ReservasiController extends Controller
{
    public function __construct(protected ReservasiService $reservasiService)
    {
    }

    public function index()
    {
        $reservasis = $this->reservasiService->getRiwayatByUser(auth()->id());

        return view('mahasiswa.reservasi.index', [
            'reservasis' => $reservasis,
        ]);
    }

    public function create(Request $request)
    {
        $ruangans = Ruangan::where('status', '!=', 'maintenance')
            ->orderBy('lantai')->orderBy('kode_ruangan')->get();

        return view('mahasiswa.reservasi.create', [
            'ruangans' => $ruangans,
            'selectedRuanganId' => $request->input('ruangan_id'),
        ]);
    }

    public function store(ReservasiRequest $request)
    {
        $this->reservasiService->create($request->validated(), auth()->id());

        return redirect()->route('mahasiswa.reservasi.index')
            ->with('success', 'Pengajuan peminjaman berhasil dikirim. Menunggu verifikasi SSC.');
    }

    public function show(Reservasi $reservasi)
    {
        abort_unless($reservasi->user_id === auth()->id(), 403);

        return view('mahasiswa.reservasi.show', [
            'reservasi' => $reservasi->load('ruangan', 'sscApprover', 'logistikApprover'),
        ]);
    }
}
