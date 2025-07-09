<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat user dummy
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);


        // Panggil seeder lainnya
        $this->call([
            AdminUserSeeder::class,
            DoctorSeeder::class,
            ScheduleSeeder::class,
            BookingSeeder::class,
            QueueSeeder::class,
        ]);
    }
}
