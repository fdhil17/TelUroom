<?php

namespace App\Http\Controllers;

use App\Services\ReservasiService;
use App\Services\RuanganService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use \App\Traits\HasRoleImpersonation;
    public function __construct(
        protected RuanganService $ruanganService,
        protected ReservasiService $reservasiService,
    ) {
    }

    public function index(Request $request)
    {
        $user = $request->user();

        // Tentukan role efektif
        $role = $this->getActiveRole($request);
        if ($role === 'admin') {
            $role = 'ssc'; // Fallback aman jika admin belum memilih role
        }

        return match ($role) {
            'mahasiswa' => $this->mahasiswaDashboard($user),
            'ssc'       => $this->sscDashboard(),
            'logistik'  => $this->logistikDashboard(),
            default     => abort(403, 'Role tidak valid.'),
        };
    }

    protected function mahasiswaDashboard($user)
    {
        $statistik = $this->reservasiService->getStatistikByUser($user->id);
        $reservasiTerbaru = $this->reservasiService->getRiwayatByUser($user->id);

        return view('mahasiswa.dashboard', [
            'statistik' => $statistik,
            'reservasiTerbaru' => $reservasiTerbaru->take(5),
        ]);
    }

    protected function sscDashboard()
    {
        $statistik = $this->reservasiService->getStatistikSsc();
        $reservasiTerbaru = $this->reservasiService->getReservasiTerbaruForSsc();

        return view('ssc.dashboard', [
            'statistik' => $statistik,
            'reservasiTerbaru' => $reservasiTerbaru,
        ]);
    }

    protected function logistikDashboard()
    {
        $statistikRuangan = $this->ruanganService->getStatistik();
        $statistikReservasi = $this->reservasiService->getStatistikLogistik();
        $reservasiTerbaru = $this->reservasiService->getReservasiTerbaruForLogistik();

        return view('logistik.dashboard', [
            'statistikRuangan' => $statistikRuangan,
            'statistikReservasi' => $statistikReservasi,
            'reservasiTerbaru' => $reservasiTerbaru,
        ]);
    }
}
