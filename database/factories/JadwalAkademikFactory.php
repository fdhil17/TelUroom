<?php

namespace Database\Factories;

use App\Models\JadwalAkademik;
use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JadwalAkademik>
 */
class JadwalAkademikFactory extends Factory
{
    protected $model = JadwalAkademik::class;

    public function definition(): array
    {
        return [
            'ruangan_id' => Ruangan::factory(),
            'hari' => $this->faker->randomElement(['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu']),
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
            'mata_kuliah' => $this->faker->sentence(3),
            'dosen' => $this->faker->name(),
            'created_by' => User::factory(),
        ];
    }
}
