<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Queue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function index()
    {
        // Cek apakah user sudah memiliki antrian aktif
        $existingQueue = Queue::whereHas('booking', function ($query) {
            $query->where('user_id', Auth::id());
        })->whereIn('status', ['waiting', 'in_progress'])->with(['booking.schedule.doctor'])->first();

        $schedules = Schedule::with('doctor')->whereDate('tanggal', '>=', now()->format('Y-m-d'))->orderBy('tanggal')->get();

        return view('user.booking.index', compact('schedules', 'existingQueue'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'catatan' => 'nullable|string|max:500',
        ]);

        // Cek apakah user sudah memiliki antrian aktif
        $existingQueue = Queue::whereHas('booking', function ($query) {
            $query->where('user_id', Auth::id());
        })->whereIn('status', ['waiting', 'in_progress'])->first();

        if ($existingQueue) {
            return redirect()->route('user.booking.index')
                ->with('error', 'Anda sudah memiliki antrian aktif. Silakan selesaikan antrian yang ada terlebih dahulu.');
        }

        DB::transaction(function () use ($request) {
            // Buat booking
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'schedule_id' => $request->schedule_id,
                'status' => 'pending',
                'catatan' => $request->catatan,
            ]);

            // Dapatkan schedule untuk mendapatkan tanggal
            $schedule = Schedule::find($request->schedule_id);

            // Generate nomor antrian
            $queueNumber = Queue::generateQueueNumber($request->schedule_id, $schedule->tanggal);

            // Buat antrian otomatis dengan status waiting
            Queue::create([
                'booking_id' => $booking->id,
                'queue_number' => $queueNumber,
                'queue_date' => $schedule->tanggal,
                'status' => 'waiting',
            ]);
        });

        return redirect()->route('user.queue.today')->with('success', 'Booking berhasil! Anda telah masuk ke antrian. Silakan pantau status antrian Anda.');
    }

    public function history()
    {
        $bookings = Booking::with(['schedule.doctor', 'queue'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('user.booking.history', compact('bookings'));
    }

    /**
     * Menampilkan antrian user.
     */
    // public function queue()
    // {
    //     $queues = Queue::whereHas('booking', function ($query) {
    //         $query->where('user_id', Auth::id());
    //     })
    //         ->with([
    //             'booking' => function ($query) {
    //                 $query->with(['user', 'schedule.doctor']);
    //             }
    //         ])
    //         ->orderBy('queue_date', 'desc')
    //         ->orderBy('queue_number', 'asc')
    //         ->get();

    //     return view('user.booking.queue', compact('queues'));
    // }

    /**
     * Menampilkan antrian hari ini berdasarkan doctor.
     */
    public function todayQueue(Request $request)
    {
        $doctorId = $request->doctor_id;
        $today = now()->format('Y-m-d');

        $queues = Queue::with([
            'booking' => function ($query) {
                $query->with(['user', 'schedule.doctor']);
            }
        ])
            ->byDate($today)
            ->when($doctorId, function ($query) use ($doctorId) {
                return $query->byDoctor($doctorId);
            })
            ->orderBy('queue_number', 'asc')->where('status', '!=', 'completed')
            ->get();

        $doctors = \App\Models\Doctor::all();

        return view('user.booking.today-queue', compact('queues', 'doctors', 'doctorId'));
    }
}

