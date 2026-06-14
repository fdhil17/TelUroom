<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\Controller;
use App\Http\Requests\JadwalAkademikRequest;
use App\Models\JadwalAkademik;
use App\Models\Ruangan;
use App\Services\JadwalAkademikService;
use Illuminate\Http\Request;

class JadwalAkademikController extends Controller
{
    public function __construct(protected JadwalAkademikService $jadwalService)
    {
    }

    public function index(Request $request)
    {
        $jadwals = $this->jadwalService->getAll(
            ruanganId: $request->input('ruangan_id') ? (int) $request->input('ruangan_id') : null,
            hari: $request->input('hari'),
        );

        return view('logistik.jadwal.index', [
            'jadwals' => $jadwals,
            'ruangans' => Ruangan::orderBy('lantai')->orderBy('kode_ruangan')->get(),
            'ruanganId' => $request->input('ruangan_id'),
            'hari' => $request->input('hari'),
        ]);
    }

    public function create()
    {
        return view('logistik.jadwal.create', [
            'ruangans' => Ruangan::orderBy('lantai')->orderBy('kode_ruangan')->get(),
        ]);
    }

    public function store(JadwalAkademikRequest $request)
    {
        $this->jadwalService->create($request->validated(), $request->user()->id);

        return redirect()->route('logistik.jadwal.index')
            ->with('success', 'Jadwal akademik berhasil ditambahkan.');
    }

    public function edit(JadwalAkademik $jadwal)
    {
        return view('logistik.jadwal.edit', [
            'jadwal' => $jadwal,
            'ruangans' => Ruangan::orderBy('lantai')->orderBy('kode_ruangan')->get(),
        ]);
    }

    public function update(JadwalAkademikRequest $request, JadwalAkademik $jadwal)
    {
        $this->jadwalService->update($jadwal, $request->validated());

        return redirect()->route('logistik.jadwal.index')
            ->with('success', 'Jadwal akademik berhasil diperbarui.');
    }

    public function destroy(JadwalAkademik $jadwal)
    {
        $this->jadwalService->delete($jadwal);

        return redirect()->route('logistik.jadwal.index')
            ->with('success', 'Jadwal akademik berhasil dihapus.');
    }
}
