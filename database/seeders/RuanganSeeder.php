<?php

namespace Database\Seeders;

use App\Models\Ruangan;
use Illuminate\Database\Seeder;

class RuanganSeeder extends Seeder
{
    public function run(): void
    {
        $ruanganLantai1 = ['1.02', '1.03', '1.04', '1.05', '1.06'];
        $ruanganLantai2 = ['2.20', '2.21', '2.22', '2.23', '2.24'];

        foreach ($ruanganLantai1 as $kode) {
            Ruangan::create([
                'kode_ruangan' => $kode,
                'nama_ruangan' => 'Ruang ' . $kode,
                'lantai' => 1,
                'kapasitas' => 40,
                'status' => 'tersedia',
            ]);
        }

        foreach ($ruanganLantai2 as $kode) {
            Ruangan::create([
                'kode_ruangan' => $kode,
                'nama_ruangan' => 'Ruang ' . $kode,
                'lantai' => 2,
                'kapasitas' => 40,
                'status' => 'tersedia',
            ]);
        }
    }
}
