<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Queue;
use App\Models\Doctor;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;

class QueueController extends Controller
{
    /**
     * Menampilkan daftar antrian.
     */
    public function index(Request $request)
    {
        $queues = Queue::with([
            'booking' => function ($query) {
                $query->with(['user', 'schedule.doctor']);
            }
        ])
            ->orderBy('queue_date', 'desc')
            ->orderBy('queue_number', 'asc')
            ->get();

        return view('Admin.queue.index', compact('queues'));
    }

    /**
     * Memulai antrian (mengubah status ke in_progress).
     */
    public function start(Queue $queue)
    {
        try {
            // Validate that the queue is in waiting status
            if ($queue->status !== 'waiting') {
                return redirect()->back()->with('error', 'Antrian tidak dalam status menunggu.');
            }

            DB::transaction(function () use ($queue) {
                $queue->update([
                    'status' => 'in_progress',
                ]);

                // Update booking status ke approved jika masih pending
                if ($queue->booking && $queue->booking->status === 'pending') {
                    $queue->booking->update(['status' => 'approved']);
                }
            });

            return redirect()->back()->with('success', 'Antrian telah dimulai dan pasien sedang dilayani.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menyelesaikan antrian (mengubah status ke completed).
     */
    public function complete(Queue $queue)
    {
        try {
            // Validate that the queue is in progress
            if ($queue->status !== 'in_progress') {
                return redirect()->back()->with('error', 'Antrian tidak dalam status sedang dilayani.');
            }

            $queue->update([
                'status' => 'completed',
            ]);

            return redirect()->back()->with('success', 'Layanan pasien telah selesai.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Membatalkan antrian (mengubah status ke cancelled).
     */
    public function cancel(Queue $queue)
    {
        try {
            // Validate that the queue can be cancelled
            if ($queue->status === 'completed') {
                return redirect()->back()->with('error', 'Antrian yang sudah selesai tidak dapat dibatalkan.');
            }

            if ($queue->status === 'cancelled') {
                return redirect()->back()->with('error', 'Antrian sudah dibatalkan sebelumnya.');
            }

            DB::transaction(function () use ($queue) {
                $queue->update(['status' => 'cancelled']);
                if ($queue->booking) {
                    $queue->booking->update(['status' => 'cancelled']);
                }
            });

            return redirect()->back()->with('success', 'Antrian telah dibatalkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Mengubah urutan antrian.
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'queue_ids' => 'required|array',
            'queue_ids.*' => 'exists:queues,id',
        ]);

        DB::transaction(function () use ($request) {
            foreach ($request->queue_ids as $index => $queueId) {
                Queue::where('id', $queueId)->update(['queue_number' => $index + 1]);
            }
        });

        return response()->json(['success' => true, 'message' => 'Urutan antrian berhasil diubah.']);
    }

    /**
     * Mendapatkan antrian berikutnya.
     */
    public function next(Request $request)
    {
        $doctorId = $request->doctor_id;
        $date = $request->date ?? now()->format('Y-m-d');

        $nextQueue = Queue::with([
            'booking' => function ($query) {
                $query->with(['user', 'schedule.doctor']);
            }
        ])
            ->byDate($date)
            ->when($doctorId, function ($query) use ($doctorId) {
                return $query->byDoctor($doctorId);
            })
            ->where('status', 'waiting')
            ->orderBy('queue_number', 'asc')
            ->first();

        if (!$nextQueue) {
            return response()->json(['success' => false, 'message' => 'Tidak ada antrian selanjutnya.']);
        }

        return response()->json([
            'success' => true,
            'queue' => $nextQueue,
            'user' => $nextQueue->booking->user,
            'doctor' => $nextQueue->booking->schedule->doctor,
        ]);
    }

    /**
     * Bulk update queue status
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'queue_ids' => 'required|array',
            'queue_ids.*' => 'exists:queues,id',
            'action' => 'required|in:start,complete,cancel'
        ]);

        DB::transaction(function () use ($request) {
            $queues = Queue::whereIn('id', $request->queue_ids)->get();

            foreach ($queues as $queue) {
                switch ($request->action) {
                    case 'start':
                        if ($queue->status === 'waiting') {
                            $queue->update(['status' => 'in_progress']);
                            if ($queue->booking->status === 'pending') {
                                $queue->booking->update(['status' => 'approved']);
                            }
                        }
                        break;
                    case 'complete':
                        if ($queue->status === 'in_progress') {
                            $queue->update(['status' => 'completed']);
                        }
                        break;
                    case 'cancel':
                        if ($queue->status !== 'completed') {
                            $queue->update(['status' => 'cancelled']);
                            $queue->booking->update(['status' => 'cancelled']);
                        }
                        break;
                }
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Status antrian berhasil diperbarui secara bulk.'
        ]);
    }

    /**
     * Get queue statistics for AJAX
     */
    public function getStats(Request $request)
    {
        $date = $request->date ?? now()->format('Y-m-d');
        $doctorId = $request->doctor_id;

        $query = Queue::byDate($date);

        if ($doctorId) {
            $query->byDoctor($doctorId);
        }

        $stats = [
            'total_waiting' => $query->clone()->where('status', 'waiting')->count(),
            'total_in_progress' => $query->clone()->where('status', 'in_progress')->count(),
            'total_completed' => $query->clone()->where('status', 'completed')->count(),
            'total_cancelled' => $query->clone()->where('status', 'cancelled')->count(),
        ];

        return response()->json($stats);
    }
}
