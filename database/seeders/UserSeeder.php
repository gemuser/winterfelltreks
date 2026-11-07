<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@winterfelltreks.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('winterfell2083'),
                'email_verified_at' => now(),
            ],
        );

        User::firstOrCreate(
            ['email' => 'asmin@winterfelltreks.com'],
            [
                'name' => 'Asmin Bhattarai',
                'password' => Hash::make('winterfell2083'),
                'email_verified_at' => now(),
            ],
        );
    }
}
