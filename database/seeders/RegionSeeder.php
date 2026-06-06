<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = [
            [
                'name' => 'Indramayu',
                'code' => 'IMY',
                'latitude' => -6.3277,
                'longitude' => 108.3246,
                'color_hex' => '#e67e22',
                'description' => 'Kabupaten Indramayu dikenal dengan wisata pantai dan pesisir utara.',
                'is_active' => true,
            ],
            [
                'name' => 'Cirebon',
                'code' => 'CRB',
                'latitude' => -6.7063,
                'longitude' => 108.5570,
                'color_hex' => '#0d7a6a',
                'description' => 'Cirebon kaya akan sejarah, keraton, dan wisata kuliner khas.',
                'is_active' => true,
            ],
            [
                'name' => 'Majalengka',
                'code' => 'MJK',
                'latitude' => -6.8365,
                'longitude' => 108.2273,
                'color_hex' => '#3498db',
                'description' => 'Majalengka memiliki keindahan alam pegunungan, terasering, dan air terjun.',
                'is_active' => true,
            ],
            [
                'name' => 'Kuningan',
                'code' => 'KNG',
                'latitude' => -6.9764,
                'longitude' => 108.4743,
                'color_hex' => '#9b59b6',
                'description' => 'Kuningan berada di kaki Gunung Ciremai, sejuk dan banyak wisata alam.',
                'is_active' => true,
            ],
        ];

        foreach ($regions as $region) {
            \App\Models\Region::updateOrCreate(
                ['code' => $region['code']],
                $region
            );
        }
    }
}
