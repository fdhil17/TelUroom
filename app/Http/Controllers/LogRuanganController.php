<?php

namespace App\Http\Controllers;

use App\Models\LogRuangan;
use App\Models\Ruangan;
use Illuminate\Http\Request;

class LogRuanganController extends Controller
{
    public function index(Request $request)
    {
        $query = LogRuangan::with('ruangan', 'user', 'reservasi');

        if ($ruanganId = $request->input('ruangan_id')) {
            $query->where('ruangan_id', $ruanganId);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($tanggalDari = $request->input('tanggal_dari')) {
            $query->where('tanggal', '>=', $tanggalDari);
        }

        if ($tanggalSampai = $request->input('tanggal_sampai')) {
            $query->where('tanggal', '<=', $tanggalSampai);
        }

        $logs = $query->orderByDesc('tanggal')->orderByDesc('jam_mulai')->paginate(15)->withQueryString();

        return view('log.index', [
            'logs' => $logs,
            'ruangans' => Ruangan::orderBy('lantai')->orderBy('kode_ruangan')->get(),
            'ruanganId' => $request->input('ruangan_id'),
            'status' => $request->input('status'),
            'tanggalDari' => $request->input('tanggal_dari'),
            'tanggalSampai' => $request->input('tanggal_sampai'),
        ]);
    }
}
