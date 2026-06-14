<?php

namespace App\Http\Controllers;

use App\Models\Reservasi;

class VerifikasiController extends Controller
{
    public function show(Reservasi $reservasi)
    {
        return view('verifikasi.show', [
            'reservasi' => $reservasi->load('user', 'ruangan'),
        ]);
    }
}
