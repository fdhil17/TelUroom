<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use App\Services\ReservasiService;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function __construct(protected ReservasiService $reservasiService)
    {
    }

    public function index()
    {
        return view('calendar.index', [
            'ruangans' => Ruangan::orderBy('lantai')->orderBy('kode_ruangan')->get(),
        ]);
    }

    public function events(Request $request)
    {
        $ruanganId = $request->input('ruangan_id') ? (int) $request->input('ruangan_id') : null;

        return response()->json($this->reservasiService->getCalendarEvents($ruanganId));
    }
}
