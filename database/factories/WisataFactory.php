<?php

namespace Database\Factories;

use App\Models\Wisata;
use Illuminate\Database\Eloquent\Factories\Factory;

class WisataFactory extends Factory
{
    protected $model = Wisata::class;

    public function definition(): array
    {
        return [
            'kode' => 'WIS-' . $this->faker->unique()->bothify('???-###'),
            'nama' => $this->faker->word(),
            'wilayah' => 'Indramayu',
            'kategori_utama' => 'Alam',
            'deskripsi' => $this->faker->sentence(),
            'lokasi_koordinat' => ['lat' => 0, 'lng' => 0],
            'fasilitas' => ['Parkir'],
            'harga_tiket' => 10000,
            'is_gratis' => false,
            'rating_avg' => 4.5,
            'total_review' => 10,
        ];
    }
}
