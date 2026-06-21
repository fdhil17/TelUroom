<?php

namespace Database\Factories;

use App\Models\Ruangan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ruangan>
 */
class RuanganFactory extends Factory
{
    protected $model = Ruangan::class;

    public function definition(): array
    {
        return [
            'kode_ruangan' => 'R' . $this->faker->unique()->numberBetween(100, 999),
            'nama_ruangan' => 'Ruangan ' . $this->faker->word(),
            'lantai' => $this->faker->numberBetween(1, 5),
            'kapasitas' => $this->faker->numberBetween(20, 100),
            'status' => 'tersedia',
        ];
    }

    public function maintenance(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'maintenance',
        ]);
    }
}
