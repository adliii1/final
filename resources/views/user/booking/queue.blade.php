@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">
                        <h4>{{ __('Antrian Saya') }}</h4>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Debug Info -->
                        <div class="alert alert-info">
                            <small>Total antrian ditemukan: {{ $queues->count() }}</small>
                        </div>

                        @if($queues->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No. Antrian</th>
                                            <th>Tanggal</th>
                                            <th>Dokter</th>
                                            <th>Spesialis</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($queues as $queue)
                                            <tr class="
                                                            @if($queue->status === 'waiting') table-warning
                                                            @elseif($queue->status === 'in_progress') table-info
                                                            @elseif($queue->status === 'completed') table-success
                                                            @elseif($queue->status === 'cancelled') table-danger
                                                            @endif
                                                        ">
                                                <td>
                                                    <span class="badge badge-lg 
                                                                                                    @if($queue->status === 'waiting') badge-warning
                                                                                                    @elseif($queue->status === 'in_progress') badge-info
                                                                                                    @elseif($queue->status === 'completed') badge-success
                                                                                                    @elseif($queue->status === 'cancelled') badge-danger
                                                                                                    @endif
                                                                                                ">
                                                        {{ $queue->queue_number }}
                                                    </span>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($queue->queue_date)->format('d/m/Y') }}</td>
                                                <td>{{ $queue->booking->schedule->doctor->name }}</td>
                                                <td>{{ $queue->booking->schedule->doctor->specialist }}</td>
                                                <td>
                                                    @if($queue->status === 'waiting')
                                                        <span class="badge badge-warning">Menunggu</span>
                                                    @elseif($queue->status === 'in_progress')
                                                        <span class="badge badge-info">Sedang Dilayani</span>
                                                    @elseif($queue->status === 'completed')
                                                        <span class="badge badge-success">Selesai</span>
                                                    @elseif($queue->status === 'cancelled')
                                                        <span class="badge badge-danger">Dibatalkan</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <h5>Tidak ada antrian</h5>
                                <p>Anda belum memiliki antrian saat ini. <a href="{{ route('user.booking.index') }}">Buat
                                        booking</a> untuk masuk ke antrian.</p>
                            </div>
                        @endif

                        <div class="mt-3">
                            <a href="{{ route('user.queue.today') }}" class="btn btn-info">Lihat Antrian Hari Ini</a>
                            <a href="{{ route('user.booking.index') }}" class="btn btn-primary">Buat Booking Baru</a>
                            <a href="{{ route('user.booking.history') }}" class="btn btn-secondary">Riwayat Booking</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection