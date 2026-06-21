<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\Controller;
use App\Http\Requests\RuanganRequest;
use App\Models\Ruangan;
use App\Services\RuanganService;
use Illuminate\Http\Request;

class RuanganController extends Controller
{
    public function __construct(protected RuanganService $ruanganService)
    {
    }

    public function index(Request $request)
    {
        $ruangans = $this->ruanganService->getAll(
            search: $request->input('search'),
            lantai: $request->input('lantai') ? (int) $request->input('lantai') : null,
        );

        return view('logistik.ruangan.index', [
            'ruangans' => $ruangans,
            'search' => $request->input('search'),
            'lantai' => $request->input('lantai'),
        ]);
    }

    public function create()
    {
        return view('logistik.ruangan.create');
    }

    public function store(RuanganRequest $request)
    {
        $this->ruanganService->create($request->validated());

        return redirect()->route('logistik.ruangan.index')
            ->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function edit(Ruangan $ruangan)
    {
        return view('logistik.ruangan.edit', ['ruangan' => $ruangan]);
    }

    public function update(RuanganRequest $request, Ruangan $ruangan)
    {
        $this->ruanganService->update($ruangan, $request->validated());

        return redirect()->route('logistik.ruangan.index')
            ->with('success', 'Ruangan berhasil diperbarui.');
    }

    public function destroy(Ruangan $ruangan)
    {
        try {
            $this->ruanganService->delete($ruangan);
            return redirect()->route('logistik.ruangan.index')
                ->with('success', 'Ruangan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('logistik.ruangan.index')
                ->with('error', $e->getMessage());
        }
    }
}
