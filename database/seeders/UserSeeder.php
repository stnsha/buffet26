<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserRole;
use App\Models\Venue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all venues
        $venues = Venue::all();

        // Create first admin user (Anasuha)
        $superadmin = User::firstOrCreate(
            [
                'email' => 'anasuharosli@gmail.com'
            ],
            [
                'name' => 'Anasuha',
                'password' => env('SUPER_ADMIN_PASSWORD')
            ]
        );

        // Assign to all venues with Admin role
        foreach ($venues as $venue) {
            UserRole::firstOrCreate([
                'user_id' => $superadmin->id,
                'venue_id' => $venue->id,
            ], [
                'role' => 'Admin',
                'contact' => '01123456789',
            ]);
        }

        // Create second admin user (Nabila)
        $admin = User::firstOrCreate([
            'email' => 'nabilajunho@gmail.com',
        ], [
            'name' => 'Nabila',
            'password' => env('SUPER_ADMIN_PASSWORD')
        ]);

        // Assign to all venues with Admin role
        foreach ($venues as $venue) {
            UserRole::firstOrCreate([
                'user_id' => $admin->id,
                'venue_id' => $venue->id,
            ], [
                'role' => 'Admin',
                'contact' => '01123456789',
            ]);
        }
    }
}
