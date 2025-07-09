<?php

namespace App\Http\Controllers\Admin;
use App\Models\Booking;
use App\Models\Doctor;
use App\Models\Queue;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        // Get latest bookings with their queue information
        $latestBookings = Booking::with(['user', 'schedule.doctor', 'queue'])
            ->whereDate('created_at', today())
            ->latest()
            ->take(10)
            ->get();

        $queueStats = [
            'waiting' => Queue::where('status', 'waiting')->whereDate('queue_date', today())->count(),
            'in_progress' => Queue::where('status', 'in_progress')->whereDate('queue_date', today())->count(),
            'completed' => Queue::where('status', 'completed')->whereDate('queue_date', today())->count(),
        ];

        return view('Admin.dashboard', [
            'bookingCount' => Booking::whereDate('created_at', today())->count(),
            'doctorCount' => Doctor::count(),
            'latestBookings' => $latestBookings,
            'queueStats' => $queueStats
        ]);
    }
}
