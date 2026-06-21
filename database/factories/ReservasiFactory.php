<?php

namespace Database\Factories;

use App\Models\Reservasi;
use App\Models\Ruangan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservasi>
 */
class ReservasiFactory extends Factory
{
    protected $model = Reservasi::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'ruangan_id' => Ruangan::factory(),
            'tanggal_reservasi' => now()->addDays(1)->format('Y-m-d'),
            'jam_mulai' => '10:00:00',
            'jam_selesai' => '12:00:00',
            'keperluan' => $this->faker->sentence(),
            'status' => 'menunggu_ssc',
            'catatan_ssc' => null,
            'catatan_logistik' => null,
            'approved_by_ssc' => null,
            'approved_by_logistik' => null,
            'qr_code' => null,
        ];
    }

    public function disetujui(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'disetujui',
        ]);
    }
}
