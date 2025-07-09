<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Queue;
use App\Models\Booking;
use App\Models\Schedule;

class QueueSeeder extends Seeder
{
    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        // Ambil semua booking yang ada
        $bookings = Booking::with('schedule')->get();

        foreach ($bookings as $booking) {
            $schedule = $booking->schedule;

            // Generate nomor antrian
            $queueNumber = Queue::generateQueueNumber($schedule->id, $schedule->tanggal);

            // Buat antrian
            Queue::create([
                'booking_id' => $booking->id,
                'queue_number' => $queueNumber,
                'queue_date' => $schedule->tanggal,
                'status' => $booking->status === 'approved' ? 'waiting' : 'waiting',
            ]);
        }
    }
}
