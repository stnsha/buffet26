<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            [
                'email' => 'anasuharosli@gmail.com'
            ],
            [
                'name' => 'superadmin',
                'password' => env('SUPER_ADMIN_PASSWORD')
            ]
        );

        User::firstOrCreate([
            'email' => 'nabilajunho@gmail.com',
        ], [
            'name' => 'Nabila',
            'password' => env('SUPER_ADMIN_PASSWORD')
        ]);
    }
}
