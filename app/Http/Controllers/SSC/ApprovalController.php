<?php

namespace App\Http\Controllers\SSC;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApprovalSscRequest;
use App\Models\Reservasi;
use App\Services\ReservasiService;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function __construct(protected ReservasiService $reservasiService)
    {
    }

    public function index(Request $request)
    {
        $reservasis = $this->reservasiService->getAllForSsc($request->input('status'));

        return view('ssc.approval.index', [
            'reservasis' => $reservasis,
            'status' => $request->input('status'),
        ]);
    }

    public function show(Reservasi $reservasi)
    {
        return view('ssc.approval.show', [
            'reservasi' => $reservasi->load('user', 'ruangan'),
        ]);
    }

    public function process(ApprovalSscRequest $request, Reservasi $reservasi)
    {
        $data = $request->validated();

        if ($data['action'] === 'approve') {
            $this->reservasiService->approveBySsc($reservasi, $request->user()->id, $data['catatan_ssc'] ?? null);
            $message = 'Pengajuan berhasil diverifikasi dan diteruskan ke Logistik.';
        } else {
            $this->reservasiService->rejectBySsc($reservasi, $request->user()->id, $data['catatan_ssc']);
            $message = 'Pengajuan berhasil ditolak oleh SSC.';
        }

        return redirect()->route('ssc.approval.index')->with('success', $message);
    }
}
