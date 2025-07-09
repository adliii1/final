<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\Queue;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;

class TodayQueueSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $today = Carbon::today()->format('Y-m-d');

        // Ambil beberapa user untuk membuat antrian
        $users = User::where('role', '!=', 'admin')->take(5)->get();

        // Ambil atau buat schedule untuk hari ini
        $schedules = Schedule::whereDate('tanggal', $today)->get();

        if ($schedules->isEmpty()) {
            // Buat schedule untuk hari ini jika belum ada
            $doctors = \App\Models\Doctor::all();

            foreach ($doctors as $doctor) {
                $schedule = Schedule::create([
                    'doctor_id' => $doctor->id,
                    'tanggal' => $today,
                    'waktu_mulai' => '08:00:00',
                    'waktu_selesai' => '12:00:00',
                ]);
            }

            // Refresh schedules setelah dibuat
            $schedules = Schedule::whereDate('tanggal', $today)->get();
        }

        // Buat queue untuk beberapa user (pastikan setiap user hanya punya 1 antrian aktif)
        $queueNumber = 1;

        foreach ($schedules->take(2) as $schedule) { // Ambil 2 schedule saja
            foreach ($users->take(3) as $index => $user) { // Maksimal 3 user per schedule
                // Cek apakah user sudah punya antrian aktif
                $hasActiveQueue = Queue::whereHas('booking', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                })->whereIn('status', ['waiting', 'in_progress'])->exists();

                if (!$hasActiveQueue) {
                    $booking = Booking::create([
                        'user_id' => $user->id,
                        'schedule_id' => $schedule->id,
                        'status' => 'approved',
                        'catatan' => "Keluhan pasien untuk Dr. {$schedule->doctor->name}",
                    ]);

                    Queue::create([
                        'booking_id' => $booking->id,
                        'queue_number' => $queueNumber,
                        'queue_date' => $today,
                        'status' => $queueNumber == 1 ? 'in_progress' : 'waiting',
                    ]);

                    $queueNumber++;
                }
            }
        }
    }
}
