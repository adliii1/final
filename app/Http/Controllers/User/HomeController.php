<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Queue;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{    /**
     * Menampilkan halaman Utama (dashboard) untuk pengguna.
     * Method ini akan dipanggil oleh route yang menuju ke controller ini.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $today = now()->format('Y-m-d');
        $userId = Auth::id();

        // Ambil antrian user yang sedang login untuk hari ini
        $userQueue = Queue::with(['booking.schedule.doctor'])
            ->whereHas('booking', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->whereDate('queue_date', $today)
            ->whereIn('status', ['waiting', 'in_progress'])
            ->first();

        // Ambil semua antrian hari ini dengan relasi untuk mengurangi query
        $todayQueues = Queue::with(['booking.schedule.doctor', 'booking.user'])
            ->whereDate('queue_date', $today)
            ->whereIn('status', ['waiting', 'in_progress'])
            ->get();

        // Group antrian yang sedang dilayani per dokter
        $currentlyServed = $todayQueues
            ->where('status', 'in_progress')
            ->groupBy(function ($queue) {
                return $queue->booking->schedule->doctor->id;
            });

        // Hitung antrian menunggu per dokter
        $waitingCounts = $todayQueues
            ->where('status', 'waiting')
            ->groupBy(function ($queue) {
                return $queue->booking->schedule->doctor->id;
            })
            ->map(function ($queues) {
                return $queues->count();
            });

        // Ambil semua dokter yang ada jadwal hari ini
        $doctorsWithSchedule = Doctor::whereHas('schedules', function ($query) use ($today) {
            $query->whereDate('tanggal', $today);
        })->get();

        return view('user.home', compact(
            'userQueue',
            'currentlyServed',
            'waitingCounts',
            'doctorsWithSchedule'
        ));
    }

}