<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1 akun admin (bisa login sebagai SSC atau Logistik)
        User::create([
            'name' => 'Admin TelUroom',
            'email' => 'admin@teluroom.com',
            'password' => Hash::make('admin'),
            'role' => 'admin',
        ]);

        // Contoh mahasiswa (bisa register sendiri)
        User::create([
            'name' => 'Mahasiswa Test',
            'nim' => '1234567890',
            'prodi' => 'S1 Informatika',
            'email' => 'mahasiswa@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'mahasiswa',
        ]);
    }
}
