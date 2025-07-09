<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@klinik.com'], // Kunci untuk mencari user
            [
                'name' => 'Admin User',
                'password' => Hash::make('password123'), // Ganti 'password123' dengan password yang aman
                'role' => 'admin',
            ]
        );
    }
}