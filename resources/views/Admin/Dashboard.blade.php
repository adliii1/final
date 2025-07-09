@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('styles')
    <style>
        .dashboard-card {
            transition: transform 0.2s ease-in-out;
        }

        .dashboard-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table td {
            vertical-align: middle;
        }

        .badge-lg {
            font-size: 1em;
            padding: 0.5em 0.75em;
        }

        /* Action buttons styling */
        .btn-group .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            margin-right: 2px;
        }

        .btn-group .btn-sm:last-child {
            margin-right: 0;
        }

        /* Responsive table actions */
        @media (max-width: 768px) {
            .btn-group {
                display: flex;
                flex-direction: column;
                gap: 2px;
            }

            .btn-sm {
                font-size: 0.75rem;
                padding: 0.2rem 0.4rem;
            }
        }
    </style>
@endsection

@section('content')
    <h1>Dashboard Admin</h1>
    <p>Selamat datang, {{ auth()->user()->name }}!</p>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white dashboard-card">
                <div class="card-body text-center">
                    <i class="fas fa-calendar-check fa-2x mb-2"></i>
                    <h4>{{ $bookingCount }}</h4>
                    <p>Booking Hari Ini</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white dashboard-card">
                <div class="card-body text-center">
                    <i class="fas fa-user-md fa-2x mb-2"></i>
                    <h4>{{ $doctorCount }}</h4>
                    <p>Total Dokter</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white dashboard-card">
                <div class="card-body text-center">
                    <i class="fas fa-clock fa-2x mb-2"></i>
                    <h4>{{ $queueStats['waiting'] }}</h4>
                    <p>Antrian Menunggu</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Latest Bookings Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5><i class="fas fa-list-alt"></i> Booking Terbaru Hari Ini</h5>
                </div>
                <div class="card-body">
                    @if($latestBookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>No. Antrian</th>
                                        <th>Pasien</th>
                                        <th>Dokter</th>
                                        <th>Waktu</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($latestBookings as $booking)
                                        <tr>
                                            <td>
                                                @if($booking->queue)
                                                    <span class="badge badge-lg 
                                                                                                                                    @if($booking->queue->status === 'waiting') badge-warning
                                                                                                                                    @elseif($booking->queue->status === 'in_progress') badge-info
                                                                                                                                    @elseif($booking->queue->status === 'completed') badge-success
                                                                                                                                    @elseif($booking->queue->status === 'cancelled') badge-danger
                                                                                                                                    @endif
                                                                                                                                ">
                                                        {{ $booking->queue->queue_number }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>{{ $booking->user->name }}</td>
                                            <td>{{ $booking->schedule->doctor->name }}</td>
                                            <td>{{ $booking->schedule->waktu_mulai }} - {{ $booking->schedule->waktu_selesai }}</td>
                                            <td>
                                                @if($booking->queue)
                                                    @if($booking->queue->status === 'waiting')
                                                        <span class="badge badge-warning">Menunggu</span>
                                                    @elseif($booking->queue->status === 'in_progress')
                                                        <span class="badge badge-info">Sedang Dilayani</span>
                                                    @elseif($booking->queue->status === 'completed')
                                                        <span class="badge badge-success">Selesai</span>
                                                    @elseif($booking->queue->status === 'cancelled')
                                                        <span class="badge badge-danger">Dibatalkan</span>
                                                    @endif
                                                @else
                                                    <span class="badge badge-secondary">Belum Antri</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($booking->queue)
                                                    @if($booking->queue->status === 'waiting')
                                                        <div class="btn-group" role="group">
                                                            <form method="POST" action="{{ route('admin.queue.start', $booking->queue) }}"
                                                                class="d-inline queue-form">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-sm btn-success" title="Mulai Layanan"
                                                                    data-action="start">
                                                                    <i class="fas fa-play"></i> Dilayani
                                                                </button>
                                                            </form>
                                                            <form method="POST" action="{{ route('admin.queue.cancel', $booking->queue) }}"
                                                                class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-sm btn-danger" title="Batalkan Antrian"
                                                                    onclick="return confirm('Yakin ingin membatalkan antrian ini?')">
                                                                    <i class="fas fa-times"></i> Batalkan
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @elseif($booking->queue->status === 'in_progress')
                                                        <div class="btn-group" role="group">
                                                            <form method="POST"
                                                                action="{{ route('admin.queue.complete', $booking->queue) }}"
                                                                class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-sm btn-primary"
                                                                    title="Selesaikan Layanan">
                                                                    <i class="fas fa-check"></i> Selesai
                                                                </button>
                                                            </form>
                                                            <form method="POST" action="{{ route('admin.queue.cancel', $booking->queue) }}"
                                                                class="d-inline">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="btn btn-sm btn-warning"
                                                                    title="Batalkan Layanan"
                                                                    onclick="return confirm('Yakin ingin membatalkan layanan ini?')">
                                                                    <i class="fas fa-pause"></i> Batalkan
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @elseif($booking->queue->status === 'completed')
                                                        <div class="text-center">
                                                            <span class="text-success d-block mb-1">
                                                                <i class="fas fa-check-circle"></i> Selesai
                                                            </span>
                                                            <small class="text-muted">
                                                                {{ $booking->queue->updated_at->format('H:i') }}
                                                            </small>
                                                        </div>
                                                    @elseif($booking->queue->status === 'cancelled')
                                                        <span class="text-danger"><i class="fas fa-times-circle"></i> Dibatalkan</span>
                                                    @endif
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3 text-center">
                            <a href="{{ route('admin.queue.index') }}" class="btn btn-primary">
                                <i class="fas fa-list"></i> Lihat Semua Antrian
                            </a>
                        </div>
                    @else
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle fa-2x mb-3"></i>
                            <h5>Belum Ada Booking Hari Ini</h5>
                            <p>Tidak ada booking yang ditemukan untuk hari ini.</p>
                            <a href="{{ route('admin.booking.index') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Kelola Booking
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script>
        // Auto refresh dashboard every 30 seconds
        setInterval(function () {
            window.location.reload();
        }, 30000);

        // Add simple loading state to buttons
        document.addEventListener('DOMContentLoaded', function () {
            // Handle queue form submissions specifically
            const queueForms = document.querySelectorAll('.queue-form');
            queueForms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    const button = this.querySelector('button[type="submit"]');

                    // Add loading state after a brief delay to allow form submission
                    setTimeout(() => {
                        button.disabled = true;
                        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                    }, 50);
                });
            });

            // Handle other form buttons
            const otherButtons = document.querySelectorAll('form:not(.queue-form) button[type="submit"]');
            otherButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    // Don't apply loading state to cancel buttons (they have confirm dialogs)
                    if (this.onclick && this.onclick.toString().includes('confirm')) {
                        return;
                    }

                    // Add loading state after brief delay
                    const originalText = button.innerHTML;
                    setTimeout(() => {
                        button.disabled = true;
                        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                    }, 50);

                    // Re-enable button after 3 seconds as fallback
                    setTimeout(() => {
                        button.disabled = false;
                        button.innerHTML = originalText;
                    }, 3000);
                });
            });

            // Add simple hover effects for action buttons
            const actionButtons = document.querySelectorAll('.btn-group .btn');
            actionButtons.forEach(btn => {
                btn.addEventListener('mouseenter', function () {
                    if (!this.disabled) {
                        this.style.transform = 'scale(1.05)';
                    }
                });

                btn.addEventListener('mouseleave', function () {
                    this.style.transform = 'scale(1)';
                });
            });
        });
    </script>
@endsection