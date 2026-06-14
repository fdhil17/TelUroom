<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApprovalLogistikRequest;
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
        $reservasis = $this->reservasiService->getAllForLogistik($request->input('status'));

        return view('logistik.approval.index', [
            'reservasis' => $reservasis,
            'status' => $request->input('status'),
        ]);
    }

    public function show(Reservasi $reservasi)
    {
        return view('logistik.approval.show', [
            'reservasi' => $reservasi->load('user', 'ruangan', 'sscApprover'),
        ]);
    }

    public function process(ApprovalLogistikRequest $request, Reservasi $reservasi)
    {
        $data = $request->validated();

        if ($data['action'] === 'approve') {
            $this->reservasiService->approveByLogistik($reservasi, $request->user()->id, $data['catatan_logistik'] ?? null);
            $message = 'Pengajuan disetujui. QR Code peminjaman telah dibuat.';
        } else {
            $this->reservasiService->rejectByLogistik($reservasi, $request->user()->id, $data['catatan_logistik']);
            $message = 'Pengajuan berhasil ditolak oleh Logistik.';
        }

        return redirect()->route('logistik.approval.index')->with('success', $message);
    }
}
