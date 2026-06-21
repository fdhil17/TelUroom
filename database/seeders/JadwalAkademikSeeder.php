<?php

namespace Database\Seeders;

use App\Models\JadwalAkademik;
use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Database\Seeder;

class JadwalAkademikSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $ruangan102 = Ruangan::where('kode_ruangan', '1.02')->first();
        $ruangan220 = Ruangan::where('kode_ruangan', '2.20')->first();

        JadwalAkademik::create([
            'ruangan_id' => $ruangan102->id,
            'hari' => 'senin',
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'mata_kuliah' => 'Pemrograman Web',
            'dosen' => 'Dr. Budi Santoso',
            'created_by' => $admin->id,
        ]);

        JadwalAkademik::create([
            'ruangan_id' => $ruangan220->id,
            'hari' => 'selasa',
            'jam_mulai' => '10:00',
            'jam_selesai' => '12:00',
            'mata_kuliah' => 'Basis Data',
            'dosen' => 'Dr. Siti Aminah',
            'created_by' => $admin->id,
        ]);
    }
}
