<?php

namespace Database\Seeders;

use App\Models\Venue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VenueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Venue::firstOrCreate(
            ['code' => 'ARN'],
            [
                'name' => 'Dewan Arena',
                'address' => 'Lot 31848 batu 2 1, 4, Jalan Sikamat, 70400 Seremban, Negeri Sembilan.',
                'gmap_url' => null,
                'waze_url' => null,
            ]
        );

        Venue::firstOrCreate(
            ['code' => 'CMN'],
            [
                'name' => 'Dewan Chermin',
                'address' => '4741, Jalan TS 1/19, Taman Semarak, 71800 Nilai, Negeri Sembilan.',
                'gmap_url' => null,
                'waze_url' => null,
            ]
        );
    }
}
