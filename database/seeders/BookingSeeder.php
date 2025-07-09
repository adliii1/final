<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use App\Models\Schedule;

class BookingSeeder extends Seeder
{
    public function run()
    {
        $user = User::first();
        $schedule = Schedule::first();

        if ($user && $schedule) {
            Booking::create([
                'user_id' => $user->id,
                'schedule_id' => $schedule->id,
                'status' => 'pending',
                'catatan' => 'Ingin konsultasi ringan.'
            ]);
        }
    }
}

