@extends('layouts.app')
@section('title', 'Riwayat Booking')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('Riwayat Booking') }}</h4>
                    </div>

                    <div class="card-body">
                        @if($bookings->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Dokter</th>
                                            <th>Spesialis</th>
                                            <th>Tanggal</th>
                                            <th>Jam</th>
                                            <th>Status Booking</th>
                                            <th>No. Antrian</th>
                                            <th>Status Antrian</th>
                                            <th>Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($bookings as $booking)
                                            <tr>
                                                <td>{{ $booking->schedule->doctor->name }}</td>
                                                <td>{{ $booking->schedule->doctor->specialist }}</td>
                                                <td>{{ \Carbon\Carbon::parse($booking->schedule->tanggal)->format('d/m/Y') }}</td>
                                                <td>{{ $booking->schedule->waktu_mulai }} - {{ $booking->schedule->waktu_selesai }}
                                                </td>
                                                <td>
                                                    @if($booking->status === 'pending')
                                                        <span class="badge badge-warning">Pending</span>
                                                    @elseif($booking->status === 'approved')
                                                        <span class="badge badge-success">Disetujui</span>
                                                    @elseif($booking->status === 'cancelled')
                                                        <span class="badge badge-danger">Dibatalkan</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($booking->queue)
                                                        <span class="badge badge-primary">{{ $booking->queue->queue_number }}</span>
                                                    @else
                                                        -
                                                    @endif
                                                </td>
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
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ $booking->catatan ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <h5>Belum ada riwayat booking</h5>
                                <p>Anda belum pernah melakukan booking. <a href="{{ route('user.booking.index') }}">Buat booking
                                        sekarang</a>.</p>
                            </div>
                        @endif

                        <div class="mt-3">
                            <a href="{{ route('user.booking.index') }}" class="btn btn-primary">Buat Booking Baru</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection