<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Schedule;
use App\Models\Doctor;

class ScheduleSeeder extends Seeder
{
    public function run()
    {
        $doctors = Doctor::all();

        foreach ($doctors as $doctor) {
            // Schedule untuk hari ini
            Schedule::create([
                'doctor_id' => $doctor->id,
                'tanggal' => now()->toDateString(),
                'waktu_mulai' => '08:00',
                'waktu_selesai' => '12:00',
            ]);

            // Schedule untuk besok
            Schedule::create([
                'doctor_id' => $doctor->id,
                'tanggal' => now()->addDays(1)->toDateString(),
                'waktu_mulai' => '09:00',
                'waktu_selesai' => '11:00',
            ]);

            // Schedule untuk lusa
            Schedule::create([
                'doctor_id' => $doctor->id,
                'tanggal' => now()->addDays(2)->toDateString(),
                'waktu_mulai' => '13:00',
                'waktu_selesai' => '17:00',
            ]);
        }
    }
}

